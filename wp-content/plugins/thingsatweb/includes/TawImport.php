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
        }else if ($import_type == "Spare Parts Update") {
            $res = $this->importProductSpareparts($spreadsheet, $heading, $end, $wpdb);
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
        $colum_check = ['Artnumber', 'Lang', 'Diagram','Diagram2','Diagram3'];
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

       
        $values = array();
        $place_holders = array();
       
        $query = "INSERT INTO `taw_diagram` (`art_no`, `lang`,`diagram`,`diagram2`,`diagram3`,`unique_code`,diagram_id,diagram2_id,diagram3_id) VALUES ";
        foreach ($data as $art_no => $v) {
            // print_r($data);
            // exit;
            $lang=$v['Lang'] ?? "";
            $art_no=$v['Artnumber'] ?? "";
            if(empty($lang)){
                continue;
            }

            $diagramname = $v['Diagram'];
            $diagram_id = checkMediaFile($wpdb, $diagramname);

            $diagramname2 = $v['Diagram2'];
            $diagram2_id = checkMediaFile($wpdb, $diagramname2);

            $diagramname3= $v['Diagram3'];
            $diagram3_id = checkMediaFile($wpdb, $diagramname3);
        //   print_r($diagram_id);
        //   exit;
            // if (empty($diagram_id)) {
            //     echo "Feature image : ".$diagram_id . " is not exist in media lib <br/>";
            //     continue;
            // }
            // if (empty($diagram2_id)) {
            //     echo "Feature image2 : ".$diagram2_id . " is not exist in media lib <br/>";
            //     continue;
            // }
            // if (empty($diagram3_id)) {
            //     echo "Feature image3 : ".$diagram3_id . " is not exist in media lib <br/>";
            //     continue;
            // }


            array_push($values, $art_no, $v['Lang'], $diagramname, $diagramname2, $diagramname3, ($v['Lang']."::".$art_no), $diagram_id, $diagram2_id, $diagram3_id);
            $place_holders[] = "('%s','%s','%s','%s','%s','%s','%s','%s','%s')";
        }

       
        $query .= implode(', ', $place_holders);
        $q = $wpdb->prepare("$query ", $values). "  ON DUPLICATE KEY UPDATE `diagram`=VALUES(`diagram`), `diagram2`=VALUES(`diagram2`), `diagram3`=VALUES(`diagram3`),
        `diagram_id`=VALUES(`diagram_id`), `diagram2_id`=VALUES(`diagram2_id`), `diagram3_id`=VALUES(`diagram3_id`)"; 
        
    //    print_r($q);  
    //    exit;
        $wpdb->query($q);
        //print_r($wpdb->query($q));  
 
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
        $query = "INSERT INTO `taw_customer_unique_price` (`art_no`, `price`,`currency`,`customer_no`) VALUES ";
        foreach ($data as $art_no => $v) {
            array_push($values, $v['Artikelnr'], $v['Price'], $v['Currency'], $v['Customer']);
            $place_holders[] = "('%s','%s','%s','%s')";
        }
    
        $query .= implode(', ', $place_holders);
        $q = $wpdb->prepare("$query ", $values). "  ON DUPLICATE KEY UPDATE `price`=VALUES(`price`)";
    //    print_r( $q );
    //    exit;
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
        $colum_check = ['Artnumber','lang','Catogory Parent','Category Child'];
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
            $lang = '';
            $cat_parent = '';
            $cat_child = '';
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
    
                if ($h == "Artnumber") {
                    $art_no = $v;
                } else if ($h == "lang") {
                    $lang = $v;
                } else if ($h == "Catogory Parent") {
                    $cat_parent = $v;
                } else if ($h == "Category Child") {
                    $cat_child = $v;
                }
            }
            $data[$art_no]=['lang'=>$lang,'cat_parent'=>$cat_parent, 'cat_child'=>$cat_child];
            
        }
       
        //save glass types
        $values = array();
        $place_holders = array();
        $currlan = getSiteCurrentLang();
        //remove old
        $del_cat = "DELETE FROM taw_article_category WHERE lang = '$currlan';";
        $wpdb->query($del_cat);

        $query = "INSERT INTO `taw_article_category` (`art_no`, `term_id`,`lang`,`parent_cate`,`unique_code`) VALUES ";

        foreach ($data as $art_no => $v) {

        
            // Split cat_parent into an array of categories
            $catParentArray = explode('|', $v['cat_parent']);
            // print_r($catParentArray);
            // exit;
        
            // Split cat_child into an array of categories
            $catChildArray = explode('|', $v['cat_child']);
        
            // Initialize arrays to store term_id and parent_cate values
            $termIds = array();
            $parentCates = array();
        
            // Iterate through cat_parent
            foreach ($catParentArray as $catParent) {
                $catParent = trim($catParent);
       
                // Get the term_id for the current category
                $termParentId = $catParent ? $wpdb->get_var($wpdb->prepare("SELECT term_id FROM tsm_terms WHERE name = %s", str_replace('&', '&amp;', $catParent))) : '0';
              
                if (!empty($termParentId)) {
                    $parentCates[] = $termParentId;
                }
            }
            
            $parentCates = !empty($parentCates) ? implode(',', $parentCates) : '0';
           
            // Iterate through cat_child
            foreach ($catChildArray as $catChild) {
                $catChild = trim($catChild);
        
                // Get the term_id for the current category
                $termChildId = $catChild ? $wpdb->get_var($wpdb->prepare("SELECT term_id FROM tsm_terms WHERE name = %s", str_replace('&', '&amp;', $catChild))) : '0';
        
                if (!empty($termChildId)) {
                    $termIds[] = $termChildId;
                }
            }
            $termIds = !empty($termIds) ? implode(',', $termIds) : '0';

            // $unique_code = $art_no . '::' . $v['lang'] . '::' . implode('|', $catParentArray) . '>>' . implode(' | ', $catChildArray);
            if (!empty($termIds) || !empty($parentCates)){
            $unique_code = $v['lang'] . '::' . $art_no;
            array_push($values, $art_no, $termIds, $v['lang'], $parentCates, $unique_code);
            $place_holders[] = "('%s','%s','%s','%s','%s')";
            }
        }
        
        $query .= implode(', ', $place_holders);
        $q = $wpdb->prepare("$query ", $values);; 
        //print_r($q);

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
        $colum_check = ['Articlenumber'];
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
    
                if ($h == "Articlenumber") {
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
                //$t = rtrim(str_replace('%', '', $t));
                $attr_t_slug = sanitize_title($t);

                $term_exists = term_exists($t, "pa_" . $attr_slug);
                
                if ($term_exists === 0 || $term_exists === null || empty($term_exists)) {
                   
              
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
                }  }
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
            // print_r( $data[$art_no]);
            // exit;
        }
    
        //save glass types
        $values = array();
        $place_holders = array();
    
        //remove old
        // $currlan = getSiteCurrentLang();
        // $currlanLower = strtolower($currlan);
        // $deletetitle = "DELETE FROM `taw_article_title` LOWER(`taw_article_title`.`lang`) = '$currlanLower'";
        // $wpdb->get_results($deletetitle);

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
    // Check valid sheet
    $column_check = ['AccessoriesArtnumber', 'ParentArtnumber', 'Number of plates'];
    if (!empty(array_diff($column_check, $heading))) {
        return -1;
    }

    // Remove old data
    $wpdb->query('TRUNCATE TABLE taw_product_accessories;');

    for ($i = 2; $i < 10000; $i++) {
        $art_no = '';
        $p_art_nos = [];
        $no_plates = 0;

        for ($j = 1; $j <= $end; $j++) {
            $v = $spreadsheet->getActiveSheet()->getCellByColumnAndRow($j, $i)->getValue();
            $v = trim($v);

            if (empty($v)) {
                if ($j == 1) {
                    break;
                }
                continue;
            }

            $h = $heading[$j - 1];

            if ($h == 'AccessoriesArtnumber') {
                $art_no = $v;
            } elseif ($h == 'ParentArtnumber') {
                $p_art_nos = explode('|', $v);
            } elseif ($h == 'Number of plates') {
                $no_plates = intval($v);
            }
        }

        foreach ($p_art_nos as $p) {
            $id = $wpdb->get_var($wpdb->prepare("SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1", $p));

            if (!empty($id)) {
                $wpdb->insert(
                    'taw_product_accessories',
                    array(
                        'parent_article' => $p,
                        'acs_article' => $art_no,
                        'no_plates' => $no_plates
                    ),
                    array('%s', '%s', '%d')
                );
            }
        }
    }
    return 1;
    }
    function importProductSpareparts($spreadsheet, $heading, $end, $wpdb)
    {
    // Check valid sheet
    $column_check = ['SparepartsArtnumber', 'ParentArtnumber', 'Minimum Qty'];
    if (!empty(array_diff($column_check, $heading))) {
        return -1;
    }

    // Remove old data
    $wpdb->query('TRUNCATE TABLE taw_product_spareparts;');

    for ($i = 2; $i < 10000; $i++) {
        $art_no = '';
        $p_art_nos = [];
        $min_qty = 0;

        for ($j = 1; $j <= $end; $j++) {
            $v = $spreadsheet->getActiveSheet()->getCellByColumnAndRow($j, $i)->getValue();
            $v = trim($v);

            if (empty($v)) {
                if ($j == 1) {
                    break;
                }
                continue;
            }

            $h = $heading[$j - 1];

            if ($h == 'SparepartsArtnumber') {
                $art_no = $v;
            } elseif ($h == 'ParentArtnumber') {
                $p_art_nos = explode('|', $v);
            } elseif ($h == 'Minimum Qty') {
                $min_qty = intval($v);
            }
        }

        foreach ($p_art_nos as $p) {
            $id = $wpdb->get_var($wpdb->prepare("SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1", $p));

            if (!empty($id)) {
                $wpdb->insert(
                    'taw_product_spareparts',
                    array(
                        'parent_article' => $p,
                        'spare_article' => $art_no,
                        'min_qty' => $min_qty
                    ),
                    array('%s', '%s', '%d')
                );
            }
        }
    }
    return 1;
    }
}