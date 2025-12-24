<?php

class TawImport {
    public function __construct() {
		add_action( 'wp_ajax_import_product', array( $this, 'importProduct' ) );       
	}    
    
    /** Step 3. */
    function importProduct()
    {
   
        $data = array_merge((array) $_GET, (array) $_POST);
        $folder_path = isset($_FILES['import_img_folder']) ? $_FILES['import_img_folder'] : [];
        $import_type = isset($data['import_type']) ? $data['import_type'] : "";
    
        check_ajax_referer('taw_security', 'taw_nonce');   
    
    
        if (!isset($_FILES["import_file"]) || empty($_FILES["import_file"]['size'])) {
            echo "no file selected";
            exit;
        }
    
        include_once(THINGSATWEB_DIR . '/vendor/autoload.php');
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($_FILES["import_file"]["tmp_name"]);
    
        global $wpdb;
        $heading = [];
        $end = 0;
        for ($i = 1; $i < 1000; $i++) {
            $v = $spreadsheet->getActiveSheet()->getCellByColumnAndRow($i, 1)->getValue();
            if (empty($v)) {
                $end = $i;
                break;
            }
            $heading[] = $v;
        }
        $res = 0;
        
        if ($import_type == "Price Update") {
            $res = $this->importPrice($spreadsheet, $heading, $end, $wpdb);
        } else if ($import_type == "Title and Description") {
            $res = $this->importTitleDesc($spreadsheet, $heading, $end, $wpdb);
        } else if ($import_type == "Attributes Update") {
            $res = $this->importProductAttributes($spreadsheet, $heading, $end, $wpdb);
        } else if ($import_type == "Category Update") {
            $res = $this->importProductCategory($spreadsheet, $heading, $end, $wpdb);
        } else if ($import_type == "Customer Update") {
            $res = $this->importCustomer($spreadsheet, $heading, $end, $wpdb);
        } else if ($import_type == "Customer Unique Price") {
            $res = $this->importCustomerPrice($spreadsheet, $heading, $end, $wpdb);
        } else if ($import_type == "Picture Update") {
            $res = $this->importPicture($spreadsheet, $heading, $end, $wpdb);
        }else if ($import_type == "Product Accessories") {
            $res = $this->importProductAccessories($spreadsheet, $heading, $end, $wpdb);
        }else if ($import_type == "Diagram Update") {
            $res = $this->importProductDiagram($spreadsheet, $heading, $end, $wpdb);
        }   
    
        if ($res == 1) {
            echo "Imported successfully";
        } else {
            echo "Could not import data. upload right xl file";
        }
    
        exit;
    }
    function importProductDiagram($spreadsheet, $heading, $end, $wpdb)
    {
        //check valid sheet
        $colum_check = ['Article_no', 'Diagram'];
        if (!empty(array_diff($colum_check, $heading))) {
            return -1;
        }

        $end_data = false;
        $data = [];

        for ($i = 2; $i < 10000; $i++) {
            if ($end_data) {
                break;
            }
            $customer = '';
            for ($j = 1; $j <= $end; $j++) {
                $v = $spreadsheet->getActiveSheet()->getCellByColumnAndRow($j, $i)->getValue();
                $v = trim($v);

                if (empty($v)) {
                    if ($j == 1) {
                        $end_data = true;
                        break;
                    }
                    continue;
                }
                //check family
                $h = trim($heading[$j - 1]);
                $a = trim($v);
                $data[$i][$h] = $a;
            }
        }
        //save glass types
        $values = array();
        $place_holders = array();

        foreach ($data as $art_no => $v) {
            $article_no = $v['Article_no'];
            $diagram = $v['Diagram'];
            $diagram_basename = pathinfo($diagram, PATHINFO_FILENAME);
        
            $query = "SELECT post_id FROM `tsm_postmeta` WHERE meta_value = '$article_no'";
            $sub_items = $wpdb->get_results($query);
        
            foreach ($sub_items as $item) {
                $meta = maybe_unserialize(get_post_meta($item->post_id, 'taw_prod_opt', true));
        
                $diagramurl = "http://smartstoring.test/wp-content/uploads/2023/07/$diagram";
                $diagram_id = checkMediaFile($wpdb, $diagram);
                $thumbnail_url = "http://smartstoring.test/wp-content/uploads/2023/07/$diagram_basename-150x150.jpg";
        
                // Update product_diagram_file id
                $queryid = "SELECT post_id FROM `tsm_postmeta` WHERE meta_key = '_wp_attached_file' AND meta_value = '2023/07/$diagram'";
                $sub_ids = $wpdb->get_results($queryid);
                foreach ($sub_ids as $ids) {
                    $meta['article_price']['product_diagram_file']['id'] = $ids->post_id;
        
                    // Update width and height
                    $widtheightid = "SELECT meta_value FROM `tsm_postmeta` WHERE meta_key = '_wp_attachment_metadata' AND post_id = $ids->post_id";
                    $width_ids = $wpdb->get_results($widtheightid);
                    foreach ($width_ids as $width_id) {
                        $meta_value = maybe_unserialize($width_id->meta_value);
                            $meta['article_price']['product_diagram_file']['width'] = $meta_value['width'];
                            $meta['article_price']['product_diagram_file']['height'] = $meta_value['height'];
                        
                    }
                }
        
                // Update other values
                $meta['article_price']['product_diagram_file']['url'] = $diagramurl;
                $meta['article_price']['product_diagram_file']['thumbnail'] = $thumbnail_url;
                $meta['article_price']['product_diagram_file']['title'] = $diagram;
        
                $attachment_id = $ids->post_id;
    update_post_meta($attachment_id, '_wp_attachment_image_alt', $diagram); // Set alt text as title
    wp_update_post(array('ID' => $attachment_id, 'post_title' => $diagram)); // Set title
    update_post_meta($attachment_id, '_wp_attachment_image_description', $diagram); // Set description
    update_post_meta($attachment_id, '_wp_attachment_metadata', maybe_unserialize($width_id->meta_value)); // Update metadata (optional)

    update_post_meta($item->post_id, 'taw_prod_opt', maybe_serialize($meta));
            }
        }
        return 1;
    }

    function importPicture($spreadsheet, $heading, $end, $wpdb)
    {
        //check valid sheet
        $colum_check = ['ART number', 'Feature Image','Gallery Images','Colour','Alt text','Lang'];
        if (!empty(array_diff($colum_check, $heading))) {
            return -1;
        }
    
        $end_data = false;
        $data = [];
    
        for ($i = 2; $i < 10000; $i++) {
            if ($end_data) {
                break;
            }
            $customer = '';
            for ($j = 1; $j <= $end; $j++) {
                $v = $spreadsheet->getActiveSheet()->getCellByColumnAndRow($j, $i)->getValue();
                $v = trim($v);
                if (empty($v)) {
                    if ($j == 1) {
                        $end_data = true;
                        break;
                    }
                    continue;
                }
                //check family
                $h = trim($heading[$j - 1]);
                $a = trim($v);   
                
                

                $data[$i][$h] = $a;
            }
        }

       
        $query = "INSERT INTO `taw_article_picture` (`art_no`, `pic_name`,`gallery_pics`,`colour`,`alt_text`,`attach_id`,`lang`,`unique_code`) VALUES ";
    
        //save glass types
        $values = array();
        $place_holders = array();
    
        foreach ($data as $art_no => $v) {
            $lang=$v['Lang'] ?? "";
            $art_no=$v['ART number'] ?? "";
            if(empty($lang)){
                continue;
            }

            $Picname = $v['Feature Image'];
            $pic_id = checkMediaFile($wpdb, $Picname);
            
            if (empty($pic_id)) {
                echo "Feature image : ".$Picname . " is not exist in media lib <br/>";
                continue;
            }

            $gallery_pics=$v['Gallery Images'] ?? "";
            $g_ar=explode("|",$gallery_pics);
            $ng_ar=[];
            foreach($g_ar as $g){
                if(empty($g)) continue;
                $g=trim($g);
                $m_id=checkMediaFile($wpdb, $g);
                if (empty($m_id)) {
                    echo "Gallery image : ".$g." is not exist in media lib <br/>";
                    continue;
                }
                $ng_ar[]=$m_id;
            }

            array_push($values, $art_no, $Picname, implode(",",$ng_ar), $v['Colour'] ?? "", $v['Alt text'] ?? "", $pic_id, $lang, $lang."::".$art_no);
            $place_holders[] = "('%s','%s','%s','%s','%s','%d','%s','%s')";
        }
    
        $query .= implode(', ', $place_holders);
        $q = $wpdb->prepare("$query ", $values). "  ON DUPLICATE KEY UPDATE `alt_text`=VALUES(`alt_text`), `gallery_pics`=VALUES(`gallery_pics`), `attach_id`=VALUES(`attach_id`), `pic_name`=VALUES(`pic_name`), `colour`=VALUES(`colour`)";
      
        $wpdb->query($q);
       
    
        return 1;
    }
    
    function importCustomerPrice($spreadsheet, $heading, $end, $wpdb)
    {
        //check valid sheet
        $colum_check = ['Artikelnr', 'Price', 'Currency', 'Customer'];
        if (!empty(array_diff($colum_check, $heading))) {
            return -1;
        }
    
        $end_data = false;
        $data = [];
    
        for ($i = 2; $i < 10000; $i++) {
            if ($end_data) {
                break;
            }
            $customer = '';
            for ($j = 1; $j <= $end; $j++) {
                $v = $spreadsheet->getActiveSheet()->getCellByColumnAndRow($j, $i)->getValue();
                $v = trim($v);
                if (empty($v)) {
                    if ($j == 1) {
                        $end_data = true;
                        break;
                    }
                    continue;
                }
                //check family
                $h = trim($heading[$j - 1]);
                $a = trim($v);
                $data[$i][$h] = $a;
            }
        }
     //save glass types
     $values = array();
     $place_holders = array();
        //remove old
        $q = 'TRUNCATE TABLE taw_customer_unique_price;';
        $wpdb->query($q);
        $query = "INSERT INTO `taw_customer_unique_price` (`art_no`, `price`,`currency`,`customer_no`,`uuid`) VALUES ";
    
       
    
        foreach ($data as $art_no => $v) {
            array_push($values, $v['Artikelnr'], $v['Price'], $v['Currency'], $v['Customer'],($v['Artikelnr']."::".$v['Currency']."::".$v['Customer']));
            $place_holders[] = "('%s','%s','%s','%s',%s)";
        }
    
        $query .= implode(', ', $place_holders);
        $q = $wpdb->prepare("$query ", $values). "  ON DUPLICATE KEY UPDATE `price`=VALUES(`price`)";
    
        $wpdb->query($q);
   
        return 1;

    }

    
    
    function importCustomer($spreadsheet, $heading, $end, $wpdb)
    {
        //check valid sheet
        $colum_check = ['Kundnr', 'Namn', 'Email', 'Gatuadress', 'Postnr', 'Postadress', 'Land', 'Telefon', 'Distrikt', 'PrislistaGruppCounter'];
        if (!empty(array_diff($colum_check, $heading))) {
            return -1;
        }
    
        $end_data = false;
        $data = [];
    
        for ($i = 2; $i < 10000; $i++) {
            if ($end_data) {
                break;
            }
            $Kundnr = '';
            for ($j = 1; $j <= $end; $j++) {
                $v = $spreadsheet->getActiveSheet()->getCellByColumnAndRow($j, $i)->getValue();
                $v = trim($v);
                if (empty($v)) {
                    if ($j == 1) {
                        $end_data = true;
                        break;
                    }
                    continue;
                }
                //check family
                $h = trim($heading[$j - 1]);
    
                if ($h == "Kundnr") {
                    $Kundnr = $v;
                } else {
                    $a = trim($v);
                    $data[$Kundnr][$h] = $v;
                }
            }
        }
    
        $user_role = ['' => TAW_ROLE_B2B, '1' => TAW_ROLE_B2B, '2' => TAW_ROLE_RESELLER_SEK, '3' => TAW_ROLE_RESELLER_EUR];
    
        foreach ($data as $customer_no => $d) {
    
            $user_id = username_exists($customer_no);
            $user_price_group = trim($d['PrislistaGruppCounter']);
            $role = isset($user_role[$user_price_group]) ? $user_role[$user_price_group] : TAW_ROLE_B2B;
    
            if (empty($user_id)) {
                $namear = explode(" ", $d['Namn']);
                $user_id = wp_insert_user(
                    array(
                        'user_login' => $customer_no,
                        'user_pass' => 'test1234',
                        'user_email' => $d['Email'],
                        'first_name' => $namear[0],
                        'last_name' => $namear[1] ?? "",
                        'display_name' => $d['Namn'],
                        'role' => $role,
                    )
                );
    
                //$user_id = wp_create_user($customer_no, 'test1234', $d['Email']);
            }
    
            $metas = array(
                'nickname' => $d['Namn'],
                'billing_address_1' => $d['Gatuadress'],
                'billing_address_2' => $d['Postadress'],
                'billing_postcode' => $d['Postnr'],
                'billing_city' => $d['Distrikt'],
                'billing_state' => $d['Land'],
                'billing_phone' => $d['Telefon'],
            );
    
            foreach ($metas as $key => $value) {
                update_user_meta($user_id, $key, $value);
            }
        }
    
        return 1;
    
    }
    
    
    function importProductCategory($spreadsheet, $heading, $end, $wpdb)
    {
        //check valid sheet
        $colum_check = ['Artnumber','lang','unique_code','Catogory Parent','Category Child'];
        if (!empty(array_diff($colum_check, $heading))) {
            return -1;
        }
    
        $end_data = false;
        $data = [];
        $common_attributes = [];
        for ($i = 2; $i < 10000; $i++) {
            if ($end_data) {
                break;
            }
            $art_no = '';
            $parent = '';
            $lang = '';
            $unique_code = [];
    
            for ($j = 1; $j <= $end; $j++) {
                $v = $spreadsheet->getActiveSheet()->getCellByColumnAndRow($j, $i)->getValue();
                $v = trim($v ?? "");
                if (empty($v)) {
                    if ($j == 1) {
                        $end_data = true;
                        break;
                    }
                    continue;
                }
                //check family
                $h = trim($heading[$j - 1]);
    
                if ($h == "Artnumber") {
                    $art_no = $v;
                } else if ($h == "unique_code") {
                    $uar=explode(">>",$v);                    
                    //if not uniquecode set, skip record
                    if(empty($uar)){
                        continue;
                    }
                    foreach($uar as $u){
                        $unique_code[]=sanitize_title($u);
                    }              
                } else if ($h == "lang") {
                    $lang = $v;
                }else if ($h == "Catogory Parent") {
                    $parent = $v;  
                    $data[$art_no]['parent']=$parent;
                    $data[$art_no]['lang']=$lang;
                    $data[$art_no]['unique_code']=implode(">>",$unique_code);                                             
                    $data[$art_no]['child']=[];
                    if(!isset($common_attributes[$parent] )){
                        $common_attributes[$parent] = ['lang'=>$lang,'unique_code'=>$unique_code[0],'child'=>[]];  
                    }                                                  
                } else if ($h == "Category Child") {
                    $a = $v;
                    $data[$art_no]['child']=$a;
                    if(count($unique_code)>1){
                        $common_attributes[$parent]['child'][$unique_code[1]] = $a;
                    }                    
                }
            }
        }
        
      
       
        $pq = "SELECT tt.term_id,tt.slug FROM `tsm_term_taxonomy` as ttt left JOIN tsm_terms as tt on tt.term_id=ttt.term_id  where ttt.taxonomy='product_cat'";    
        $product_categories=$wpdb->get_results($pq);
        $exist_cates = [];
        foreach ($product_categories as $c) {
            $exist_cates[$c->slug] = $c->term_id;
        }
           
        $cate_map_term_id = [];
        
        foreach ($common_attributes as $parent => $c) {
            //insert parent           
            $attr_slug = sanitize_title($parent);
            $lang=$c['lang'];
            $unique_code=$c['unique_code'] ?? $attr_slug;
            $slug=trim($unique_code);
            //add lang code to slug if not english (base) lang.
            if($lang!="en"){
                $slug.="-".$lang;
            }
            

            if (isset($exist_cates[$slug])) {
                $parent_id = $exist_cates[$slug];
            } else {
               
                $res = wp_insert_term($parent, "product_cat", ['description' => $parent, 'slug' => $slug]);
                $parent_id = $res['term_id'] ?? 0;
            }
    
            if (empty($parent_id)){                
                continue;
            }
                     
            if($lang!="en"){
                $main_lang_term_id=get_term_by('slug',$unique_code,'product_cat')->term_id;
                if(!empty($main_lang_term_id)){
                    $trid=getLanguageCateTridId($main_lang_term_id);                 
                    $this->linkWpmlLangCategory($parent_id,$trid,$lang);
                }
            }

            $cate_map_term_id[$parent] = $parent_id;
            $child=$c['child'] ?? [];
           
            //insert child
            foreach ($child as $attr_slug_c=>$t) {               
                $slug_c=trim($attr_slug_c);
                if($lang!="en"){
                    $slug_c.="-".$lang;
                }

                $d = ['description' => $t, 'slug' => $slug_c, 'parent' => $parent_id];                
    
                if (isset($exist_cates[$slug_c])) {
                    $id = $exist_cates[$slug_c];
                    wp_update_term($id, 'product_cat', $d);

                } else {                  
                    $res = wp_insert_term($t, "product_cat", $d);
                    $id = $res['term_id'] ?? 0;
                }

                if($lang!="en"){
                    $main_lang_term_id=get_term_by('slug',$attr_slug_c,'product_cat')->term_id;
                    if(!empty($main_lang_term_id)){
                        $trid=getLanguageCateTridId($main_lang_term_id);                 
                        $this->linkWpmlLangCategory($id,$trid,$lang);
                    }
                }
    
                $cate_map_term_id[$t] = [$id,$parent_id];    
            }
        }

        //check parent is child        
    
        //save category map to table
        $values = array();
        $place_holders = array();
    
        //remove old
        // $q = 'TRUNCATE TABLE taw_article_category;';
        // $wpdb->query($q);
       
        $query = "INSERT INTO `taw_article_category` (`art_no`, `term_id`, `parent_cate`,`lang`,`unique_code`) VALUES ";
        
        foreach ($data as $art_no => $v) {

            $cate=$v['parent'];
            if(!empty($v['child'])){
                $cate=$v['child'];
            }           
            $unique_code=$v['unique_code'];            

            if (isset($cate_map_term_id[$cate])&&!empty($unique_code)) {              
                $child_data=$cate_map_term_id[$cate];
                $child_data=is_array($child_data)?$child_data:array($child_data);
                array_push($values, $art_no,$child_data[0],$child_data[1] ?? 0,$v['lang'],$art_no."::".$v['lang']."::".$unique_code);
                $place_holders[] = "('%s','%d','%d','%s','%s')";
            }
        }

        $query .= implode(', ', $place_holders);
        $q = $wpdb->prepare("$query ", $values).' ON DUPLICATE KEY UPDATE `term_id`=VALUES(`term_id`), `parent_cate`=VALUES(`parent_cate`)';
  
        $wpdb->query($q);
   
        return 1;
    }

    

    function updateParentRelation($parent,$data){
        foreach($data as $key=>$d){
           // if($)
        }
    }

    function linkWpmlLangCategory($term_id,$trid,$cur_lang){
        $set_language_args = array(
            'element_id'    => $term_id,
            'element_type'  => 'tax_product_cat',
            'trid'   => $trid,
            'language_code'   => $cur_lang,
            'source_language_code' => 'en'
        );    
       do_action( 'wpml_set_element_language_details', $set_language_args );
    }
    
     function importProductAttributes($spreadsheet, $heading, $end, $wpdb){    
        //check valid sheet
        $colum_check = ['Artnumber'];
        if (!empty(array_diff($colum_check, $heading))) {
            return -1;
        }
    
        $end_data = false;
        $data = [];
        $common_attributes = [];
        for ($i = 2; $i < 10000; $i++) {
            if ($end_data) {
                break;
            }
            $art_no = '';
    
            for ($j = 1; $j <= $end; $j++) {
                $v = $spreadsheet->getActiveSheet()->getCellByColumnAndRow($j, $i)->getValue();
                $v = trim($v);
                if (empty($v)) {
                    if ($j == 1) {
                        $end_data = true;
                        break;
                    }
                    continue;
                }
                //check family
                $h = trim($heading[$j - 1]);
    
                if ($h == "Artnumber") {
                    $art_no = $v;
                } else {
    
                    $ar = explode("|", $v);
                    foreach ($ar as $a) {
                        $a = trim($a);
                        $data[$art_no][$h][$a] = $a;
                        $common_attributes[$h][$a] = $a;
                    }
    
                }
            }
        }
    
        $attrs = array();
        $attributes = wc_get_attribute_taxonomies();
        foreach ($attributes as $key => $value) {
            $attrs[$value->attribute_name] = $value->attribute_id;
        }
    
        $att_map_ids = ['attr' => [], 'term' => []];
        foreach ($common_attributes as $a => $terms) {
    
            $attr_slug = sanitize_title($a);
            if (strlen($attr_slug) > 28) {
                $attr_slug = substr($attr_slug, 0, 28);
            }
    
            $parent = $attrs[$attr_slug] ?? 0;
            if (empty($parent)) {
                $args = array(
                    'slug' => $attr_slug,
                    'name' => __($a, 'woocommerce'),
                    'type' => 'select',
                    'orderby' => 'menu_order',
                    'has_archives' => false,
                );
                $parent = wc_create_attribute($args);
                $attrs[$attr_slug] = $parent;
            }
            if (!empty($parent)) {
                $att_map_ids["attr"][$attr_slug] = $parent;
            }
    
            foreach ($terms as $t) {
                $t = rtrim(str_replace('%', '', $t));
                $attr_t_slug = sanitize_title($t);
                $term_exists = term_exists($t, "pa_" . $attr_slug);
                if ($term_exists !== 0 && $term_exists !== null) {
                    // Term already exists, update its properties if needed
                    $term_id = $term_exists['term_id'];
                    wp_update_term($term_id, "pa_" . $attr_slug, ['description' => $t, 'slug' => $attr_t_slug]);
                } else {
                    // Term doesn't exist, insert it
                    $d = ['description' => $t, 'slug' => $attr_t_slug];
                    $res = wp_insert_term($t, "pa_" . $attr_slug, $d);
            
                    if (is_object($res)) {
                        $term_id = $res->error_data['term_exists'];
                    } else {
                        $term_id = $res['term_id'] ?? 0;
                    }
               
                    if (!empty($term_id)) {
                        $att_map_ids['term'][$attr_t_slug] = $term_id;
                    }
                }
            }
        }
    
       
        //save category map to table
        $values = array();
        $place_holders = array();
    
        //remove old
        $q = 'TRUNCATE TABLE taw_article_attributes;';
        $wpdb->query($q);
        $query = "INSERT INTO `taw_article_attributes` (`art_no`, `attr_id`,`term_ids`) VALUES ";
    
    
        foreach ($data as $art_no => $attributes) {
    
            foreach ($attributes as $attr => $terms) {
    
                $attr_sl = sanitize_title($attr);
                $attr_id = 0;
                if (isset($att_map_ids['attr'][$attr_sl])) {
                    $attr_id = "pa_" . $attr_sl;
                } else {
                    continue;
                }
    
                $t_ar = [];
                foreach ($terms as $t) {
                    $term_sl = sanitize_title($t);
    
                    // if (isset($att_map_ids['term'][$term_sl])) {
                        $t_ar[] = $term_sl; //$att_map_ids['term'][$term_sl];
                    // }
                }
    
                array_push($values, $art_no, $attr_id, implode(",", $t_ar));
                $place_holders[] = "('%s','%s','%s')";
            }
        }
    
        $query .= implode(', ', $place_holders);
        $q = $wpdb->prepare("$query ", $values);
               

        $wpdb->query($q);
    
        return 1;
    }
    
    
    
    function importTitleDesc($spreadsheet, $heading, $end, $wpdb)
    {
    
        //check valid sheet
        $colum_check = ['Identifierare','Title','Description','ShortDescription','Lang'];
        if (!empty(array_diff($colum_check, $heading))) {
            return -1;
        }
    
        $end_data = false;
        $data = [];
    
        for ($i = 2; $i < 10000; $i++) {
            if ($end_data) {
                break;
            }
            $art_no = '';
            $title='';
            $Description='';
            $lang='';
            $ShortDescription='';
            for ($j = 1; $j <= $end; $j++) {
                $v = $spreadsheet->getActiveSheet()->getCellByColumnAndRow($j, $i)->getValue();
                $v = trim($v);
                if (empty($v)) {
                    if ($j == 1) {
                        $end_data = true;
                        break;
                    }
                    continue;
                }
                //check family
                $h = $heading[$j - 1];
    
                if ($h == "Identifierare") {
                    $art_no = $v;
                }else if($h=="Title"){
                    $title=$v;
                } else if($h=="Description"){
                    $Description=$v;
                }  else if($h=="Lang"){
                    $lang=$v;
                } else if($h=="ShortDescription"){
                    $ShortDescription=$v;
                }
            }
            $data[$art_no]=['title'=>$title,'desc'=>$Description,'lang'=>$lang, 'shortdesc'=>$ShortDescription];
        }
    
        //save glass types
        $values = array();
        $place_holders = array();
    
        //remove old
        // $currlan = getSiteCurrentLang();
        // $q = $wpdb->prepare(
        //     "DELETE FROM `taw_article_title`
        //     WHERE `lang` = %s",
        //     $currlan
        // );
        // $wpdb->query($q);
        // $q = 'TRUNCATE TABLE taw_article_title;';
        // $wpdb->query($q);
        $query = "INSERT INTO `taw_article_title` (`art_no`, `title`,`desc`,`lang`,`uuid`,`shortdesc`) VALUES ";       
    
        foreach ($data as $art_no => $v) {  
            if(!empty($art_no)){
                array_push($values, $art_no, $v['title'], $v['desc'], $v['lang'],($v['lang']."::".$art_no),$v['shortdesc']);
                $place_holders[] = "('%s','%s','%s','%s','%s','%s')";
            }
                    
        }
       
        $query .= implode(', ', $place_holders);
        $q = $wpdb->prepare("$query ", $values). "  ON DUPLICATE KEY UPDATE `title`=VALUES(`title`), `desc`=VALUES(`desc`), `shortdesc`=VALUES(`shortdesc`)"; 
        
//    print_r($q);  
//    exit;
        $wpdb->query($q);
        //print_r($wpdb->query($q));  
 
        return 1;
    }
    function importPrice($spreadsheet, $heading, $end, $wpdb)
    {
       
        //check valid sheet
        $colum_check = ['article_no', 'price_b2b', 'price_reseller_sek', 'price_reseller_eur'];
        if (!empty(array_diff($colum_check, $heading))) {
            return -1;
        }
    
        $end_data = false;
        $data = [];
    
        for ($i = 2; $i < 10000; $i++) {
            if ($end_data) {
                break;
            }
            $art_no = '';
            for ($j = 1; $j <= $end; $j++) {
                $v = $spreadsheet->getActiveSheet()->getCellByColumnAndRow($j, $i)->getValue();
                $v = trim($v);
                if (empty($v)) {
                    if ($j == 1) {
                        $end_data = true;
                        break;
                    }
                    $row[] = $v;
                    continue;
                }
    
                //check family
                $h = $heading[$j - 1];
    
                if ($h == "article_no") {
                    $art_no = $v;
                } else if ($h == "price_b2b") {
                    $data[$art_no][$h] = intval($v);
                } else if ($h == "price_reseller_sek") {
                    $data[$art_no][$h] = intval($v);
                } else if ($h == "price_reseller_eur") {
                    $data[$art_no][$h] = intval($v);
                }
            }
        }
   
    
        $values = array();
        $place_holders = array();
    
    
        //save glass types
        $values = array();
        $place_holders = array();
        $q = 'TRUNCATE TABLE taw_article_price;';
        $wpdb->query($q);
        $query = "INSERT INTO `taw_article_price` (`art_no`, `price_b2b`,`price_reseller_sek`,`price_reseller_eur`) VALUES ";
    
        foreach ($data as $art_no => $price) {
            array_push($values, $art_no, $price['price_b2b'] ?? 0, $price['price_reseller_sek'] ?? 0, $price['price_reseller_eur'] ?? 0);
            $place_holders[] = "('%s','%s','%s','%s')";
        }
    
        $query .= implode(', ', $place_holders);
        $q = $wpdb->prepare("$query ", $values) . "  ON DUPLICATE KEY UPDATE `price_b2b`=VALUES(`price_b2b`), `price_reseller_sek`=VALUES(`price_reseller_sek`), `price_reseller_eur`=VALUES(`price_reseller_eur`)";
  
        $wpdb->query($q);
  
        return 1;
    }

    function importProductAccessories($spreadsheet, $heading, $end, $wpdb)
    {
    
        //check valid sheet
        $colum_check = ['AccessoriesArtnumber', 'ParentArtnumber', 'Number of plates'];
        if (!empty(array_diff($colum_check, $heading))) {
            return -1;
        }
    
        $end_data = false;
        $data = [];    
        for ($i = 2; $i < 10000; $i++) {
            if ($end_data) {
                break;
            }
            $art_no = '';
            for ($j = 1; $j <= $end; $j++) {
                $v = $spreadsheet->getActiveSheet()->getCellByColumnAndRow($j, $i)->getValue();
                $v = trim($v);
               
                if (empty($v)) {
                    if ($j == 1) {
                        $end_data = true;
                        break;
                    }
                    $row[] = $v;
                    continue;
                }

                //check family
                $h = $heading[$j - 1];
    
                if ($h == "AccessoriesArtnumber") {
                    $art_no = $v;
                } else if ($h == "ParentArtnumber") {
                    $data[$art_no][] = $v;
                } else if ($h == "Number of plates") {
                    $data[$art_no][] = intval($v);
                }
            }
        }

    
        $values = array();
        $place_holders = array();
    
        //remove old
        $q = 'TRUNCATE TABLE taw_product_accessories;';
        $wpdb->query($q);
        //save glass types
        $values = array();
        $place_holders = array();
        $query = "INSERT INTO `taw_product_accessories` (`parent_article`, `acs_article`,`no_plates`) VALUES ";
    


        foreach ($data as $art_no => $v) {
            //print_r($data);
            $p_art_nos=explode("|",$v[0]);         

            foreach($p_art_nos as $p){
                if(empty($p)) continue;
                array_push($values, trim($p), $art_no, $v[1]);
                $place_holders[] = "('%s','%s','%d')";
            }            
        }
       //print_r($values);
  
        $query .= implode(', ', $place_holders);
        $q = $wpdb->prepare("$query ", $values);
    
        $wpdb->query($q);
    
        return 1;
    }
}