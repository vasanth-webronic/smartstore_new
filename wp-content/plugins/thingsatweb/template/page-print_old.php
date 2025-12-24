<!-- <link rel='stylesheet' id='tailwind-css-css' href='http://smartstoring.test/wp-content/plugins/thingsatweb//css/tailwind.css?v=7.618&#038;ver=6.2' media='all' /> -->
<?php
defined( 'ABSPATH' ) || exit;
 $product_language = apply_filters('wpml_post_language_details', null, $product->get_id());
 $lang = isset($product_language['language_code']) ? $product_language['language_code'] : '';
$todayDate = date('Y-m-d');
global $wpdb;
if(!function_exists('wp_get_current_user')) {
    include(ABSPATH . "wp-includes/pluggable.php"); 
}
$current_user = wp_get_current_user();
$user_roles = $current_user->roles;
//$user_id = $current_user->ID;
$reseller_logo = get_user_meta($user_id, 'account_company_logo', true);
$reseller_website = get_user_meta($user_id, 'account_company_website', true);
$reseller_color = get_user_meta($user_id, 'account_company_theme', true);
$reseller_name = get_user_meta($user_id, 'account_company_name', true);


// if (in_array('custom_uam_reseller_eur', $user_roles) || in_array('custom_uam_reseller_sek', $user_roles)) {
//     echo 'reseller';
// } elseif(in_array('custom_uam_b2b', $user_roles)) {
//     echo 'b2b';
// }
?>
<head>
    <!-- Other head elements -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap">
</head>
<div style="display: flex; width: 745px; margin-top:-20px; margin-left:-20px; padding-top:62px;  padding-bottom:0px; background-color: #E6E7E9; ">
    <div style="width:720px ;float:left ;  margin-top: -42px; ">
        <?php
        if ((in_array('custom_uam_reseller_eur', $user_roles) || in_array('custom_uam_reseller_sek', $user_roles)) &&  (!empty($reseller_logo) && isset($reseller_logo))){ ?>
            <span style="font-size: 26px;  height:45px; font-family:sans-serif; color: #CC071D; font-weight:Regular; padding-top: -100px;   margin-left: 90px;">
            <?php
            $image_url = $reseller_logo;
            $type = pathinfo($image_url, PATHINFO_EXTENSION);
            $image_data = file_get_contents($image_url);
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($image_data);
            echo '<img class="filter-item-image mx-auto my-0 md:my-4 !h-6 sm:!h-9 md:!h-11 w-auto"  src="' . $base64 . '" alt="Manual" style="justify-content: center; align-items: center;   margin-left:0px; margin-top:-90px; object-fit: cover; width: auto; height: 45px; ">';
            ?>
            </span>        
        <?php }else{ ?>
            <span style="font-size: 26px;  font-family:sans-serif; color: #CC071D; font-weight:Regular; padding-top: -100px;   margin-left: 90px;">
            <?php
            $image_url =  THINGSATWEB_BASE . '/img/smartstoringlogonew.png';
            $type = pathinfo($image_url, PATHINFO_EXTENSION);
            $image_data = file_get_contents($image_url);
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($image_data);
            echo '<img class="filter-item-image mx-auto my-0 md:my-4 !h-6 sm:!h-9 md:!h-11 w-auto"  src="' . $base64 . '" alt="Manual" style="justify-content: center; align-items: center; width:auto; height:45px;  margin-left:0px; margin-top:-90px; ">';
            ?>
            </span>        
        <?php } ?>
        
        <?php
        //if ((in_array('custom_uam_reseller_eur', $user_roles) || in_array('custom_uam_reseller_sek', $user_roles)) &&  (!empty($reseller_color) && isset($reseller_color))){ ?>
            <span style="height:30px; width: 800px; text-align: center; color: <?php echo esc_attr($reseller_color); ?>;font-size: 26px; margin-top:-50px;   padding-top: -80px; font-weight:semi-bold; margin-left:80px; font-family: 'Poppins', sans-serif; ">
        <?php //}else{ ?>
            <!-- <span style="height:30px; width: 800px; text-align: center; color: #CC071D;font-size: 26px; margin-top:-50px;   padding-top: -80px; font-weight:semi-bold; margin-left:80px;  font-family: 'Poppins', sans-serif; "> -->
        <?php //} ?>
                
        <!-- <span style="height:30px;color: #CC071D;font-size: 26px; margin-top:-50px;   padding-top: -80px; font-weight:semi-bold; margin-left:80px;  font-family: 'Poppins', sans-serif; "> -->
             <!-- <?php //if($lang=='en'){ ?>  Product data sheet <?php //}elseif($lang=='sv'){ ?> Produktdatablad <?php //} ?> -->
        <!-- </span> -->
        <?php 
        if ((in_array('custom_uam_reseller_eur', $user_roles) || in_array('custom_uam_reseller_sek', $user_roles)) &&  (!empty($reseller_color) && isset($reseller_color))){ ?>
            <span style="width: 700px; margin-left:0px; font-size: 10px; color: white; font-family: 'Poppins', sans-serif; text-align: center; padding-top:6px; padding-bottom:4px;background-color: <?php echo esc_attr($reseller_color); ?>; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; cursor: pointer; display: flex; justify-content: center;"></span>
        <?php }else{ ?>
            <span style="width: 700px; margin-left:0px; font-size: 10px; color: white; font-family: 'Poppins', sans-serif; text-align: center; padding-top:6px; padding-bottom:4px;background-color: #CC071D; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; cursor: pointer; display: flex; justify-content: center;"></span>
        <?php } ?>
    </div>
    <div style="float:left;">
        <div style="width:280px; margin-left: 240px; background-color: #E6E7E9;  padding-top:15px;  margin-bottom:-40px ; margin-top:-60px ;margin-right:90px">
        <?php if ((in_array('custom_uam_reseller_eur', $user_roles) || in_array('custom_uam_reseller_sek', $user_roles)) &&  (!empty($reseller_color) && isset($reseller_color))){ ?>
            <span style="font-size: 26px; height:30px;  font-family: 'Poppins', sans-serif;padding-bottom:50px; color: <?php echo esc_attr($reseller_color); ?>; font-weight:semi-bold; margin-left: 35px;">
            <?php }else{ ?>
            <span style="font-size: 26px; height:30px;  font-family: 'Poppins', sans-serif;padding-bottom:50px; color: #CC071D; font-weight:semi-bold; margin-left: 35px;">
            <?php } ?>
            <?php if($lang=='en'){ ?>  Product data sheet <?php }elseif($lang=='sv'){ ?> Produktdatablad <?php } ?>
            </span>
        </div>
    </div>
    <div style="float:left;">
        <div style="width:175px; margin-left:-40px; background-color: #FFFFFF;  padding-top:20px;  margin-bottom:-40px ; margin-top:-47px ;margin-right:90px">
            <span style="font-size: 16px;  font-family: 'Poppins', sans-serif;padding-bottom:50px; color: black; font-weight:semi-bold; margin-left: 35px;">
                <?php
                echo $product->get_sku();
                ?>
            </span>
            <span style="width: 165px; font-size: 14px; margin-top:6px; color: white; font-weight:semi-bold; font-family: 'Poppins', sans-serif; text-align: left;background-color:#666666; padding-top:6px; padding-left:10px; padding-bottom:6px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; cursor: pointer; display: flex; justify-content: center;">
            <?php if($lang=='en'){ ?>  Printed: <?php }elseif($lang=='sv'){ ?> Utskriven: <?php } ?> <?php echo $todayDate; ?></span>
        </div>
    </div>
</div>
<br>
<div style="display:flex; height:915px;">
    <!-- product image -->
    <div style="height: 296px; width: 500px; text-align: center; margin-left: auto; margin-right: auto;  margin-top: 10px;">
        <?php
            $image_url = get_the_post_thumbnail_url($product->get_id());
            // $image_size = getimagesize($image_url);
            // $actual_image_width = $image_size[0];
        ?>
        <div style="width: 100%; height: 100%; overflow: hidden; display: flex; align-items: center; justify-content: center;">
            <?php
            if (!empty(get_the_post_thumbnail_url($product->get_id()))) {
            $image_url = get_the_post_thumbnail_url($product->get_id());
            $type = pathinfo($image_url, PATHINFO_EXTENSION);
            $image_data = file_get_contents($image_url);
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($image_data);
            echo '<img src="' . $base64 . '" alt="Manual" style="width: auto; height: 100%; max-width: 100%; max-height: 100%; display: flex; align-items: center; justify-content: center;">';
            } else {
            $imageurl = THINGSATWEB_BASE . '/img/smartstoring-Fav-Icon-300x300.png';
            $type = pathinfo($imageurl, PATHINFO_EXTENSION);
            $image_data = file_get_contents($imageurl);
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($image_data);
            echo '<img src="' . $base64 . '" alt="Manual" style="width: auto; height: 100%; max-width: 100%; max-height: 100%;">';
            }
            ?>
        </div>
    </div>

    <!-- product title -->
    <?php if (!empty($product->get_title())) : ?>
        <div style="width:95%; margin-top:10px; padding-bottom:30px;">
            <div style="float:left; margin-left: -20px;">
                <?php 
        if ((in_array('custom_uam_reseller_eur', $user_roles) || in_array('custom_uam_reseller_sek', $user_roles)) &&  (!empty($reseller_color) && isset($reseller_color))){ ?>
            <span style="width:60px; font-size: 10px; color: white; font-family:sans-serif; text-align: center; padding-top:6px; padding-bottom:4px;background-color: <?php echo esc_attr($reseller_color); ?>; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; cursor: pointer; display: flex; justify-content: center;"></span>
                <?php }else{ ?>
                    <span style="width:60px; font-size: 10px; color: white; font-family:sans-serif; text-align: center; padding-top:6px; padding-bottom:4px;background-color: #C70039; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; cursor: pointer; display: flex; justify-content: center;"></span>
                <?php } ?>
                <!-- <span style="width:60px; font-size: 10px; color: white; font-family:sans-serif; text-align: center; padding-top:6px; padding-bottom:4px;background-color: #C70039; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; cursor: pointer; display: flex; justify-content: center;"></span> -->
            </div>
            <div style="margin-left: 6px; float:left;  margin-top: -5px;">
                <?php 
        if ((in_array('custom_uam_reseller_eur', $user_roles) || in_array('custom_uam_reseller_sek', $user_roles)) &&  (!empty($reseller_color) && isset($reseller_color))){ ?>
            <span style="font-size: 14px; font-family: 'Poppins', sans-serif; color:<?php echo esc_attr($reseller_color); ?>; font-weight:bold; width: 95%; max-width: 40%;  ">
                <?php }else{ ?>
                    <span style="font-size: 14px; font-family: 'Poppins', sans-serif; color: #C70039; font-weight:bold; width: 95%; max-width: 40%;  ">
                <?php } ?>
                <!-- <span style="font-size: 14px; font-family: 'Poppins', sans-serif; color: #C70039; font-weight:bold; width: 95%; max-width: 40%;  "> -->
                    <?php 
                    //  echo $actual_image_width;
                    echo $product->get_title();
                    ?>
                </span>
            </div>
        </div>
    <?php endif; ?>
    <br>
    <br>
    <?php //if (!empty($product->get_description())) : ?>
        <!-- product description -->
        <div style="margin-left: 50px; height: 245px; margin-top: -40px; margin-bottom:20px;  width:90%; overflow: hidden;">
            <p style="font-size: 11px; font-family: 'Poppins', sans-serif;  line-height: 15px;">
                <?php
                  $description = $product->get_description();
                  $formattedDescription = nl2br($description);
                  echo $formattedDescription;
                ?>
            </p>
        </div>
    <?php //endif; ?>
    <!-- Technical specification -->
    <?php 
    if($lang=='en')
    { 
    $attributes = $product->get_attributes();
    foreach ($attributes as $attr => $attr_deets) {
        $attribute_label = wc_attribute_label($attr);
        if (isset($attributes[$attr]) || isset($attributes['pa_' . $attr])) {
            $attribute = isset($attributes[$attr]) ? $attributes[$attr] : $attributes['pa_' . $attr];
            if ($attribute['is_taxonomy']) {
                $formatted_attributes[$attribute_label] = implode(' | ', wc_get_product_terms($product->id, $attribute['name'], array('fields' => 'names')));
            } else {
                $formatted_attributes[$attribute_label] = $attribute['value'];
            }
        }
    }
    $formatted_attri = array(); // Initialize $formatted_attri as an array
    $attributes = $product->get_attributes();
    foreach ($attributes as $attr => $attr_deets) {
     $attribute_slug = wc_attribute_label($attr); // Use attribute_slug instead of attribute_name
     if (isset($attributes[$attr]) || isset($attributes['pa_' . $attr])) {
         $attribute = isset($attributes[$attr]) ? $attributes[$attr] : $attributes['pa_' . $attr];
         if ($attribute['is_taxonomy']) {
             // Query the database to get attribute_name based on attribute_slug
             $attribute_name = $wpdb->get_var(
                 $wpdb->prepare("SELECT attribute_name FROM tsm_woocommerce_attribute_taxonomies WHERE attribute_label = %s", $attribute_slug)
             );

             $formatted_attri['pa_' . $attribute_name] = implode(' | ', wc_get_product_terms($product->id, $attribute['name'], array('fields' => 'slugs')));
         } else {
            $formatted_attri['pa_' . $attribute_slug] = $attribute['value'];
         }
     }
 }

 $categories = $product->get_category_ids();
//  print_r($categories);
//  print_r('**************');
 // Initialize a variable to store the result
$categoryName = '';

foreach ($categories as $categoryId) {
    $childquery = "SELECT term.name FROM tsm_terms as term 
                    INNER JOIN tsm_term_taxonomy as taxonomy ON term.term_id = taxonomy.term_id
                    WHERE term.term_id = $categoryId AND taxonomy.parent != 0 limit 1";
    $childresult = $wpdb->get_var($childquery);

    if (!empty($childresult)) {
        // If the child result is not empty, use it and break out of the loop
        $categoryName = $childresult;
        break;
    }

    $parentquery = "SELECT term.name FROM tsm_terms as term 
                    INNER JOIN tsm_term_taxonomy as taxonomy ON term.term_id = taxonomy.term_id
                    WHERE term.term_id = $categoryId AND taxonomy.parent = 0 limit 1";
    $parentresult = $wpdb->get_var($parentquery);

    // Check if parent result is not empty
    if (!empty($parentresult)) {
        $categoryName = $parentresult;
    }
}

//print_r($categoryName);

     $categorywise_result = array();
     foreach ($formatted_attri as $attribute => $att_value) {
         // Explode the att_value if it contains commas
         $att_values = explode(' | ', $att_value);
 
         foreach ($att_values as $single_att_value) {
             // Use $wpdb->get_results() to fetch multiple rows
             $categorywise = $wpdb->prepare(
                 "SELECT attribute, att_value FROM taw_filter_setting 
                 WHERE cate_no = %s AND attribute = %s AND att_value = %s 
                 AND datasheet = '1' AND datasheetfilt_enable = '1'",
                 $categoryName,
                 $attribute,
                 $single_att_value
             );
             $result = $wpdb->get_results($categorywise);
             // Store the result in the array
             $categorywise_result[] = $result;
         }
     }


 $formatted_result = array();

 foreach ($categorywise_result as $result_set) {
     foreach ($result_set as $result) {
         // Check if the attribute key already exists in the formatted result array
         if (!isset($formatted_result[$result->attribute])) {
             $formatted_result[$result->attribute] = $result->att_value;
         } else {
             // If the attribute key already exists, concatenate the values with a comma
             $formatted_result[$result->attribute] .= ' | ' . $result->att_value;
         }
     }
 }
 
 $cleaned_result = array();

 foreach ($formatted_result as $attribute => $att_value) {
     // Explode att_value to handle multiple values
     $values = explode(' | ', $att_value);
     $clean_attribute = str_replace('pa_', '', $attribute);
     $label = $wpdb->get_var(
         $wpdb->prepare("SELECT attribute_label FROM tsm_woocommerce_attribute_taxonomies WHERE attribute_name = %s", $clean_attribute)
     );
     $label = $label ? $label : $clean_attribute;
     foreach ($values as &$value) {
         // Use the $wpdb->get_var() to get the name from the tsm_terms table
         $name = $wpdb->get_var(
             $wpdb->prepare("SELECT name FROM tsm_terms WHERE slug = %s", $value)
         );
 
         // If the name is found, use it; otherwise, keep the original value
         $value = $name ? $name : $value;
     }
 
     // Implode the values back
     $cleaned_result[$label] = implode(' | ', $values);
 }

 //print_r($cleaned_result);
    }elseif($lang=='sv')
    {
        global $wpdb;
        $objid=$product->get_id();
        $objid_query = "SELECT
    termtaxonomy.taxonomy as attribute_label,
        terms.name as attvalue
        FROM 
        tsm_term_relationships as relation 
    INNER JOIN 
        tsm_term_taxonomy as termtaxonomy 
        ON relation.term_taxonomy_id = termtaxonomy.term_taxonomy_id 
    INNER JOIN 
        tsm_terms as terms 
        ON termtaxonomy.term_id = terms.term_id
    LEFT JOIN 
        tsm_woocommerce_attribute_taxonomies as attribute_tax
        ON REPLACE(termtaxonomy.taxonomy, 'pa_', '') = attribute_tax.attribute_name
    WHERE 
        object_id = $objid
        AND termtaxonomy.taxonomy NOT IN ('product_cat', 'product_type', 'translation_priority');";
                    $objresult = $wpdb->get_results($objid_query);

    // Organize the results into an associative array
    $formatted_attributes = array();

    foreach ($objresult as $row) {
        $attribute_label = $row->attribute_label;
        $attvalue = $row->attvalue;

        if (!isset($formatted_attributes[$attribute_label])) {
            $formatted_attributes[$attribute_label] = $attvalue;
        } else {
            $formatted_attributes[$attribute_label] .= ' | ' . $attvalue;
        }
    }
  
    $objid=$product->get_id();
        $objid_query = "SELECT 
    termtaxonomy.taxonomy as attribute_label,
        terms.name as attvalue
        FROM 
        tsm_term_relationships as relation 
    INNER JOIN 
        tsm_term_taxonomy as termtaxonomy 
        ON relation.term_taxonomy_id = termtaxonomy.term_taxonomy_id 
    INNER JOIN 
        tsm_terms as terms 
        ON termtaxonomy.term_id = terms.term_id
    LEFT JOIN 
        tsm_woocommerce_attribute_taxonomies as attribute_tax
        ON REPLACE(termtaxonomy.taxonomy, 'pa_', '') = attribute_tax.attribute_name
    WHERE 
        object_id = $objid
        AND termtaxonomy.taxonomy NOT IN ('product_cat', 'product_type', 'translation_priority');";
                    $objresult = $wpdb->get_results($objid_query);

    // Organize the results into an associative array
    $formatted_attri = array();

    foreach ($objresult as $row) {
        $attribute_label = $row->attribute_label;
        $attvalue = $row->attvalue;

        if (!isset($formatted_attri[$attribute_label])) {
            $formatted_attri[$attribute_label] = $attvalue;
        } else {
            $formatted_attri[$attribute_label] .= ' | ' . $attvalue;
        }
    }

    $objchildcate_query = "SELECT terms.name FROM tsm_term_relationships as relation 
    INNER JOIN tsm_term_taxonomy as termtaxonomy ON relation.term_taxonomy_id = termtaxonomy.term_taxonomy_id 
    INNER JOIN tsm_terms as terms on terms.term_id=termtaxonomy.term_id
    WHERE object_id = $objid AND termtaxonomy.parent != 0  AND termtaxonomy.taxonomy='product_cat' limit 1;";
    $objchildcate_result = $wpdb->get_results($objchildcate_query);

    $objparentcate_query = "SELECT terms.name FROM tsm_term_relationships as relation 
    INNER JOIN tsm_term_taxonomy as termtaxonomy ON relation.term_taxonomy_id = termtaxonomy.term_taxonomy_id 
    INNER JOIN tsm_terms as terms on terms.term_id=termtaxonomy.term_id
    WHERE object_id = $objid AND termtaxonomy.parent = 0  AND termtaxonomy.taxonomy='product_cat' limit 1;";
    $objparentcate_result = $wpdb->get_results($objparentcate_query);

//     // $categories = array(); // Initialize an empty array
// if(isset( $objchildcate_result ) && !empty($objchildcate_result)){
// foreach ($objchildcate_result as $childresult) {
//     $categories[] = $childresult->term_id;
// }
// }elseif(isset( $objparentcate_query ) && !empty($objparentcate_query)){

// foreach ($objparentcate_result as $parentresult) {
//     $categories[] = $parentresult->term_id;
// }
// }

$category = ''; // Initialize an empty string
if (isset($objchildcate_result) && !empty($objchildcate_result)) {
    $category = $objchildcate_result[0]->name;
} elseif (isset($objparentcate_result) && !empty($objparentcate_result)) {
    $category = $objparentcate_result[0]->name;
}

//   print_r($category);
 $categorywise_result = array();
 
//   foreach ($categories as $categoryId) {
                // $childquery =  "SELECT termtaxonomy.description FROM tsm_term_relationships as relation 
                // INNER JOIN tsm_term_taxonomy as termtaxonomy ON relation.term_taxonomy_id = termtaxonomy.term_taxonomy_id 
                // WHERE object_id = $objid  AND termtaxonomy.taxonomy='product_cat';";
                // $categoryName = $wpdb->get_var($childquery);
                // print_r($categoryName);

    //  $category = get_term($categoryId, 'product_cat');
     //$categoryName = $category->name;
     //print_r($formatted_attri);
     foreach ($formatted_attri as $attribute => $att_value) {
         // Explode the att_value if it contains commas
         $att_values = explode(' | ', $att_value);
         
         foreach ($att_values as $single_att_value) {
         
             // Use $wpdb->get_results() to fetch multiple rows
             $categorywise = $wpdb->prepare(
                 "SELECT attribute, att_value FROM taw_filter_setting 
                 WHERE cate_no = %s AND attribute = %s AND att_value = %s 
                 AND datasheet = '1' AND datasheetfilt_enable = '1'",
                 $category,
                 $attribute,
                 $single_att_value
             );
             //print_r($categorywise);
             $result = $wpdb->get_results($categorywise);
             // Store the result in the array
             $categorywise_result[] = $result;
         }
     }
 //}
   //print_r($category);
 $formatted_result = array();

 foreach ($categorywise_result as $result_set) {

     foreach ($result_set as $result) {
         // Check if the attribute key already exists in the formatted result array
         if (!isset($formatted_result[$result->attribute])) {
             $formatted_result[$result->attribute] = $result->att_value;
         } else {
             // If the attribute key already exists, concatenate the values with a comma
             $formatted_result[$result->attribute] .= ' | ' . $result->att_value;
         }
     }
 }

 $cleaned_result = array();

 foreach ($formatted_result as $attribute => $att_value) {
     // Explode att_value to handle multiple values
     $values = explode(' | ', $att_value);
     $clean_attribute = str_replace('pa_', '', $attribute);
     $label = $wpdb->get_var(
         $wpdb->prepare("SELECT attribute_label FROM tsm_woocommerce_attribute_taxonomies WHERE attribute_name = %s", $clean_attribute)
     );
     $label = $label ? $label : $clean_attribute;
     foreach ($values as &$value) {
         // Use the $wpdb->get_var() to get the name from the tsm_terms table
         $name = $wpdb->get_var(
             $wpdb->prepare("SELECT name FROM tsm_terms WHERE slug = %s", $value)
         );
 
         // If the name is found, use it; otherwise, keep the original value
         $value = $name ? $name : $value;
     }
 
     // Implode the values back
     $cleaned_result[$label] = implode(' | ', $values);
    }
   // print_r($cleaned_result);
}
    ?>
     
    <?php 
        $productcategory = $product->get_categories();

        if (isset($cleaned_result['Weight capacity']) || isset($cleaned_result['Extension']) || isset($cleaned_result['Colour'])
        || isset($cleaned_result['Load metod']) || isset($cleaned_result['Type of load']) || isset($cleaned_result['Loading'])
        || isset($cleaned_result['Load Way']) || isset($cleaned_result['Picking Method']) || isset($cleaned_result['Short or long side handled']) 
        || isset($cleaned_result['Shelf lock']) || isset($cleaned_result['Number of shelves']) || isset($cleaned_result['Mounted onto product'])
        ) :
        ?>

    <?php if ((strpos($productcategory, "Tailor-made product") === false)) : ?>
        <div style="margin-top:20px; width:900px; max-width:40%;">
            <span style="font-size:14px; font-family:sans-serif; font-weight:bold; margin-left:20px;">
                 <?php if($lang=='en'){ ?>  PRODUCT FEATURES <?php }elseif($lang=='sv'){ ?> Produktegenskaper <?php } ?>
            </span>
        </div>
    <?php endif; ?>
    <?php endif; ?>
    <br>

    <div style="height: 230px; margin-left: -2px; margin-top: 10px; display: flex; justify-content: space-between; align-items: center;">
        <?php
        $divHeight = 'height: 90px;';
        $paddingbottom='padding-bottom:0px;';
        $imgheight='height:35px;';

        if (isset($cleaned_result) && is_array($cleaned_result)) 
        {
            if (isset($cleaned_result['Load metod'])) {
            $loadMethodValues = $cleaned_result['Load metod'];
            $loadmethodvaluesArray = explode(', ', $loadMethodValues);
            $loadmetodcount = count($loadmethodvaluesArray);
            }
            if (isset($cleaned_result['Picking Method'])) {
            $picking = $cleaned_result['Picking Method'];
            $pickingvalues = explode(', ', $picking);
            $pickingcount = count($pickingvalues);
            }
            if (isset($cleaned_result['Type of load'])) {
            $typeofloadValues = $cleaned_result['Type of load'];
            $typeofloadValuesArray = explode(', ', $typeofloadValues);
            $typeofloadcount = count($typeofloadValuesArray);
            }
        
            if ($loadmetodcount >= 4 || $typeofloadcount >= 4) {
                $divHeight = 'height: 100px;';
            }elseif ($loadmetodcount == 3 || $typeofloadcount == 3) {
                $divHeight = 'height: 95px;';
            }elseif ($loadmetodcount == 2 || $typeofloadcount == 2) {
                $divHeight = 'height: 95px;';
            }

            //$paddingbottom='padding-bottom:0px;';

            if ($loadmetodcount >= 3 || $typeofloadcount >= 3) {
                $paddingbottom = 'padding-bottom:4px;';
            }
        }
       

        // if ($loadmetodcount >= 4 || $typeofloadcount >= 4) {
        //     $imgheight = 'height: 60px;';
        // }


        $productcategory = $product->get_categories();
        if ((strpos($productcategory, "Tailor-made product") === false)) :
            if($lang=='en')
            {
            $attributesToDisplay = [
                'Type of load' => [
                    'label' => 'TYPE OF LOAD',
                    'image_urls' => [
                        'bins &amp; boxes' => [
                            'url' => THINGSATWEB_BASE . '/img/black_PlasticBin.png',
                            'width' => '20px',
                            'height' => '25px',
                        ],
                        '½ eur-pallet' => [
                            'url' => THINGSATWEB_BASE . '/img/black_halfEUR.png',
                            'width' => '25px',
                            'height' => '25px',
                        ],
                        'eur-pallet' => [
                            'url' => THINGSATWEB_BASE . '/img/black_EUR.png',
                            'width' => '25px',
                            'height' => '25px',
                        ],
                        'fin-/ chep-pallet' => [
                            'url' => THINGSATWEB_BASE . '/img/black_FIN.png',
                            'width' => '25px',
                            'height' => '25px',
                        ],
                        
                        'gitterbox' => [
                            'url' => THINGSATWEB_BASE . '/img/black_HMUbox.png',
                            'width' => '15px',
                            'height' => '20px',
                        ],
                        'pipes' => [
                            'url' => THINGSATWEB_BASE . '/img/black_Pipe.png',
                            'width' => '10px',
                            'height' => '25px',
                        ],
                        'steel shelf' => [
                            'url' => THINGSATWEB_BASE . '/img/black_Steelshelf.png',
                            'width' => '15px',
                            'height' => '25px',
                        ],
                    ],

                ],
                'Loading' => [
                    'label' => 'LOADING',
                    'image_urls' => [
                        'front loaded' => [
                            'url' => THINGSATWEB_BASE . '/img/black_FrontLoad.png',
                            'width' => '40px',
                            'height' => '25px',
                        ],
                        'rear loaded' => [
                            'url' => THINGSATWEB_BASE . '/img/black_RearLoad.png',
                            'width' => '40px',
                            'height' => '25px',
                        ],
                    ],
                ],
                'Short or long side handled' => [
                    'label' => 'SHORT OR LONG SIDE HANDLED',
                    'image_urls' => [
                        'long side handled' => [
                            'url' => THINGSATWEB_BASE . '/img/black_LongSideHandling.png',
                            'width' => '20px',
                            'height' => '30px',
                        ],
                        'short side handled' => [
                            'url' => THINGSATWEB_BASE . '/img/black_ShortSideHandling.png',
                            'width' => '15px',
                            'height' => '30px',
                        ],
                        '½ eur-pallet long side handle' => [
                            'url' => THINGSATWEB_BASE . '/img/1by2-Long-1-300x235.png',
                            'width' => '25px',
                            'height' => '40px',
                        ],
                       
                    ],
                ],
                'Weight capacity' => [
                    'label' => 'WEIGHT CAPACITY',
                    'image_url' => THINGSATWEB_BASE . '/img/black_Loadcapacity.png',
                    'width' => '25px',
                    'height' => '25px',
                ],
                'Extension' => [
                    'label' => 'EXTENSION',
                    'image_urls' => [
                        '100%' => [
                            'url' => THINGSATWEB_BASE . '/img/black_OneWay100.png',
                            'width' => '30px',
                            'height' => '25px',
                        ],
                        '70%' => [
                            'url' => THINGSATWEB_BASE . '/img/black_OneWay70.png',
                            'width' => '30px',
                            'height' => '25px',
                        ],
                        '85%' => [
                            'url' => THINGSATWEB_BASE . '/img/black_OneWay85.png',
                            'width' => '30px',
                            'height' => '25px',
                        ],
                        '95%' => [
                            'url' => THINGSATWEB_BASE . '/img/black_OneWay95.png',
                            'width' => '30px',
                            'height' => '25px',
                        ],
                        '74%' => [
                            'url' => THINGSATWEB_BASE . '/img/black_OneWay74.png',
                            'width' => '30px',
                            'height' => '25px',
                        ],
                        '65%' => [
                            'url' => THINGSATWEB_BASE . '/img/black_OneWay65.png',
                            'width' => '30px',
                            'height' => '25px',
                        ],
                        'two-way 2×70%' => [
                            'url' => THINGSATWEB_BASE . '/img/black_TwoWay70.png',
                            'width' => '30px',
                            'height' => '25px',
                        ],
                    ],

                ],
                'Load Way' => [
                    'label' => 'LOAD WAY',
                    'image_urls' => [
                        'one-way' => [
                            'url' => THINGSATWEB_BASE . '/img/black_OneWay.png',
                            'width' => '35px',
                            'height' => '15px',
                        ],
                        'two-way' => [
                            'url' => THINGSATWEB_BASE . '/img/black_TwoWay.png',
                            'width' => '35px',
                            'height' => '15px',
                        ],
                    ],
                ],
                'Number of shelves' => [
                    'label' => 'NUMBER OF SHELVES',
                    'image_urls' => [
                        '1 shelve' => [
                            'url' => THINGSATWEB_BASE . '/img/black_1.png',
                            'width' => '20px',
                            'height' => '20px',
                        ],
                        '2 shelves' => [
                            'url' => THINGSATWEB_BASE . '/img/black_2.png',
                            'width' => '20px',
                            'height' => '20px',
                        ],
                        '3 shelves' => [
                            'url' => THINGSATWEB_BASE . '/img/black_3.png',
                            'width' => '20px',
                            'height' => '20px',
                        ],
                        '4 shelves' => [
                            'url' => THINGSATWEB_BASE . '/img/black_4.png',
                            'width' => '20px',
                            'height' => '20px',
                        ],
                        '5 shelves' => [
                            'url' => THINGSATWEB_BASE . '/img/black_5.png',
                            'width' => '20px',
                            'height' => '20px',
                        ],
                        '6 shelves' => [
                            'url' => THINGSATWEB_BASE . '/img/black_6.png',
                            'width' => '20px',
                            'height' => '20px',
                        ],
                        '7 shelves' => [
                            'url' => THINGSATWEB_BASE . '/img/black_7.png',
                            'width' => '20px',
                            'height' => '20px',
                        ],
                        '8 shelves' => [
                            'url' => THINGSATWEB_BASE . '/img/black_8.png',
                            'width' => '20px',
                            'height' => '20px',
                        ],
                        '9 shelves' => [
                            'url' => THINGSATWEB_BASE . '/img/black_9.png',
                            'width' => '20px',
                            'height' => '20px',
                        ],
                        '10 shelves' => [
                            'url' => THINGSATWEB_BASE . '/img/black_10.png',
                            'width' => '20px',
                            'height' => '20px',
                        ],
                        '11 shelves' => [
                            'url' => THINGSATWEB_BASE . '/img/black_11.png',
                            'width' => '20px',
                            'height' => '20px',
                        ],
                    ],

                ],
                'Shelf lock' => [
                    'label' => 'SHELF LOCK',
                    'image_url' => THINGSATWEB_BASE . '/img/black_ShelfLock.png',
                    'width' => '30px',
                    'height' => '30px',
                ],
                'Colour' => [
                    'label' => 'COLOUR',
                    'image_url' => THINGSATWEB_BASE . '/img/black_Colour.png',
                    'width' => '25px',
                    'height' => '25px',
                ],
                'Load metod' => [
                    'label' => 'LOAD METOD',
                    'image_urls' => [
                        'by hand' => [
                            'url' => THINGSATWEB_BASE . '/img/black_Byhand.png',
                            'width' => '10px',
                            'height' => '25px',
                        ],
                        'forklift' => [
                            'url' => THINGSATWEB_BASE . '/img/black_ForkLift.png',
                            'width' => '20px',
                            'height' => '20px',
                        ],
                        'hand pallet truck' => [
                            'url' => THINGSATWEB_BASE . '/img/black_Handpallettruck.png',
                            'width' => '10px',
                            'height' => '25px',
                        ],
                        'overhead crane' => [
                            'url' => THINGSATWEB_BASE . '/img/black_Overheadcrane.png',
                            'width' => '10px',
                            'height' => '25px',
                        ],
                        'pallet stacker' => [
                            'url' => THINGSATWEB_BASE . '/img/black_Palletstacker.png',
                            'width' => '10px',
                            'height' => '25px',
                        ],

                    ],

                ],
                'Picking Method' => [
                    'label' => 'PICKING METHOD',
                    'image_urls' => [
                        'by hand' => [
                            'url' => THINGSATWEB_BASE . '/img/black_Byhand.png',
                            'width' => '15px',
                            'height' => '30px',
                        ],
                        'overhead crane' => [
                            'url' => THINGSATWEB_BASE . '/img/black_Overheadcrane.png',
                            'width' => '12px',
                            'height' => '30px',
                        ],

                    ],
                ],
                'Mounted onto product' => [
                    'label' => 'MOUNTED ONTO PRODUCT',
                    'image_urls' => [
                        'yes' => [
                            'url' => THINGSATWEB_BASE . '/img/black_mountedyes.png',
                            'width' => '25px',
                            'height' => '25px',
                        ],
                        'no' => [
                            'url' => THINGSATWEB_BASE . '/img/black_mountedno.png',
                            'width' => '25px',
                            'height' => '25px',
                        ],

                    ],
                ],
                // Add more attributes as needed
            ];
         }elseif($lang=='sv')
         {
            $attributesToDisplay = [
                'Type of load' => [
                    'label' => 'Typ av last',
                    'image_urls' => [
                        'eur-pall' => [
                            'url' => THINGSATWEB_BASE . '/img/black_EUR.png',
                            'width' => '25px',
                            'height' => '25px',
                        ],
                        'nätcontainer' => [
                            'url' => THINGSATWEB_BASE . '/img/black_HMUbox.png',
                            'width' => '15px',
                            'height' => '20px',
                        ],
                        '½ eur-pal' => [
                            'url' => THINGSATWEB_BASE . '/img/black_halfEUR.png',
                            'width' => '25px',
                            'height' => '25px',
                        ],
                        'fin-pall' => [
                            'url' => THINGSATWEB_BASE . '/img/black_FIN.png',
                            'width' => '25px',
                            'height' => '25px',
                        ],
                        'lådor' => [
                            'url' => THINGSATWEB_BASE . '/img/black_PlasticBin.png',
                            'width' => '20px',
                            'height' => '25px',
                        ],
                        'rör' => [
                            'url' => THINGSATWEB_BASE . '/img/black_Pipe.png',
                            'width' => '10px',
                            'height' => '25px',
                        ],
                        'stålplan' => [
                            'url' => THINGSATWEB_BASE . '/img/black_Steelshelf.png',
                            'width' => '15px',
                            'height' => '25px',
                        ],
                    ],

                ],
                'Loading' => [
                    'label' => 'Lastning',
                    'image_urls' => [
                        'frontlastad' => [
                            'url' => THINGSATWEB_BASE . '/img/black_FrontLoad.png',
                            'width' => '40px',
                            'height' => '25px',
                        ],
                        'baklastad' => [
                            'url' => THINGSATWEB_BASE . '/img/black_RearLoad.png',
                            'width' => '40px',
                            'height' => '25px',
                        ],
                    ],
                ],
                'Short or long side handled' => [
                    'label' => 'Kort- eller långsideshanterad',
                    'image_urls' => [
                        'långsideshanterad' => [
                            'url' => THINGSATWEB_BASE . '/img/black_LongSideHandling.png',
                            'width' => '20px',
                            'height' => '30px',
                        ],
                        'kortsideshanterad' => [
                            'url' => THINGSATWEB_BASE . '/img/black_ShortSideHandling.png',
                            'width' => '15px',
                            'height' => '30px',
                        ],
                        '½ eur-pall långsideshanterad' => [
                            'url' => THINGSATWEB_BASE . '/img/1by2-Long-1-300x235.png',
                            'width' => '20px',
                            'height' => '30px',
                        ],
                    ],
                ],
                'Weight capacity' => [
                    'label' => 'Viktkapacitet',
                    'image_url' => THINGSATWEB_BASE . '/img/black_Loadcapacity.png',
                    'width' => '25px',
                    'height' => '25px',
                ],
                'Extension' => [
                    'label' => 'Utdrag',
                    'image_urls' => [
                        '100%' => [
                            'url' => THINGSATWEB_BASE . '/img/black_OneWay100.png',
                            'width' => '30px',
                            'height' => '25px',
                        ],
                        '70%' => [
                            'url' => THINGSATWEB_BASE . '/img/black_OneWay70.png',
                            'width' => '30px',
                            'height' => '25px',
                        ],
                        '85%' => [
                            'url' => THINGSATWEB_BASE . '/img/black_OneWay85.png',
                            'width' => '30px',
                            'height' => '25px',
                        ],
                        '95%' => [
                            'url' => THINGSATWEB_BASE . '/img/black_OneWay95.png',
                            'width' => '30px',
                            'height' => '25px',
                        ],
                        '74%' => [
                            'url' => THINGSATWEB_BASE . '/img/black_OneWay74.png',
                            'width' => '30px',
                            'height' => '25px',
                        ],
                        '65%' => [
                            'url' => THINGSATWEB_BASE . '/img/black_OneWay65.png',
                            'width' => '30px',
                            'height' => '25px',
                        ],
                        'tvåvägs 2×70%' => [
                            'url' => THINGSATWEB_BASE . '/img/black_TwoWay70.png',
                            'width' => '30px',
                            'height' => '25px',
                        ],
                    ],

                ],
                'Load Way' => [
                    'label' => 'Lastmetod',
                    'image_urls' => [
                        'envägs' => [
                            'url' => THINGSATWEB_BASE . '/img/black_OneWay.png',
                            'width' => '35px',
                            'height' => '15px',
                        ],
                        'tvåvägs' => [
                            'url' => THINGSATWEB_BASE . '/img/black_TwoWay.png',
                            'width' => '35px',
                            'height' => '15px',
                        ],
                    ],
                ],
                'Number of shelves' => [
                    'label' => 'Antal hyllor',
                    'image_urls' => [
                        '1 hyllor' => [
                            'url' => THINGSATWEB_BASE . '/img/black_1.png',
                            'width' => '20px',
                            'height' => '20px',
                        ],
                        '2 hyllor' => [
                            'url' => THINGSATWEB_BASE . '/img/black_2.png',
                            'width' => '20px',
                            'height' => '20px',
                        ],
                        '3 hyllor' => [
                            'url' => THINGSATWEB_BASE . '/img/black_3.png',
                            'width' => '20px',
                            'height' => '20px',
                        ],
                        '4 hyllor' => [
                            'url' => THINGSATWEB_BASE . '/img/black_4.png',
                            'width' => '20px',
                            'height' => '20px',
                        ],
                        '5 hyllor' => [
                            'url' => THINGSATWEB_BASE . '/img/black_5.png',
                            'width' => '20px',
                            'height' => '20px',
                        ],
                        '6 hyllor' => [
                            'url' => THINGSATWEB_BASE . '/img/black_6.png',
                            'width' => '20px',
                            'height' => '20px',
                        ],
                        '7 hyllor' => [
                            'url' => THINGSATWEB_BASE . '/img/black_7.png',
                            'width' => '20px',
                            'height' => '20px',
                        ],
                        '8 hyllor' => [
                            'url' => THINGSATWEB_BASE . '/img/black_8.png',
                            'width' => '20px',
                            'height' => '20px',
                        ],
                        '9 hyllor' => [
                            'url' => THINGSATWEB_BASE . '/img/black_9.png',
                            'width' => '20px',
                            'height' => '20px',
                        ],
                        '10 hyllor' => [
                            'url' => THINGSATWEB_BASE . '/img/black_10.png',
                            'width' => '20px',
                            'height' => '20px',
                        ],
                        '11 hyllor' => [
                            'url' => THINGSATWEB_BASE . '/img/black_11.png',
                            'width' => '20px',
                            'height' => '20px',
                        ],
                    ],

                ],
                'Shelf lock' => [
                    'label' => 'Tippskyddslås',
                    'image_url' => THINGSATWEB_BASE . '/img/black_ShelfLock.png',
                    'width' => '30px',
                    'height' => '30px',
                ],
                'Colour' => [
                    'label' => 'Färg',
                    'image_url' => THINGSATWEB_BASE . '/img/black_Colour.png',
                    'width' => '25px',
                    'height' => '25px',
                ],
                'Load metod' => [
                    'label' => 'Lastmetod',
                    'image_urls' => [
                        'travers' => [
                            'url' => THINGSATWEB_BASE . '/img/black_Overheadcrane.png',
                            'width' => '10px',
                            'height' => '25px',
                        ],
                        'gaffeltruck' => [
                            'url' => THINGSATWEB_BASE . '/img/black_ForkLift.png',
                            'width' => '20px',
                            'height' => '20px',
                        ],
                        'ledstaplare' => [
                            'url' => THINGSATWEB_BASE . '/img/black_Palletstacker.png',
                            'width' => '10px',
                            'height' => '25px',
                        ],
                        'palldragare' => [
                            'url' => THINGSATWEB_BASE . '/img/black_Handpallettruck.png',
                            'width' => '10px',
                            'height' => '25px',
                        ],
                        
                        'manuellt' => [
                            'url' => THINGSATWEB_BASE . '/img/black_Byhand.png',
                            'width' => '10px',
                            'height' => '25px',
                        ],

                    ],

                ],
                'Picking Method' => [
                    'label' => 'Plockningsmetod',
                    'image_urls' => [
                        'manuell' => [
                            'url' => THINGSATWEB_BASE . '/img/black_Byhand.png',
                            'width' => '15px',
                            'height' => '30px',
                        ],
                        'travers eller lyftverktyg' => [
                            'url' => THINGSATWEB_BASE . '/img/black_Overheadcrane.png',
                            'width' => '12px',
                            'height' => '30px',
                        ],

                    ],
                ],
                'Mounted onto product' => [
                    'label' => 'Monterad på produkt',
                    'image_urls' => [
                        'ja' => [
                            'url' => THINGSATWEB_BASE . '/img/black_mountedyes.png',
                            'width' => '25px',
                            'height' => '25px',
                        ],
                        'nej' => [
                            'url' => THINGSATWEB_BASE . '/img/black_mountedno.png',
                            'width' => '25px',
                            'height' => '25px',
                        ],

                    ],
                ],

            ];

        }
            foreach ($attributesToDisplay as $attributeKey => $attributeData) {
                if (isset($cleaned_result[$attributeKey])) {

                    echo '<div style="width: 9.5%; align-items: center; padding: 2px; display: inline-block; box-sizing: border-box; margin: 5px 22px; ' . $divHeight . '">';
                    echo '<div style="width: 100%; border: 1px solid; padding: 2px; display: flex; align-items: center; margin-bottom: -1px; padding-left: 20px; padding-right: 20px; border-color: #374151; display: flex; justify-content: center; align-items: center; position: relative; ' . $divHeight . ';">';
        ?>
                    <!-- <div style="width: 10%; align-items: center; padding: 2px; display: inline-block; box-sizing: border-box; margin: 5px 22px; height: 150px;">
            <div style="width: 100%; border: 1px solid; padding: 2px; display: flex; align-items: center; margin-bottom: -1px; padding-left: 20px; padding-right: 20px; border-color: #374151; display: flex; justify-content: center; align-items: center; position: relative; height: 150px;"> -->
                    <span style="position: absolute;font-family: 'Poppins', sans-serif; top: 5px; left: 0; right: 0; text-align: center; font-size: 8px; color: black; font-weight:bold; display: flex; justify-content: center; width: 100%; height: 5px;">

                        <!-- <span style="position: absolute; top: 5px; left: 0; right: 0; text-align: center; font-size: 9px; color: black; font-family: 'Poppins', sans-serif; font-weight: bold; display: flex; justify-content: center; width: 100%; margin-bottom: 2px;padding-bottom:4px; height: 2px;"> -->
                        <?php echo $attributeData['label']; ?>
                    </span>
                    <div style="<?php echo $imgheight; ?>; text-align: center; padding-top: 30px;  display: flex; justify-content: center; margin-top: 5px; align-items: center;">
                        <?php
                        // Add an image if needed
                        if (!empty($attributeData['image_url'])) {
                            $image_url = $attributeData['image_url'];
                            $type = pathinfo($image_url, PATHINFO_EXTENSION);
                            $image_data = file_get_contents($image_url);
                            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($image_data);
                        ?>
                            <img style=" display: block; width: <?php echo $attributeData['width']; ?>; height: <?php echo $attributeData['height']; ?>" src="<?php echo $base64; ?>" alt="Manual" />
                            <?php
                        }
                        if (!empty($attributeData['image_urls'])) {
                            $extensions = explode(' | ', strtolower($cleaned_result[$attributeKey]));
                            foreach ($attributeData['image_urls'] as $percentage => $data) {
                                foreach ($extensions as $extension) {
                                    if (strtolower(trim($extension)) == strtolower(trim($percentage))) {
                                        $type = pathinfo($data['url'], PATHINFO_EXTENSION);
                                        $image_data = file_get_contents($data['url']);
                                        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($image_data);
                                        ?>
                                        <img style="<?php echo $paddingbottom; ?> ; display: block; width: <?php echo $data['width']; ?>; height: <?php echo $data['height']; ?>" src="<?php echo $base64; ?>" alt="Manual" />
                                        <?php
                                    }
                                }
                            }
                        }
                        ?>
                        
                    </div>
                    <span style="position: absolute; font-family: 'Poppins', sans-serif; left: 0; right: 0; text-align: center; font-size: 9px; color: black; font-weight: normal; display: flex; justify-content: center; width: 100%; padding-bottom: 15px; height: 5px;">
                    <!-- <span style="text-align: center;  font-family: 'Poppins', sans-serif; font-size: 12px; color: black; font-weight: normal; width: 100%; cursor: pointer; display: inline-block; max-width: 100%; overflow-wrap: break-word; word-wrap: break-word; hyphens: auto; height: 42px; padding-bottom:5px;"> -->
                        <?php
                        $keyvalue = $cleaned_result[$attributeKey];
                        echo $keyvalue;
                        ?>
                    </span>
        <?php
                    echo '</div></div>';
                }
            }

        endif;
        ?>
       
    </div>
    <br>
    <br>
    <br>
</div>
<div style="background-color:#F2F2F2; display:flex; width:728px; padding:6px; margin-left:-20px;margin-top:-25px; font-family: 'Poppins', sans-serif;">

    <?php
        if ((in_array('custom_uam_reseller_eur', $user_roles) || in_array('custom_uam_reseller_sek', $user_roles)) &&  (!empty($reseller_color) && isset($reseller_color))){ ?>
            <span style="width: 200px; margin-left: 250px; font-size: 10px; margin-top:0px; color: <?php echo esc_attr($reseller_color); ?>; font-weight:bold; font-family: 'Poppins', sans-serif; text-align: left; padding-top:2px; padding-left:20px; padding-right:20px; padding-bottom:2px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; cursor: pointer; display: flex; justify-content: center;">
    <?php }else{ ?>
        <span style="width: 200px; margin-left: 250px; font-size: 10px; margin-top:0px; color: #CC071D; font-weight:bold; font-family: 'Poppins', sans-serif; text-align: left; padding-top:2px; padding-left:20px; padding-right:20px; padding-bottom:2px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; cursor: pointer; display: flex; justify-content: center;">
    <?php } ?>  
       
    <!-- <span style="width: 200px; margin-left: 250px; font-size: 10px; margin-top:0px; color: #CC071D; font-weight:bold; font-family: 'Poppins', sans-serif; text-align: left; padding-top:2px; padding-left:20px; padding-right:20px; padding-bottom:2px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; cursor: pointer; display: flex; justify-content: center;"> -->
        <?php if($lang=='en'){ ?>  PROPRIETARY AND CONFIDENTIAL <?php }elseif($lang=='sv'){ ?>ÄGANDERÄTT OCH KONFIDENTIELLT<?php } ?>
    
    </span>
    <?php if($lang=='en'){ ?> 
        <?php if ((in_array('custom_uam_reseller_eur', $user_roles) || in_array('custom_uam_reseller_sek', $user_roles)) &&  (!empty($reseller_name) && isset($reseller_name))){ ?>
            <span style="width: 728px; margin-left: 0px; font-size: 8px; color: black; font-weight:normal; font-family: 'Poppins', sans-serif; text-align: center; padding-top:2px; padding-left:2px; padding-right:2px; padding-bottom:2px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; cursor: pointer; display: flex; justify-content: center;">
                The information contained in this product data sheet is the sole property of <?php echo $reseller_name;?>. Any reproduction in part or as a whole without the written permission of <?php echo $reseller_name; ?> is prohibited
            </span>
            <?php }else{ ?>
            <span style="width: 900px; margin-left: 0px; font-size: 8px; color: black; font-weight:normal; font-family: 'Poppins', sans-serif; text-align: left; padding-top:2px; padding-left:2px; padding-right:2px; padding-bottom:2px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; cursor: pointer; display: flex; justify-content: center;">
                The information contained in this product data sheet is the sole property of TSM Smartstoring AB. Any reproduction in part or as a whole without the written permission of TSM Smartstoring AB is prohibited
            </span>
            <?php } ?>  
        <?php }elseif($lang=='sv'){ ?>
            <?php if ((in_array('custom_uam_reseller_eur', $user_roles) || in_array('custom_uam_reseller_sek', $user_roles)) &&  (!empty($reseller_name) && isset($reseller_name))){ ?>
                <span style="width: 728px; margin-left: 0px; font-size: 8px; color: black; font-weight:normal; font-family: 'Poppins', sans-serif; text-align: center; padding-top:2px; padding-left:20px; padding-right:20px; padding-bottom:2px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; cursor: pointer; display: flex; justify-content: center;">
                Informationen i detta produktdatablad tillhör <?php echo $reseller_name;?>. All kopiering i sin helhet eller delvis utan skriftligt tillstånd från <?php echo $reseller_name;?> är förbjuden
            </span>
            <?php }else{ ?>
                <span style="width: 900px; margin-left: 90px; font-size: 8px; color: black; font-weight:normal; font-family: 'Poppins', sans-serif; text-align: left; padding-top:2px; padding-left:20px; padding-right:20px; padding-bottom:2px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; cursor: pointer; display: flex; justify-content: center;">
                Informationen i detta produktdatablad tillhör TSM Smartstoring AB. All kopiering i sin helhet eller delvis utan skriftligt tillstånd från TSM Smartstoring AB är förbjuden
            </span>
            <?php } ?>  
        <?php } ?>
   
    <span style="width: 800px; margin-left: 280px; font-size: 13px; margin-top:-6px; color: black; font-weight:bold; font-family: 'Poppins', sans-serif; text-align: left; padding-top:4px; padding-left:20px; padding-right:20px; padding-bottom:4px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; cursor: pointer; display: flex; justify-content: center;">

    </span>
</div>
<br>
<div style="display:flex; margin-bottom: -100px; margin-top:-15px;margin-left:-20px">
    <div style="float:left;">
        <!-- <div style="width: 1000px;  background-color:#4B4C4D; padding-top:45px; padding-bottom:-8px;"> -->
        <?php
        $image_url =  THINGSATWEB_BASE . '/img/smartstoringlogo.png';
        $type = pathinfo($image_url, PATHINFO_EXTENSION);
        $image_data = file_get_contents($image_url);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($image_data);
        //echo '<img class="filter-item-image mx-auto my-0 md:my-4 !h-6 sm:!h-9 md:!h-11 w-auto"  src="' . $base64 . '" alt="Manual" style="justify-content: center; align-items: center; width:300px; height:30px;  margin-left:360px; ">';
        ?>
        <div style="width: 683px;background-color:#4B4C4D;padding-top:2px; padding-bottom:4px;">
            <span style="font-size: 18px;  margin-left:25px; color: white; font-family: 'Poppins', sans-serif; text-align: center; ">
                <?php
        if ((in_array('custom_uam_reseller_eur', $user_roles) || in_array('custom_uam_reseller_sek', $user_roles)) &&  (!empty($reseller_website) && isset($reseller_website))){ ?>
            <a href="https://<?php echo esc_attr($reseller_website); ?>/" style="color: white;margin-left:25px; text-decoration: none;"> <?php echo esc_attr($reseller_website); ?></a>
                <?php }else{ ?>
                    <a href="https://smartstoring.eu/" style="color: white;margin-left:25px; text-decoration: none;">smartstoring.eu</a>
                <?php } ?>
                <!-- <a href="https://smartstoring.eu/" style="color: white;margin-left:25px; text-decoration: none;">smartstoring.eu</a> -->
            </span>
        </div>

        <!-- </div> -->
       
        <?php
        if ((in_array('custom_uam_reseller_eur', $user_roles) || in_array('custom_uam_reseller_sek', $user_roles)) &&  (!empty($reseller_color) && isset($reseller_color))){ ?>
            <span style="width: 683px; font-size: 10px; color: white; font-family: 'Poppins', sans-serif; text-align: center; margin-top:3px;padding-top:9px; padding-bottom:9px;background-color:  <?php echo esc_attr($reseller_color); ?>; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; cursor: pointer; display: flex; justify-content: center;"></span>
        <?php }else{ ?>
            <span style="width: 683px; font-size: 10px; color: white; font-family: 'Poppins', sans-serif; text-align: center; margin-top:3px;padding-top:9px; padding-bottom:9px;background-color: #CC071D; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; cursor: pointer; display: flex; justify-content: center;"></span>
        <?php } ?>
        <!-- <span style="width: 683px; font-size: 10px; color: white; font-family: 'Poppins', sans-serif; text-align: center; margin-top:3px;padding-top:9px; padding-bottom:9px;background-color: #CC071D; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; cursor: pointer; display: flex; justify-content: center;"></span> -->
    </div>

    <div style="background-color: #374151; float:left; width: 40px; padding-top:5px; padding-bottom:5px; padding-left:6px; padding-right:6px; margin-left:5px;">
        <span style="font-size: 32px; font-family: 'Poppins', sans-serif; color: white; font-weight:bold; text-align: center;opacity: 1; ">01</span>
    </div>

</div>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<!-- <div style="background-color: #CC071D; margin-top: -10px;"> -->
<?php $meta  = get_post_meta($product->get_id(), 'taw_prod_opt');
$meta = isset($meta[0]) ? $meta[0] : array();
if (
    !empty($meta['article_price']['product_diagram_file']['url']) ||   !empty($meta['article_price']['product_diagram_file2']['url']) ||   !empty($meta['article_price']['product_diagram_file3']['url'])||
    isset($cleaned_result['Loading Width']) || isset($cleaned_result['Loading Depth']) || isset($cleaned_result['Height'])
    || isset($cleaned_result['Rack Depth']) || isset($cleaned_result['Section width']) || isset($cleaned_result['Section Height'])
    || isset($cleaned_result['Width']) || isset($cleaned_result['Length'])
    || isset($cleaned_result['Depth']) || isset($cleaned_result['Loading height']) 
    || isset($cleaned_result['Product Weight']) || isset($cleaned_result['Quantity at pallet'])
    || isset($cleaned_result['Package weight']) || isset($cleaned_result['Number of pallets / Trailer'])
    || isset($cleaned_result['Quantity in the Package']) || isset($cleaned_result['Package Size'])
    || isset($cleaned_result['WCSB 1 pallet']) || isset($cleaned_result['WCSB 2 pallets']) || 
    isset($cleaned_result['WCSB 3 pallets'])
) : ?>

    <?php if  (isset($meta['article_price']['product_diagram_file']['url']) && !empty($meta['article_price']['product_diagram_file']['url'])) {   ?>  
    <div style="display:flex; height:1005px;">
    <?php  }else{ ?>
        <div style="display:flex; height:972px;">
    <?php } ?>

                <?php if (isset($meta['article_price']['product_diagram_file']['url']) && !empty($meta['article_price']['product_diagram_file']['url'])) {    
                    $diagram1 = $meta['article_price']['product_diagram_file']['url']; ?>
                    <div class="diagram-container" style="margin-left: 70px; height: 520px; overflow: hidden; margin-top: -40px;">
                        <?php
                            $type = pathinfo($diagram1, PATHINFO_EXTENSION);
                            $image_data = file_get_contents($diagram1);
                            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($image_data);
                             echo '<img src="' . $base64 . '" alt="Manual" class="diagram-image">';
                            ?>
                    </div>
                <?php } ?>
                <style>
                    .diagram-container {
                        width: 95%;
                        height: 100%;
                        display: flex;
                        align-items: left;
                        justify-content: left;
                    }

                    .diagram-image {
                        max-width: 100%;
                        max-height: 100%;
                    ;
                    }
                </style>
                <?php if  ((isset($meta['article_price']['product_diagram_file2']['url']) && !empty($meta['article_price']['product_diagram_file2']['url'])) ||   (isset($meta['article_price']['product_diagram_file3']['url']) &&  !empty($meta['article_price']['product_diagram_file3']['url']))) {   ?>  
                    <div style="width:600px; height: 125px;  padding-bottom:30px;">                    
                        <?php if (isset($meta['article_price']['product_diagram_file2']['url']) && !empty($meta['article_price']['product_diagram_file2']['url'])) {    
                                $diagram2 = $meta['article_price']['product_diagram_file2']['url']; ?>
                            <div style="float:left; margin-left: 30px;">
                                    <?php
                                    $type = pathinfo($diagram2, PATHINFO_EXTENSION);
                                    $image_data = file_get_contents($diagram2);
                                    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($image_data);
                                    // echo '<img src="' . $base64 . '" alt="Manual" 
                                    // style="margin-bottom:9px; margin-left:50px; margin-right:10px; width:500px; height:300px;">';
                                    echo '<img src="' . $base64 . '" alt="Manual" style="margin-bottom:2px; margin-top:2px; max-width:460px;height: auto;">';  
                                    ?>                
                            </div>
                        <?php } ?>
                        <?php if (isset($meta['article_price']['product_diagram_file3']['url']) && !empty($meta['article_price']['product_diagram_file3']['url'])) { ?>
                           
                                <div style="margin-left:20px; float:left; "> 
                                    <?php
                                    $diagram3 = $meta['article_price']['product_diagram_file3']['url'];
                                    $type = pathinfo($diagram3, PATHINFO_EXTENSION);
                                    $image_data = file_get_contents($diagram3);
                                    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($image_data);
                                    // echo '<img src="' . $base64 . '" alt="Manual" 
                                    // style="margin-bottom:9px; margin-left:50px; margin-right:10px; width:500px; height:300px;">';
                                    echo '<img src="' . $base64 . '" alt="Manual" style="margin-bottom:2px; margin-top:2px; max-width:160px; height: auto;">';  
                                    ?>  
                                </div>
                          
                        <?php } ?>
                    </div>
                <?php } ?>
           
            <br>
            <br>
        
        <br>
        <?php if ((strpos($productcategory, "Tailor-made product") === false)) : ?>
            <?php
            if (isset($cleaned_result['Loading Width']) || isset($cleaned_result['Loading Depth']) || isset($cleaned_result['Height'])
            || isset($cleaned_result['Rack Depth']) || isset($cleaned_result['Section width']) || isset($cleaned_result['Section Height'])
            || isset($cleaned_result['Width']) || isset($cleaned_result['Length'])
            || isset($cleaned_result['Depth']) || isset($cleaned_result['Loading height'])
            ) : ?>

            <div style="height:145px; margin-left: -35px; ">
                <div style="width:900px; padding-bottom:10px;">
                    <span style="font-size:14px; font-family: 'Poppins', sans-serif; font-weight:bold;  margin-left:50px;">
                        <?php if ($lang == "en") { ?> TECHNICAL SPECIFICATIONS <?php } elseif ($lang == "sv") { ?> Teknisk specifikation <?php } ?>
                    </span>
                </div>

                <br>
                <?php
                $divHeight = 'height: 90px;';
                if ($lang == "en") {
                $attributesToDisplay = [
                    'Width' => [
                        'label' => 'WIDTH',
                        'image_url' => THINGSATWEB_BASE . '/img/black_Width.png',
                        'width' => '25px',
                        'height' => '15px',
                    ],
                    'Loading Width' => [
                        'label' => 'LOADING WIDTH',
                        'image_url' => THINGSATWEB_BASE . '/img/black_LoadingWidth.png',
                        'width' => '25px',
                        'height' => '15px',
                    ],
                    'Depth' => [
                        'label' => 'DEPTH',
                        'image_url' => THINGSATWEB_BASE . '/img/black_Depth.png',
                        'width' => '25px',
                        'height' => '25px',
                    ],
                    'Loading Depth' => [
                        'label' => 'LOADING DEPTH',
                        'image_url' => THINGSATWEB_BASE . '/img/black_LoadingDepth.png',
                        'width' => '25px',
                        'height' => '25px',
                    ],
                    'Length' => [
                        'label' => 'LENGTH',
                        'image_url' => THINGSATWEB_BASE . '/img/black_Length.png',
                        'width' => '25px',
                        'height' => '20px',
                    ],
                    'Loading height' => [
                        'label' => 'LOADING HEIGHT',
                        'image_url' => THINGSATWEB_BASE . '/img/black_Loadingheight.png',
                        'width' => '20px',
                        'height' => '25px',
                    ],
                    'Height' => [
                        'label' => 'HEIGHT',
                        'image_url' => THINGSATWEB_BASE . '/img/black_Height.png',
                        'width' => '15px',
                        'height' => '30px',
                    ],
                    'Total Height' => [
                        'label' => 'TOTAL HEIGHT',
                        'image_url' => THINGSATWEB_BASE . '/img/smartstoring-Fav-Icon-300x300.png',
                        'width' => '15px',
                        'height' => '30px',
                    ],
                    'Rack Depth' => [
                        'label' => 'RACK DEPTH',
                        'image_url' => THINGSATWEB_BASE . '/img/black_RackDepth.png',
                        'width' => '20px',
                        'height' => '30px',
                    ],
                    'Handle Height' => [
                        'label' => 'HANDLE HEIGHT',
                        'image_url' => THINGSATWEB_BASE . '/img/smartstoring-Fav-Icon-300x300.png',
                        'width' => '15px',
                        'height' => '30px',
                    ],
                    'Section width' => [
                        'label' => 'SECTION WIDTH',
                        'image_url' => THINGSATWEB_BASE . '/img/black_SectionWidth.png',
                        'width' => '30px',
                        'height' => '30px',

                    ],
                    'Section Height' => [
                        'label' => 'SECTION HEIGHT',
                        'image_url' => THINGSATWEB_BASE . '/img/black_Sectionheight.png',
                        'width' => '20px',
                        'height' => '30px',
                    ],
                ];
             } elseif ($lang == "sv") 
             {
                $attributesToDisplay = [
                    'Width' => [
                        'label' => 'Bredd',
                        'image_url' => THINGSATWEB_BASE . '/img/black_Width.png',
                        'width' => '30px',
                        'height' => '20px',
                    ],
                    'Loading Width' => [
                        'label' => 'Last Bredd',
                        'image_url' => THINGSATWEB_BASE . '/img/black_LoadingWidth.png',
                        'width' => '30px',
                        'height' => '15px',
                    ],
                    'Depth' => [
                        'label' => 'Djup',
                        'image_url' => THINGSATWEB_BASE . '/img/black_Depth.png',
                        'width' => '30px',
                        'height' => '30px',
                    ],
                    'Loading Depth' => [
                        'label' => 'Last Djup',
                        'image_url' => THINGSATWEB_BASE . '/img/black_LoadingDepth.png',
                        'width' => '30px',
                        'height' => '30px',

                    ],
                    'Length' => [
                        'label' => 'Längd',
                        'image_url' => THINGSATWEB_BASE . '/img/black_Length.png',
                        'width' => '30px',
                        'height' => '20px',
                    ],
                    'Loading height' => [
                        'label' => 'Last Höjd',
                        'image_url' => THINGSATWEB_BASE . '/img/black_Loadingheight.png',
                        'width' => '30px',
                        'height' => '30px',
                    ],
                    'Height' => [
                        'label' => 'Höjd',
                        'image_url' => THINGSATWEB_BASE . '/img/black_Height.png',
                        'width' => '15px',
                        'height' => '30px',
                    ],
                    'Total Height' => [
                        'label' => 'Total Höjd',
                        'image_url' => THINGSATWEB_BASE . '/img/smartstoring-Fav-Icon-300x300.png',
                        'width' => '15px',
                        'height' => '30px',
                    ],
                    'Rack Depth' => [
                        'label' => 'Djup Pallställ',
                        'image_url' => THINGSATWEB_BASE . '/img/black_RackDepth.png',
                        'width' => '20px',
                        'height' => '30px',
                    ],
                    'Handle Height' => [
                        'label' => 'Handtags Höjd',
                        'image_url' => THINGSATWEB_BASE . '/img/smartstoring-Fav-Icon-300x300.png',
                        'width' => '15px',
                        'height' => '30px',
                    ],
                    'Section width' => [
                        'label' => 'Sektions Bredd',
                        'image_url' => THINGSATWEB_BASE . '/img/black_SectionWidth.png',
                        'width' => '30px',
                        'height' => '30px',

                    ],
                    'Section Height' => [
                        'label' => 'Sektions Höjd',
                        'image_url' => THINGSATWEB_BASE . '/img/black_Sectionheight.png',
                        'width' => '30px',
                        'height' => '30px',
                    ],
                ];
             }

                foreach ($attributesToDisplay as $attributeKey => $attributeData) {
                    if (isset($cleaned_result[$attributeKey])) {

                        echo '<div style="width:0.9%; align-items: center;  display: inline-block; box-sizing: border-box; margin-left:52px; ' . $divHeight . '">';
                        echo '<div style="width: 100%; border: 1px solid; display: flex; align-items: center; margin-bottom: -1px; padding-left: 23px; padding-right: 23px; border-color: #374151; display: flex; justify-content: center; align-items: center; position: relative; ' . $divHeight . ';">';
                ?>
                        <!-- <div style="width: 10%; align-items: center; padding: 2px; display: inline-block; box-sizing: border-box; margin: 5px 22px; height: 150px;">
                     <div style="width: 100%; border: 1px solid; padding: 2px; display: flex; align-items: center; margin-bottom: -1px; padding-left: 20px; padding-right: 20px; border-color: #374151; display: flex; justify-content: center; align-items: center; position: relative; height: 150px;"> -->
                        <span style="position: absolute;font-family: 'Poppins', sans-serif; top: 5px; left: 0; right: 0; text-align: center; font-size: 8px; color: black; font-weight:bold; display: flex; justify-content: center; width: 100%; height: 10px;">
                            <?php echo $attributeData['label']; ?>
                        </span>
                        <div style="height: 35px; text-align: center;  padding-top:30px;  display: flex; justify-content: center;margin-top: 5px;  align-items: center;">
                            <?php
                            // Add an image if needed
                            if (!empty($attributeData['image_url'])) {
                                $image_url = $attributeData['image_url'];
                                $type = pathinfo($image_url, PATHINFO_EXTENSION);
                                $image_data = file_get_contents($image_url);
                                $base64 = 'data:image/' . $type . ';base64,' . base64_encode($image_data);
                            ?>
                                <img style=" display: block; width: <?php echo $attributeData['width']; ?>; height: <?php echo $attributeData['height']; ?>" src="<?php echo $base64; ?>" alt="Manual" />
                            <?php
                            }
                            ?>
                        </div>
                        <!-- <span style="text-align: center; font-size: 12px; color: black; font-weight: normal; width: 100%; cursor: pointer; display: inline-block; max-width: 100%; overflow-wrap: break-word; word-wrap: break-word; hyphens: auto; height: 40px;"> -->
                        <span style="position: absolute;font-family: 'Poppins', sans-serif; left: 0; right: 0; text-align: center; font-size: 8px; color: black; font-weight: normal; display: flex; justify-content: center; width: 100%;  height: 5px;">
                            <?php
                            // $keyvalue = str_replace(' ', '', $cleaned_result[$attributeKey]);
                            // echo $keyvalue;
                            $keyvalue = $cleaned_result[$attributeKey];
                            echo $keyvalue;
                            ?>
                        </span>

                <?php
                        echo '</div></div>';
                    }
                } 
                ?>
            </div>
        <?php endif; ?>
        <?php endif; ?>
        <?php if ((strpos($productcategory, "Tailor-made product") === false)) : ?>
        <?php
        if ( (isset($cleaned_result['Product Weight'])) || (isset($cleaned_result['Quantity at pallet'])) || 
             (isset($cleaned_result['Package weight'])) || (isset($cleaned_result['Number of pallets / Trailer'])) || 
             (isset($cleaned_result['Quantity in the Package'])) || (isset($cleaned_result['Package Size']))||
             (isset($cleaned_result['WCSB 1 pallet'])) || (isset($cleaned_result['WCSB 2 pallets'])) ||
             (isset($cleaned_result['WCSB 3 pallets']))
            ) : ?>
                <?php
                if ((in_array('custom_uam_reseller_eur', $user_roles) || in_array('custom_uam_reseller_sek', $user_roles)) &&  (!empty($reseller_color) && isset($reseller_color))){ ?>
                    <span style=" margin-left:16px; width: 700px; margin-top: -10px; margin-bottom: 10px; display: flex;border: 1px solid <?php echo esc_attr($reseller_color); ?>; opacity: 1; "></span>
                <?php }else{ ?>
                    <span style=" margin-left:16px; width: 700px; margin-top: -10px; margin-bottom: 10px; display: flex;border: 1px solid #CC071D;opacity: 1; "></span>
                <?php } ?>                     
	    <?php endif; ?>

        <?php
        if ((isset($cleaned_result['Product Weight']))  || (isset($cleaned_result['Quantity at pallet'])) ||
            (isset($cleaned_result['Package weight'])) || (isset($cleaned_result['Number of pallets / Trailer'])) ||
            (isset($cleaned_result['Quantity in the Package'])) || (isset($cleaned_result['Package Size'])) ||
            (isset($cleaned_result['WCSB 1 pallet'])) || (isset($cleaned_result['WCSB 2 pallets'])) ||
            (isset($cleaned_result['WCSB 3 pallets']))
            ) : ?>
        <div style="height:105px; margin-left: -35px; ">
            <?php
            if ((isset($cleaned_result['Product Weight']))  || (isset($cleaned_result['Quantity at pallet'])) ||
            (isset($cleaned_result['Package weight'])) || (isset($cleaned_result['Number of pallets / Trailer'])) ||
            (isset($cleaned_result['Quantity in the Package'])) || (isset($cleaned_result['Package Size']))
            ) : ?>
                    <?php if($lang=='en'){ ?> <div  style="width:600px; float: left;">
                    <?php }elseif($lang=='sv'){ ?> <div  style="width:630px; float: left;"> <?php } ?>
                    <div style="width:500px; max-width:40%; height:30px;">
                        <span style="font-size:14px; font-family: 'Poppins', sans-serif; font-weight:bold;  margin-left:50px;">
                            <?php if ($lang == "en") { ?> WEIGHT AND VOLUME <?php } elseif ($lang == "sv") { ?> Vikt och volym <?php } ?>
                        </span>
                    </div>

                    <br>
                    <?php
                    $divHeight = 'height: 90px;';
                    if ($lang == "en") {
                    $attributesToDisplay = [
                        'Product Weight' => [
                            'label' => 'PRODUCT WEIGHT',
                            'image_url' => THINGSATWEB_BASE . '/img/black_Productweight.png',
                            'width' => '30px',
                            'height' => '30px',
                        ],
                        'Box Weight' => [
                            'label' => 'BOX WEIGHT',
                            'image_url' => THINGSATWEB_BASE . '/img/black_BoxWeight.png',
                            'width' => '30px',
                            'height' => '30px',
                        ],
                        'Quantity in the Package' => [
                            'label' => 'QUANTITY IN THE PACKAGE',
                            'image_url' => THINGSATWEB_BASE . '/img/black_QuantityinthePackage.png',
                            'width' => '30px',
                            'height' => '30px',

                        ],
                        'Box Size' => [
                            'label' => 'BOX SIZE',
                            'image_url' => THINGSATWEB_BASE . '/img/black_BoxSize.png',
                            'width' => '30px',
                            'height' => '30px',
                        ],
                        'Quantity on pallet' => [
                            'label' => 'QUANTITY ON PALLET',
                            'image_url' => THINGSATWEB_BASE . '/img/black_Quantityatpallet.png',
                            'width' => '30px',
                            'height' => '30px',
                        ],
                        'Pallet weight' => [
                            'label' => 'PALLET WEIGHT',
                            'image_url' => THINGSATWEB_BASE . '/img/black_Packageweight.png',
                            'width' => '30px',
                            'height' => '30px',
                        ],
                        'Pallet Size' => [
                            'label' => 'PALLET SIZE',
                            'image_url' => THINGSATWEB_BASE . '/img/black_Packagesize.png',
                            'width' => '30px',
                            'height' => '30px',
                        ],
                        'Number of pallets / Trailer' => [
                            'label' => 'NUMBER OF PALLETS /TRAILER',
                            'image_url' => THINGSATWEB_BASE . '/img/black_NumberofpalletsTrailer.png',
                            'width' => '30px',
                            'height' => '30px',
                        ]

                    ];
                    } elseif ($lang == "sv") 
                    {
                    $attributesToDisplay = [
                        'Product Weight' => [
                            'label' => 'Produktens vikt',
                            'image_url' => THINGSATWEB_BASE . '/img/black_Productweight.png',
                            'width' => '30px',
                            'height' => '30px',
                        ],
                        'Box Weight' => [
                            'label' => 'Förpacknings vikt',
                            'image_url' => THINGSATWEB_BASE . '/img/black_BoxWeight.png',
                            'width' => '30px',
                            'height' => '30px',
                        ],
                        'Quantity in the Package' => [
                            'label' => 'Antal / förpackning',
                            'image_url' => THINGSATWEB_BASE . '/img/black_QuantityinthePackage.png',
                            'width' => '30px',
                            'height' => '30px',

                        ],
                        'Box Size' => [
                            'label' => 'Förpacknings storlek',
                            'image_url' => THINGSATWEB_BASE . '/img/black_BoxSize.png',
                            'width' => '30px',
                            'height' => '30px',
                        ],
                        'Quantity on pallet' => [
                            'label' => 'Antal per pall',
                            'image_url' => THINGSATWEB_BASE . '/img/black_Quantityatpallet.png',
                            'width' => '30px',
                            'height' => '30px',
                        ],
                        'Pallet weight' => [
                            'label' => 'Pallvikt',
                            'image_url' => THINGSATWEB_BASE . '/img/black_Packageweight.png',
                            'width' => '30px',
                            'height' => '30px',
                        ],
                        'Pallet Size' => [
                            'label' => 'Pallstorlek',
                            'image_url' => THINGSATWEB_BASE . '/img/black_Packagesize.png',
                            'width' => '30px',
                            'height' => '30px',
                        ],
                        'Number of pallets / Trailer' => [
                            'label' => 'Antal på en lastbil',
                            'image_url' => THINGSATWEB_BASE . '/img/black_NumberofpalletsTrailer.png',
                            'width' => '30px',
                            'height' => '30px',
                        ]
                    ];
                    }
                    foreach ($attributesToDisplay as $attributeKey => $attributeData) {
                        if (isset($cleaned_result[$attributeKey])) {
                            if($lang=='en'){
                                echo '<div style="width: 0.7%; align-items: center;  display: inline-block; box-sizing: border-box; margin-left:52px; ' . $divHeight . '">';
                            }elseif($lang=='sv'){
                                echo '<div style="width: 1.3%; align-items: center;  display: inline-block; box-sizing: border-box; margin-left:52px; ' . $divHeight . '">';
                            }
                            echo '<div style="width: 100%; border: 1px solid; display: flex; align-items: center; margin-bottom: -1px; padding-left: 23px; padding-right: 23px; border-color: #374151; display: flex; justify-content: center; align-items: center; position: relative; ' . $divHeight . ';">';
                    ?>
                            <!-- <div style="width: 10%; align-items: center; padding: 2px; display: inline-block; box-sizing: border-box; margin: 5px 22px; height: 150px;">
                        <div style="width: 100%; border: 1px solid; padding: 2px; display: flex; align-items: center; margin-bottom: -1px; padding-left: 20px; padding-right: 20px; border-color: #374151; display: flex; justify-content: center; align-items: center; position: relative; height: 150px;"> -->
                            <span style="position: absolute;font-family: 'Poppins', sans-serif; top: 5px; left: 0; right: 0; text-align: center; font-size: 8px; color: black; font-weight:bold; display: flex; justify-content: center; width: 100%; margin-bottom: 5px; padding-bottom:10px; height: 35px;">
                                <?php echo $attributeData['label']; ?>
                            </span>
                            <div style="height: 35px; text-align: center; padding-top: 30px;  display: flex; justify-content: center; margin-top: 10px; align-items: center;">
                                    <?php
                                
                                    if (!empty($attributeData['image_url'])) {
                                        $image_url = $attributeData['image_url'];
                                        $type = pathinfo($image_url, PATHINFO_EXTENSION);
                                        $image_data = file_get_contents($image_url);
                                        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($image_data);
                                    ?>
                                        <img style=" display: block; width: <?php echo $attributeData['width']; ?>; height: <?php echo $attributeData['height']; ?>" src="<?php echo $base64; ?>" alt="Manual" />
                                    <?php
                                    }
                                    ?>
                            </div>
                            <!-- <span style="text-align: center; font-size: 12px; color: black; font-weight: normal; width: 100%; cursor: pointer; display: inline-block; max-width: 100%; overflow-wrap: break-word; word-wrap: break-word; hyphens: auto; height: 40px;"> -->
                            <span style="position: absolute;font-family: 'Poppins', sans-serif; padding-bottom:2px; left: 0; right: 0; text-align: center; font-size: 8px; color: black; font-weight: normal; display: flex; justify-content: center; width: 100%; height: 5px;">
                                <?php
                                // $keyvalue = str_replace(' ', '', $cleaned_result[$attributeKey]);
                                // echo $keyvalue;
                                $keyvalue = $cleaned_result[$attributeKey];
                                echo $keyvalue;
                                ?>
                            </span>

                    <?php
                            echo '</div></div>';
                        }
                    } ?>
                </div>
                <?php endif; ?>
                <?php
                if ((isset($cleaned_result['WCSB 1 pallet'])) || (isset($cleaned_result['WCSB 2 pallets'])) ||
                (isset($cleaned_result['WCSB 3 pallets']))
                ) : ?>
                <?php
                if ((isset($cleaned_result['Product Weight']))  || (isset($cleaned_result['Quantity at pallet'])) ||
                (isset($cleaned_result['Package weight'])) || (isset($cleaned_result['Number of pallets / Trailer'])) ||
                (isset($cleaned_result['Quantity in the Package'])) || (isset($cleaned_result['Package Size']))
                ) : ?>
                <div  style="width:800px; float: left; margin-left:-150px;">
                <?php
                elseif ((!isset($cleaned_result['Product Weight']))  || (!isset($cleaned_result['Quantity at pallet'])) ||
                (!isset($cleaned_result['Package weight'])) || (!isset($cleaned_result['Number of pallets / Trailer'])) ||
                (!isset($cleaned_result['Quantity in the Package'])) || (!isset($cleaned_result['Package Size']))
                ) : ?>
                <div  style="width:800px; float: left;">
                <?php endif; ?>
                    <?php if ($lang == "en") { ?>
                        <div style="height: 30px; margin-top: 0px;">
                            <span style="width: 400px; font-size: 14px; font-family: 'Poppins', sans-serif; font-weight: bold; margin-left: 55px; margin-bottom: 0; display: block;">
                                <?php if ($lang == "en") { ?>WEIGHT CAPACITY SUPPORT BEAM<?php } elseif ($lang == "sv") { ?>Bärbalkskapacitet<?php } ?>
                            </span>

                            <span style="width: 600px; font-size: 8px; font-family: 'Poppins', sans-serif; font-weight: normal; margin-left: 55px; margin-top: 0; display: block;">
                                <?php if ($lang == "en") { ?>OBS! Only one unit per section may be extracted at a time!<?php } elseif ($lang == "sv") { ?>Endast en enhet per sektion får vara utdragen åt gången!<?php } ?>
                            </span>
                        </div>
                    <?php } elseif ($lang == "sv") { ?>
                        <div style="height: 30px; margin-top: 0px;">
                            <span style="width: 100px; font-size: 14px; font-family: 'Poppins', sans-serif; font-weight: bold; margin-left: 55px; margin-bottom: 0; display: block;">
                                <?php if ($lang == "en") { ?>WEIGHT CAPACITY SUPPORT BEAM<?php } elseif ($lang == "sv") { ?>Bärbalkskapacitet<?php } ?>
                            </span>

                            <span style="width: 600px; font-size: 8px; font-family: 'Poppins', sans-serif; font-weight: normal; margin-left: 55px; margin-top: 0; display: block;">
                                <?php if ($lang == "en") { ?>OBS! Only one unit per section may be extracted at a time!<?php } elseif ($lang == "sv") { ?>Endast en enhet per sektion får vara utdragen åt gången!<?php } ?>
                            </span>
                        </div>
                    <?php } ?>
                    <br>
                    <?php
                    $divHeight = 'height: 90px;';
                    if ($lang == "en") {
                    $attributesToDisplay = [
                        'WCSB 1 pallet' => [
                            'label' => '1 PALLET',
                            'image_url' => THINGSATWEB_BASE . '/img/black_1Pallet.png',
                            'width' => '30px',
                            'height' => '30px',
                        ],
                        'WCSB 2 pallets' => [
                            'label' => '2 PALLET',
                            'image_url' => THINGSATWEB_BASE . '/img/black_2Pallet.png',
                            'width' => '40px',
                            'height' => '30px',
                        ],
                        'WCSB 3 pallets' => [
                            'label' => '3 PALLET',
                            'image_url' => THINGSATWEB_BASE . '/img/black_3Pallet.png',
                            'width' => '50px',
                            'height' => '30px',
                        ],
                    ];
                    } elseif ($lang == "sv") 
                    {
                        $attributesToDisplay = [
                            'WCSB 1 pallet' => [
                                'label' => '1 PALL',
                                'image_url' => THINGSATWEB_BASE . '/img/black_1Pallet.png',
                                'width' => '30px',
                                'height' => '30px',
                            ],
                        'WCSB 2 pallets' => [
                            'label' => '2 PALL',
                            'image_url' => THINGSATWEB_BASE . '/img/black_2Pallet.png',
                            'width' => '30px',
                            'height' => '30px',
                        ],
                        'WCSB 3 pallets' => [
                            'label' => '3 PALL',
                            'image_url' => THINGSATWEB_BASE . '/img/black_3Pallet.png',
                            'width' => '30px',
                            'height' => '30px',
                        ],
                    ];
                    }       
                    foreach ($attributesToDisplay as $attributeKey => $attributeData) {
                        if (isset($cleaned_result[$attributeKey])) {

                            echo '<div style="width: 2.5%; align-items: center;  display: inline-block; box-sizing: border-box; margin-left:55px; ' . $divHeight . '">';
                            echo '<div style="width: 100%; border: 1px solid; display: flex; align-items: center; margin-bottom: -1px; padding-left: 23px; padding-right: 23px; border-color: #374151; display: flex; justify-content: center; align-items: center; position: relative; ' . $divHeight . ';">';
                    ?>
                            <!-- <div style="width: 10%; align-items: center; padding: 2px; display: inline-block; box-sizing: border-box; margin: 5px 22px; height: 150px;">
                        <div style="width: 100%; border: 1px solid; padding: 2px; display: flex; align-items: center; margin-bottom: -1px; padding-left: 20px; padding-right: 20px; border-color: #374151; display: flex; justify-content: center; align-items: center; position: relative; height: 150px;"> -->
                            <span style="position: absolute;font-family: 'Poppins', sans-serif; top: 5px; left: 0; right: 0; text-align: center; font-size: 8px; color: black; font-weight:bold; display: flex; justify-content: center; width: 100%; margin-bottom: 5px; padding-bottom:10px; height: 35px;">
                                <?php echo $attributeData['label']; ?>
                            </span>
                            <div style="height: 35px; text-align: center; padding-top: 30px;  display: flex; justify-content: center; margin-top: 5px; align-items: center;">
                                    <?php
                                
                                    if (!empty($attributeData['image_url'])) {
                                        $image_url = $attributeData['image_url'];
                                        $type = pathinfo($image_url, PATHINFO_EXTENSION);
                                        $image_data = file_get_contents($image_url);
                                        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($image_data);
                                    ?>
                                        <img style=" display: block; width: <?php echo $attributeData['width']; ?>; height: <?php echo $attributeData['height']; ?>" src="<?php echo $base64; ?>" alt="Manual" />
                                    <?php
                                    }
                                    ?>
                            </div>
                            <!-- <span style="text-align: center; font-size: 12px; color: black; font-weight: normal; width: 100%; cursor: pointer; display: inline-block; max-width: 100%; overflow-wrap: break-word; word-wrap: break-word; hyphens: auto; height: 40px;"> -->
                            <span style="position: absolute;font-family: 'Poppins', sans-serif; padding-bottom:2px; left: 0; right: 0; text-align: center; font-size: 8px; color: black; font-weight: normal; display: flex; justify-content: center; width: 100%; height: 5px;">
                                <?php
                                // $keyvalue = str_replace(' ', '', $cleaned_result[$attributeKey]);
                                // echo $keyvalue;
                                //$keyvalue = explode(', ', $cleaned_result[$attributeKey]);
                                // echo implode(' | ', $keyvalue);
                                echo str_replace('kg', ' kg', $cleaned_result[$attributeKey]);
                                ?>
                            </span>

                    <?php
                            echo '</div></div>';
                        }
                    } ?>
                </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        <?php endif; ?>

    </div>
   
    <br>
    <br>
    <br>
    <div style="display:flex; margin-bottom: -100px; margin-top:-15px;margin-left:-20px">
    <div style="float:left;">
        <!-- <div style="width: 1000px;  background-color:#4B4C4D; padding-top:45px; padding-bottom:-8px;"> -->
        <?php
        $image_url =  THINGSATWEB_BASE . '/img/smartstoringlogo.png';
        $type = pathinfo($image_url, PATHINFO_EXTENSION);
        $image_data = file_get_contents($image_url);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($image_data);
        //echo '<img class="filter-item-image mx-auto my-0 md:my-4 !h-6 sm:!h-9 md:!h-11 w-auto"  src="' . $base64 . '" alt="Manual" style="justify-content: center; align-items: center; width:300px; height:30px;  margin-left:360px; ">';
        ?>
        <div style="width: 683px;background-color:#4B4C4D;padding-top:2px; padding-bottom:4px;">
            <span style="font-size: 18px;  margin-left:25px; color: white; font-family: 'Poppins', sans-serif; text-align: center; ">
            <?php
        if ((in_array('custom_uam_reseller_eur', $user_roles) || in_array('custom_uam_reseller_sek', $user_roles)) &&  (!empty($reseller_website) && isset($reseller_website))){ ?>
            <a href="https://<?php echo esc_attr($reseller_website); ?>/" style="color: white;margin-left:25px; text-decoration: none;"> <?php echo esc_attr($reseller_website); ?></a>
            <?php }else{ ?>
                <a href="https://smartstoring.eu/" style="color: white;margin-left:25px; text-decoration: none;">smartstoring.eu</a>
            <?php } ?>
                <!-- <a href="https://smartstoring.eu/" style="color: white;margin-left:25px; text-decoration: none;">smartstoring.eu</a> -->
            </span>
        </div>

        <!-- </div> -->
        <?php
        if ((in_array('custom_uam_reseller_eur', $user_roles) || in_array('custom_uam_reseller_sek', $user_roles)) &&  (!empty($reseller_color) && isset($reseller_color))){ ?>
            <span style="width: 683px; font-size: 10px; color: white; font-family: 'Poppins', sans-serif; text-align: center; margin-top:3px;padding-top:9px; padding-bottom:9px;background-color:  <?php echo esc_attr($reseller_color); ?>; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; cursor: pointer; display: flex; justify-content: center;"></span>
        <?php }else{ ?>
            <span style="width: 683px; font-size: 10px; color: white; font-family: 'Poppins', sans-serif; text-align: center; margin-top:3px;padding-top:9px; padding-bottom:9px;background-color: #CC071D; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; cursor: pointer; display: flex; justify-content: center;"></span>
        <?php } ?>
        <!-- <span style="width: 683px; font-size: 10px; color: white; font-family: 'Poppins', sans-serif; text-align: center; margin-top:3px;padding-top:9px; padding-bottom:9px;background-color: #CC071D; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; cursor: pointer; display: flex; justify-content: center;"></span> -->
    </div>

    <div style="background-color: #374151; float:left; width: 40px; padding-top:5px; padding-bottom:5px; padding-left:6px; padding-right:6px; margin-left:5px;">
        <span style="font-size: 32px; font-family: 'Poppins', sans-serif; color: white; font-weight:bold; text-align: center;opacity: 1; ">02</span>
    </div>

</div>
<?php endif; ?>