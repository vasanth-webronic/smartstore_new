<?php

class S3Manager {

    private $s3Client;
    private $CDN_URL;
    private $S3_BUCKET;
    private $S_KEY;
    private $S_SECRET;

    function init(){

        $config=unserialize(TAW_S3_SETTINGS);
       
        $this->CDN_URL=$config['cdn'] ?? "";    
        $this->S3_BUCKET=$config['bucket'] ?? "";    
        $this->S_KEY=$config['key'] ?? "";    
        $this->S_SECRET=$config['secret'] ?? "";       

        add_action('admin_enqueue_scripts', array($this,'taw_s3_load_script'));
        add_filter( 'wp_get_attachment_url',array($this,'taw_s3_update_url'),10,2);
        add_action("wp_ajax_syncMediaToS3", array($this,'syncMediaToS3'));
        add_action("wp_ajax_createS3Meta", array($this,'createS3Meta'));
        add_action('admin_menu', array($this,'my_s3_plugin_menu'));
        add_filter( 'wp_generate_attachment_metadata', array($this,'uploadNewImage'),10,2);
        add_filter('wp_calculate_image_srcset',  array($this,'taw_calculate_image_srcset'),10, 5);
    }

    //change src url
    function taw_calculate_image_srcset($sources, $size_array, $image_src, $image_meta, $attachment_id) {       
      
        if (!empty($this->CDN_URL)&&str_contains($image_src,$this->CDN_URL)) {     
              $temp=[]; 
            foreach($sources as $s){                
                $linkAr=explode("/",$s['url']);
                $url=$this->CDN_URL.end($linkAr);           
                $s['url']=$url;
                $temp[]=$s;              
            }
            $sources=$temp;
        }
      
        return $sources;
    }

    function uploadNewImage($metadata,$post_id){
        //error_log(json_encode($metadata));
        $this->uploadFileInBg($post_id,$metadata);
        
        return $metadata;  
    }

    //initialize background task for new image upload
    function taw_s3_load_script(){
        wp_enqueue_media();
        wp_enqueue_style('taw-s3-css',  TAW_S3_MANAGER_BASE . '/css/style.css' . TAW_FILE_VERSION);
        wp_enqueue_script('taw-s3-js', TAW_S3_MANAGER_BASE . '/js/script.js' . TAW_FILE_VERSION, ['jquery', 'jquery-migrate'], null, true);
    }

    function my_s3_plugin_menu()
    {
        add_menu_page("S3 Manager", "S3 Manager", "manage_options", "s3_manage", [$this,"mangageS3ConfigPage"], "dashicons-screenoptions", 10);
    }


    function mangageS3ConfigPage()
    {
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }
        
        include_once(TAW_S3_MANAGER_DIR . '/template/page-admin-manage-s3.php');
    }

    function createS3Meta(){
        global $wpdb;
        $count=$wpdb->get_var("SELECT count(id) as count FROM `{$wpdb->prefix}posts` where post_type='attachment'");

        $perPage=200;
        $totalPage=1;
        if($count>$perPage){
            $totalPage=ceil($count/$perPage);
        }

        for($i=0;$i<$totalPage;$i++){

            $limit=($i*$perPage).",".$perPage;

            $data=$wpdb->get_results("SELECT ID FROM `{$wpdb->prefix}posts` where post_type='attachment' limit $limit");      
            
            //save glass types
            $values = array();
            $place_holders = array();
            $query = "INSERT INTO `{$wpdb->prefix}postmeta` (`post_id`, `meta_key`,`meta_value`) VALUES ";
            foreach ($data as  $d) {
                $post_id=$d->ID;
                if(!empty(get_post_meta($post_id,TAW_S3_META_KEY,true))){
                    continue;
                }
            
                array_push($values, $post_id,'taw_cdn', TAW_S3_CDN_YES);
                $place_holders[] = "('%d','%s','%s')";
            }

            if(!empty($values)){
                $query .= implode(', ', $place_holders);
                $q = $wpdb->prepare("$query ", $values);          
                $wpdb->query($q);
            }

        }
        echo "success";
    }

    //createS3Meta();
    //exit;

    function uploadFileInBg($post_id,$data){
        $this->initAwsClient();
        $status=$this->parseAttachmentData($post_id,$data);

        //for wpml other language support
        global $wpdb;
        $guid=$wpdb->get_var("SELECT guid FROM `{$wpdb->prefix}posts` WHERE ID=$post_id");
      
        if(!empty($guid)){
            $ids=$wpdb->get_results("SELECT ID FROM `{$wpdb->prefix}posts` WHERE guid='$guid'");
            foreach($ids as $v){
                if($v->ID!=$post_id){                  
                    update_post_meta( $v->ID, TAW_S3_META_KEY, $status );
                }
            }
        }
    }


    function taw_s3_update_url($url,$attach_id){
       
       // if(get_post_meta($attach_id,TAW_S3_META_KEY,true)=='y'){  
            
            $linkAr=explode("/",$url);
		//$url=$url;
		
       // $url=str_replace("smartstoring.eu","d3objcxk0x8mev.cloudfront.net",$url);    
		//$url=$this->CDN_URL.$url;
	//	$url=$this->CDN_URL.end($linkAr);

           
        //}   
        return $url;
    }

    function processUpdate($page_start,$page_item){
        global $wpdb;

        $this->initAwsClient();
        $q="SELECT id FROM `{$wpdb->prefix}posts` as p LEFT join `{$wpdb->prefix}postmeta` as pm on pm.post_id=p.id where post_type='attachment' and pm.meta_key='".TAW_S3_META_KEY."' and pm.meta_value='".TAW_S3_CDN_NO."' limit $page_start,$page_item";
     
        $result=$wpdb->get_results($q);
        
        foreach($result as $re){
            $post_id=$re->id;
            $attachements = get_post_meta($post_id, '_wp_attachment_metadata', true );
            $this->parseAttachmentData($post_id,$attachements);
        }
        
    }

    function initAwsClient(){
        require (TAW_S3_MANAGER_DIR.'/vendor/autoload.php');
        $this->s3Client = new Aws\S3\S3Client([
            'version'     => 'latest',
            'region'      => 'eu-north-1',
            'credentials' => ['key'=>$this->S_KEY,'secret'=>$this->S_SECRET],
        ]);       
    }

    function parseAttachmentData($post_id,$attachements){
        $upload_dir = wp_upload_dir()['basedir']."/";

        $attach_to_upload=[];

        if(is_file($upload_dir.$attachements['file'])){
            $attach_to_upload[$attachements['file']]=$upload_dir.$attachements['file'];
        }
        $sizes=$attachements['sizes'] ?? [];
        foreach($sizes as $k=>$v){
            if(is_file($upload_dir.$v['file'])){
                $attach_to_upload[$v['file']]=$upload_dir.$v['file'];
            }
        }

        // error_log(json_encode($attach_to_upload));
        $status=TAW_S3_CDN_NO_FILE;
        if(!empty($attach_to_upload)){           
            $this->uploadToS3($attach_to_upload);
            $status=TAW_S3_CDN_YES;
        }

        update_post_meta( $post_id, TAW_S3_META_KEY, $status );

        return $status;
    }


    function uploadToS3($attach_to_upload){
      
        foreach($attach_to_upload as $key=>$path){  
            
            //check file already exist
            if(is_file($this->CDN_URL.$key)){
                continue;
            }

            $result = $this->s3Client->putObject([
                'Bucket' => $this->S3_BUCKET,
                'Key' => $key,
                'SourceFile' => $path,
            ]);

            error_log(json_encode($result));
        }

        error_log("updataed to s3");
    }


    /** Step 3. */
    function syncMediaToS3(){
        $data = array_merge((array) $_GET, (array) $_POST);
        $count = isset($data['count']) ? $data['count'] : -1;
        $page_num = isset($data['page_num']) ? $data['page_num'] : 0; 

        global $wpdb;

        if($count==-1){
            $count=6;
            //$count=$wpdb->get_var("SELECT count(id) as id FROM `fxekc_posts` as p LEFT join fxekc_postmeta as pm on pm.post_id=p.id where post_type='attachment' and pm.meta_key='".TAW_S3_META_KEY."' and pm.meta_value='n'");           
        }

        $page_item=1;
        $page_start=$page_num*$page_item;        
        $percentage=floor($page_start/($count/100));
        $req=1;
        if($percentage>=100){
            $req=0;
        }
        $page_start=100;
        $this->processUpdate($page_start,$page_item);  
       
        // wp_send_json_success(["count"=>$count,'page_num'=>++$page_num,'end'=>0,
        // 'percentage'=>$percentage,'action'=>'syncMediaToS3','req'=>$req,
        // 'remaining_item'=>($count-$page_start)]);
    }
}