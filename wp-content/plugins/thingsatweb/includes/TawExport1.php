<?php

class TawExport {
    public function __construct() {
		add_action( 'wp_ajax_export_product', array( $this, 'exportProduct' ) );       
	}
        
    function exportProduct(){
        
        $data = array_merge((array) $_GET, (array) $_POST);
        $export_type = isset($data['export_type']) ? $data['export_type'] : "";

        check_ajax_referer('taw_security', 'taw_nonce');

        include_once(THINGSATWEB_DIR . '/vendor/autoload.php');
        $spreadsheet = new PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        global $wpdb; 

        if ($export_type == "Price") {
            $this->exportProductPrice($sheet,$wpdb);
        }else if ($export_type == "Category") {            
            $this->exportProductCategory($sheet,$wpdb);
        }else  if ($export_type == "Attributes") {
            $this->exportProductAttributes($sheet,$wpdb);
        }else if ($export_type == "Customers") {
            $this->exportCustomer($sheet,$wpdb);
        } else if ($export_type == "Customers Unique Price") {
            $this->exportCustomerUniquePrice($sheet,$wpdb);
        }else if ($export_type == "Pictures") {
            $this->exportProductPicture($sheet,$wpdb);
        }else if ($export_type == "Title and Description") {
            $this->exportProductTitleDesc($sheet,$wpdb);
        }else if ($export_type == "Accessories") {
            $this->exportProductAccessories($sheet,$wpdb);
        }else if ($export_type == "Diagram") {
            $this->exportProductdiagram($sheet,$wpdb);
        }
         //set style
        $sheet->getStyle('A1:Z1')->getFont()->setBold(true);
        $this->downloadXlFile($export_type,$spreadsheet);    
    }

    private function exportProductCategory($sheet,$wpdb){         
    
        $sheet->setCellValue('A1', 'Artnumber');       
        $sheet->setCellValue('B1', 'lang');       
        $sheet->setCellValue('C1', 'unique_code');       
        $sheet->setCellValue('D1', 'Catogory Parent');
        $sheet->setCellValue('E1', 'Category Child');
        
        $lang=getSiteCurrentLang();

        $q="SELECT cat.art_no,tms.`name`, (select name from tsm_terms WHERE term_id=cat.parent_cate) as parent_name,cat.lang,cat.unique_code FROM `taw_article_category` as cat left join tsm_terms as tms on tms.term_id=cat.term_id where cat.lang='$lang'";
        
        
        $results=$wpdb->get_results($q);

        $row=2;
        $lookupData=[];
        $imgData=[];
        foreach($results as $res){
               
            $sheet->setCellValue('A'.$row,  $res->art_no); 
            $sheet->setCellValue('B'.$row,  $res->lang); 
            $unique_code=explode("::",$res->unique_code);
            $code="";
            if(count($unique_code)==3){
                $code=$unique_code[2];
            }
            $sheet->setCellValue('C'.$row,  $code);            
            $sheet->setCellValue('D'.$row,  wp_specialchars_decode($res->parent_name));
            $sheet->setCellValue('E'.$row,  wp_specialchars_decode($res->name));
                    
            $row++;
        }
    
    }

    private function exportProductPrice($sheet,$wpdb){         
    
        $sheet->setCellValue('A1', 'article_no');
        $sheet->setCellValue('B1', 'price_b2b');
        $sheet->setCellValue('C1', 'price_reseller_sek');  
        $sheet->setCellValue('D1', 'price_reseller_eur');
        
        $q="SELECT art_no,price_b2b,price_reseller_eur,price_reseller_sek FROM `taw_article_price`";
        $results=$wpdb->get_results($q);

        $row=2;
        foreach($results as $res){           

            $sheet->setCellValue('A'.$row,  $res->art_no);           
           
            $sheet->setCellValue('B'.$row,  $res->price_b2b);
            $sheet->setCellValue('C'.$row,  $res->price_reseller_sek);
            $sheet->setCellValue('D'.$row,  $res->price_reseller_eur);
       
            $row++;
        }
    
    }
    private function exportProductAccessories($sheet, $wpdb) {         
    
        $sheet->setCellValue('A1', 'AccessoriesArtnumber');
        $sheet->setCellValue('B1', 'ParentArtnumber');
        $sheet->setCellValue('C1', 'Number of plates');  
           
        $q = "SELECT parent_article, acs_article, no_plates FROM `taw_product_accessories`";
        $results = $wpdb->get_results($q);
    
        $consolidatedData = array();
    
        foreach ($results as $res) {
            $accessoriesArtnumber = $res->acs_article;
            $parentArtnumber = $res->parent_article;
            $noPlates = $res->no_plates;
    
            // Create a unique combination key
            $combinationKey = $accessoriesArtnumber . '*' . $noPlates;
            // print_r($combinationKey);
            // exit;
            if (!isset($consolidatedData[$combinationKey])) {
                $consolidatedData[$combinationKey] = array(
                    'parent_artnumbers' => array($parentArtnumber),
                    'no_plates' => $noPlates
                );
            } else {
                $consolidatedData[$combinationKey]['parent_artnumbers'][] = $parentArtnumber;
            }
        }
    
        $row = 2;
    
        foreach ($consolidatedData as $combinationKey => $data) {
            $accessoriesArtnumber = explode('*', $combinationKey)[0];
            $parentArtnumbers = implode('|', $data['parent_artnumbers']);
            $noPlates = $data['no_plates'];
    
            $sheet->setCellValue('A' . $row, $accessoriesArtnumber);
            $sheet->setCellValue('B' . $row, $parentArtnumbers);
            $sheet->setCellValue('C' . $row, $noPlates);
    
            $row++;
        }
    }

    private function exportProductAttributes($sheet,$wpdb){  
        
        //get heading
        $heading=$wpdb->get_results("SELECT DISTINCT attr_id FROM `taw_article_attributes`");
        $sheet->setCellValue('A1', 'Artnumber');
        $hMap=['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ'];
        $kMap;
        foreach($heading as $k=>$h){
            
            $hh=trim($h->attr_id);
       
            if(!empty($hh)){
                $kMap[$hh]=$hMap[$k+1];
                $hh=str_replace("pa_","",$h->attr_id);
                $sheet->setCellValue($hMap[$k+1].'1', $hh);
            }
        }

        $q="SELECT art_no,GROUP_CONCAT(`attr_id`,':',`term_ids`,'::') as v FROM `taw_article_attributes`  GROUP by `art_no`";
        $results=$wpdb->get_results($q);

        $row = 2;
        foreach($results as $res){
            $sheet->setCellValue('A'.$row,  $res->art_no);
    
            $attr_ar = explode("::", $res->v);
    
            foreach($attr_ar as $a){
                $av_ar = explode(":", $a);
    
                if(count($av_ar) == 2){
                    $key = trim(str_replace(",", "", $av_ar[0]));
                    $val = trim(str_replace(",", " | ", $av_ar[1]));
                    $val = urldecode($val); // Decode URL encoded characters
    
                    // Check if the attribute is "extension" and append a percentage sign with a space
                    if ($key === 'extension') {
                        // No need to append the percentage sign here
                        $val = rtrim($val); // Remove any trailing spaces
                    }
    
                    if (isset($kMap[$key])) {
                        $cellReference = $kMap[$key] . $row;
                        $sheet->setCellValue($cellReference, $val);
    
                        // If the attribute is "extension", explicitly format the cell as percentage
                        if ($key === 'extension') {
                            $sheet->getStyle($cellReference)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE);
                        }
                    }
                }             
            } 
    
            $row++;
        }
        
    }
 
    private function exportCustomer($sheet,$wpdb){         
    
        $sheet->setCellValue('A1', 'Kundnr');
        $sheet->setCellValue('B1', 'Namn');        
        $sheet->setCellValue('C1', 'Email');
        $sheet->setCellValue('D1', 'Gatuadress');
        $sheet->setCellValue('E1', 'Postnr');
        $sheet->setCellValue('F1', 'Postadress');
        $sheet->setCellValue('G1', 'Land');
        $sheet->setCellValue('H1', 'Telefon');        
        $sheet->setCellValue('I1', 'Distrikt');
        $sheet->setCellValue('J1', 'PrislistaGruppCounter');

        $q="SELECT user_login,user_email,display_name,
        (select meta_value from tsm_usermeta where meta_key='tsm_capabilities' and user_id=ID) as capability,
        (select meta_value from tsm_usermeta where meta_key='billing_address_1' and user_id=ID) as address_1,
        (select meta_value from tsm_usermeta where meta_key='billing_address_2' and user_id=ID) as address_2,
        (select meta_value from tsm_usermeta where meta_key='billing_postcode' and user_id=ID) as postcode,
        (select meta_value from tsm_usermeta where meta_key='billing_city' and user_id=ID) as city,
        (select meta_value from tsm_usermeta where meta_key='billing_state' and user_id=ID) as state,
        (select meta_value from tsm_usermeta where meta_key='billing_phone' and user_id=ID) as phone
         FROM `tsm_users`";

        $results=$wpdb->get_results($q);
        $row=2;    
        $user_role = [TAW_ROLE_B2B=>'1', TAW_ROLE_RESELLER_SEK=> '2', TAW_ROLE_RESELLER_EUR=>'3']; 
        foreach($results as $res){
            
            $cap=unserialize($res->capability);
            if(isset($cap['administrator'])){
                continue;
            }
            
            $capability="1";
            foreach($cap as $c=>$v){
                if(isset($user_role[$c])){
                    $capability=$user_role[$c];
                }
            }

            $sheet->setCellValue('A'.$row, $res->user_login);
            $sheet->setCellValue('B'.$row, $res->display_name);
            $sheet->setCellValue('C'.$row, $res->user_email);
            $sheet->setCellValue('D'.$row, $res->address_1);   
            $sheet->setCellValue('E'.$row, $res->postcode);   
            $sheet->setCellValue('F'.$row, $res->address_2);   
            $sheet->setCellValue('G'.$row, $res->state);
            $sheet->setCellValue('H'.$row, $res->phone);
            $sheet->setCellValue('I'.$row, $res->city);
            $sheet->setCellValue('J'.$row, $capability);
            
            $row++;
        }
    
    }

    private function exportCustomerUniquePrice($sheet,$wpdb){    
        
        $q="SELECT customer_no,price,currency,art_no FROM `taw_customer_unique_price`";
        $results=$wpdb->get_results($q);
    
        $sheet->setCellValue('A1', 'Customer');
        $sheet->setCellValue('B1', 'Artikelnr');
        $sheet->setCellValue('C1', 'Price');        
        $sheet->setCellValue('D1', 'Currency');
       
        $row=2;
        foreach($results as $res){
        
            //kickplate options
            if (isset($res->art_data)) {
            $art_nos=json_decode($res->art_data,true) ?? [];
    
            $sheet->setCellValue('A'.$row,$res->customer_no);
            $sheet->setCellValue('B'.$row,$res->art_no);
            $sheet->setCellValue('C'.$row,$res->price);            
            $sheet->setCellValue('D'.$row, $res->currency);
    
            $row++;
        }
    }
    }
    
    private function exportProductPicture($sheet,$wpdb){     
        $lang=getSiteCurrentLang();
        $q="SELECT pic_name,colour,alt_text,art_no,lang,gallery_pics FROM `taw_article_picture` where lang='$lang';";
        $results=$wpdb->get_results($q);
    
        $sheet->setCellValue('A1', 'ART number');
        $sheet->setCellValue('B1', 'Feature Image');
        $sheet->setCellValue('C1', 'Gallery Images');
        $sheet->setCellValue('D1', 'Colour');
        $sheet->setCellValue('E1', 'Alt text');
        $sheet->setCellValue('F1', 'Lang'); 

        $row=2;
        $family_data=[];
        foreach($results as $res){           
            $sheet->setCellValue('A'.$row,$res->art_no);
            $sheet->setCellValue('B'.$row,$res->pic_name);

            $gal_ar=explode(",",$res->gallery_pics);
            $gal_pics=[];
            foreach( $gal_ar as $a){
                $attach_id=trim($a);
                if(!empty($attach_id)){
                    $pic_name=$wpdb->get_var("SELECT meta_value FROM tsm_postmeta WHERE post_id=$a and meta_key='_wp_attached_file'");
                    if(!empty($pic_name)){
                        //split pic name
                        $d=explode("/",$pic_name);
                        $gal_pics[]=$d[count($d)-1];
                    }       
                }
                         
            }

            $sheet->setCellValue('C'.$row,implode("|",$gal_pics));
            $sheet->setCellValue('D'.$row, $res->colour);          
            $sheet->setCellValue('E'.$row, $res->alt_text);     
            $sheet->setCellValue('F'.$row, $res->lang);     
            $row++;        
        }
    
    } 

    private function exportProductdiagram($sheet,$wpdb){         
    
        $sheet->setCellValue('A1', 'Article_no');
        $sheet->setCellValue('B1', 'Diagram');
        
        $q = "SELECT * FROM `tsm_postmeta` WHERE meta_key='taw_prod_opt' ";

        $results=$wpdb->get_results($q);

        $row=2;
        foreach($results as $res){   
            $sku_number = get_post_meta($res->post_id, '_sku', true);
            $meta_value = maybe_unserialize($res->meta_value); 
            if (isset($meta_value['article_price']['product_diagram_file']['url']) && !empty($meta_value['article_price']['product_diagram_file']['url'])) { 
            $diagram_file_url = $meta_value['article_price']['product_diagram_file']['url']; 
            $diagram_filename = pathinfo($diagram_file_url, PATHINFO_BASENAME);
                 
            $sheet->setCellValue('A'.$row,  $sku_number);           
            $sheet->setCellValue('B'.$row,  $diagram_filename);
       
            $row++;
            }
        }
    
    }

    private function exportProductTitleDesc($sheet,$wpdb){        
    
        $sheet->setCellValue('A1', 'Identifierare');
        $sheet->setCellValue('B1', 'Title');
        $sheet->setCellValue('C1', 'Description');
        $sheet->setCellValue('D1', 'ShortDescription');
        $sheet->setCellValue('E1', 'Lang');

        $lang=getSiteCurrentLang();
        $q="SELECT * from taw_article_title where lang='$lang'";
        $res=$wpdb->get_results($q);
        $row=2;
        foreach($res as $r){
            $sheet->setCellValue('A'.$row,  $r->art_no);
            $sheet->setCellValue('B'.$row,  $r->title);
            $sheet->setCellValue('C'.$row,  $r->desc);
            $sheet->setCellValue('D'.$row,  $r->shortdesc);
            $sheet->setCellValue('E'.$row,  $r->lang);
          
            $row++;
        }    
    }   


    private function downloadXlFile($type,$spreadsheet){
        $filename = $type."_".time().".xlsx";

        try {
            $writer = new PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $writer->save($filename);
            $content = file_get_contents($filename);
        } catch(Exception $e) {
            exit($e->getMessage());
        }

        header("Content-Disposition: attachment; filename=".$filename);

        unlink($filename);
        exit($content);
    }

}