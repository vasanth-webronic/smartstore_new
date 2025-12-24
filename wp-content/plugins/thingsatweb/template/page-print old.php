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
$categories = $product->get_category_ids();
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

$sku_no=$product->get_sku();

$skuquery = $wpdb->prepare(
    "SELECT * FROM taw_attribute_product_segment WHERE art_no = %s",
    $sku_no
);
$skuresult = $wpdb->get_results($skuquery); // Execute the query

if(!empty($skuresult))
{
    $ProductFeaturesQuery = $wpdb->prepare(
        "SELECT  seg.id as attribute_id,  head.`attribute` as attibutename, head.attr_imgurl as attribute_imgurl, head.attribute_translation as attribute_translation,
        head.datasheet_width as head_datasheet_width, head.datasheet_height as head_datasheet_height,
        subhead.attr_value as attr_value, subhead.datasheet_imgurl as attrvalue_imgurls, 
        subhead.datasheet_width as datasheet_width,subhead.datasheet_height as datasheet_height,
        subhead.attr_value_translation as attr_value_translation  
        FROM taw_attribute_heading as head JOIN taw_attribute_product_segment as seg ON head.id = seg.attribute_id 
        LEFT JOIN taw_attribute_subheading as subhead ON subhead.attribute_id = head.id
        WHERE head.heading = 'Product Features' AND seg.art_no = %s ORDER BY attribute_id ASC;",
       $sku_no
    );
    
    $ProductFeatures = $wpdb->get_results($ProductFeaturesQuery, ARRAY_A);

    $technicalsupportquery = $wpdb->prepare(
        "SELECT  seg.id as attribute_id,  head.`attribute` as attibutename, head.attr_imgurl as attribute_imgurl, head.attribute_translation as attribute_translation,
         head.datasheet_width as head_datasheet_width, head.datasheet_height as head_datasheet_height,
         subhead.attr_value as attr_value, subhead.datasheet_imgurl as attrvalue_imgurls, 
         subhead.datasheet_width as datasheet_width,subhead.datasheet_height as datasheet_height,
         subhead.attr_value_translation as attr_value_translation  
         FROM taw_attribute_heading as head JOIN taw_attribute_product_segment as seg ON head.id = seg.attribute_id 
         LEFT JOIN taw_attribute_subheading as subhead ON subhead.attribute_id = head.id
         WHERE head.heading = 'Technical Specification' AND seg.art_no = %s ORDER BY attribute_id ASC;",
        $sku_no
    );
    
    $technicalsupport = $wpdb->get_results($technicalsupportquery, ARRAY_A);

    $weightvolumesupportquery = $wpdb->prepare(
        "SELECT  seg.id as attribute_id,  head.`attribute` as attibutename, head.attr_imgurl as attribute_imgurl, head.attribute_translation as attribute_translation,
         head.datasheet_width as head_datasheet_width, head.datasheet_height as head_datasheet_height,
         subhead.attr_value as attr_value, subhead.datasheet_imgurl as attrvalue_imgurls, 
         subhead.datasheet_width as datasheet_width,subhead.datasheet_height as datasheet_height,
         subhead.attr_value_translation as attr_value_translation  
         FROM taw_attribute_heading as head JOIN taw_attribute_product_segment as seg ON head.id = seg.attribute_id 
         LEFT JOIN taw_attribute_subheading as subhead ON subhead.attribute_id = head.id
         WHERE head.heading = 'Weight and volume' AND seg.art_no = %s ORDER BY attribute_id ASC;",
        $sku_no
    );
    
    $weightvolumesupport = $wpdb->get_results($weightvolumesupportquery, ARRAY_A);

    $WeightcapacityQuery = $wpdb->prepare(
        "SELECT  seg.id as attribute_id,  head.`attribute` as attibutename, head.attr_imgurl as attribute_imgurl, head.attribute_translation as attribute_translation,
         head.datasheet_width as head_datasheet_width, head.datasheet_height as head_datasheet_height,
         subhead.attr_value as attr_value, subhead.datasheet_imgurl as attrvalue_imgurls, 
         subhead.datasheet_width as datasheet_width,subhead.datasheet_height as datasheet_height,
         subhead.attr_value_translation as attr_value_translation  
         FROM taw_attribute_heading as head JOIN taw_attribute_product_segment as seg ON head.id = seg.attribute_id 
         LEFT JOIN taw_attribute_subheading as subhead ON subhead.attribute_id = head.id
         WHERE head.heading = 'Weight capacity support beam' AND seg.art_no = %s ORDER BY attribute_id ASC;",
        $sku_no
    );
    
    $Weightcapacitysupport = $wpdb->get_results($WeightcapacityQuery, ARRAY_A);

}else{
    $ProductFeaturesQuery = $wpdb->prepare(
        "SELECT  seg.id as attribute_id,  head.`attribute` as attibutename, head.attr_imgurl as attribute_imgurl, head.attribute_translation as attribute_translation, 
        head.datasheet_width as head_datasheet_width, head.datasheet_height as head_datasheet_height,
        subhead.attr_value as attr_value, subhead.datasheet_imgurl as attrvalue_imgurls, 
        subhead.datasheet_width as datasheet_width,subhead.datasheet_height as datasheet_height,
        subhead.attr_value_translation as attr_value_translation  
         FROM taw_attribute_heading as head JOIN taw_attribute_segment as seg ON head.id = seg.attribute_id 
         LEFT JOIN taw_attribute_subheading as subhead ON subhead.attribute_id = head.id
         WHERE head.heading = 'Product Features' AND seg.cate_no = %s ORDER BY attribute_id ASC;",
        $categoryName
    );
    
    $ProductFeatures = $wpdb->get_results($ProductFeaturesQuery, ARRAY_A);

    $technicalsupportquery = $wpdb->prepare(
        "SELECT seg.id as attribute_id, head.`attribute` as attibutename, head.attr_imgurl as attribute_imgurl, head.attribute_translation as attribute_translation, 
        head.datasheet_width as head_datasheet_width, head.datasheet_height as head_datasheet_height,
        subhead.attr_value as attr_value, subhead.datasheet_imgurl as attrvalue_imgurls, 
        subhead.datasheet_width as datasheet_width,subhead.datasheet_height as datasheet_height,
        subhead.attr_value_translation as attr_value_translation  
         FROM taw_attribute_heading as head JOIN taw_attribute_segment as seg ON head.id = seg.attribute_id 
         LEFT JOIN taw_attribute_subheading as subhead ON subhead.attribute_id = head.id
         WHERE head.heading = 'Technical Specification' AND seg.cate_no = %s ORDER BY attribute_id ASC;",
        $categoryName
    );
    
    $technicalsupport = $wpdb->get_results($technicalsupportquery, ARRAY_A);

    $WeightcapacityQuery = $wpdb->prepare(
        "SELECT seg.id as attribute_id, head.`attribute` as attibutename, head.attr_imgurl as attribute_imgurl, head.attribute_translation as attribute_translation, 
        head.datasheet_width as head_datasheet_width, head.datasheet_height as head_datasheet_height,
        subhead.attr_value as attr_value, subhead.datasheet_imgurl as attrvalue_imgurls, 
        subhead.datasheet_width as datasheet_width,subhead.datasheet_height as datasheet_height,
        subhead.attr_value_translation as attr_value_translation  
         FROM taw_attribute_heading as head JOIN taw_attribute_segment as seg ON head.id = seg.attribute_id 
         LEFT JOIN taw_attribute_subheading as subhead ON subhead.attribute_id = head.id
         WHERE head.heading = 'Weight capacity support beam' AND seg.cate_no = %s ORDER BY attribute_id ASC;",
        $categoryName
    );
    
    $Weightcapacitysupport = $wpdb->get_results($WeightcapacityQuery, ARRAY_A);

    $weightvolumesupportquery = $wpdb->prepare(
        "SELECT seg.id as attribute_id, head.`attribute` as attibutename, head.attr_imgurl as attribute_imgurl, head.attribute_translation as attribute_translation, 
        head.datasheet_width as head_datasheet_width, head.datasheet_height as head_datasheet_height,
        subhead.attr_value as attr_value, subhead.datasheet_imgurl as attrvalue_imgurls, 
        subhead.datasheet_width as datasheet_width,subhead.datasheet_height as datasheet_height,
        subhead.attr_value_translation as attr_value_translation  
         FROM taw_attribute_heading as head JOIN taw_attribute_segment as seg ON head.id = seg.attribute_id 
         LEFT JOIN taw_attribute_subheading as subhead ON subhead.attribute_id = head.id
         WHERE head.heading = 'Weight and volume' AND seg.cate_no = %s ORDER BY attribute_id ASC;",
        $categoryName
    );
    
    $weightvolumesupport = $wpdb->get_results($weightvolumesupportquery, ARRAY_A);
}      
        
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

$category = ''; // Initialize an empty string
if (isset($objchildcate_result) && !empty($objchildcate_result)) {
    $category = $objchildcate_result[0]->name;
} elseif (isset($objparentcate_result) && !empty($objparentcate_result)) {
    $category = $objparentcate_result[0]->name;
}

//   print_r($category);
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
        $ProductFeatureskeysToCheck = array_column($ProductFeatures, 'attibutename');
        
        $ProductFeaturesmatchFound = false;
        // Check dynamically if any key exists in $formatted_attributes
        foreach ($ProductFeatureskeysToCheck as $key) {
            if (isset($cleaned_result[$key])) {
                $ProductFeaturesmatchFound = true;
                break;
            }
        }
        if ($ProductFeaturesmatchFound) : ?>

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
        $divHeight = 'height: 95px;';
        $paddingbottom='padding-bottom:0px;';
        $imgheight='height:25px;';

        if (isset($cleaned_result) && is_array($cleaned_result)) 
        {
            if (isset($cleaned_result['Load metod'])) {
            $loadMethodValues = $cleaned_result['Load metod'];
            $loadmethodvaluesArray = explode('| ', $loadMethodValues);
            $loadmetodcount = count($loadmethodvaluesArray);
            }
            if (isset($cleaned_result['Picking Method'])) {
            $picking = $cleaned_result['Picking Method'];
            $pickingvalues = explode('| ', $picking);
            $pickingcount = count($pickingvalues);
            }
            if (isset($cleaned_result['Type of load'])) {
            $typeofloadValues = $cleaned_result['Type of load'];
            $typeofloadValuesArray = explode('| ', $typeofloadValues);
            $typeofloadcount = count($typeofloadValuesArray);
            }
        
            if ($loadmetodcount >= 4 || $typeofloadcount >= 4) {
                $divHeight = 'height: 105px;';
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

        $productcategory = $product->get_categories();
        
        if ((strpos($productcategory, "Tailor-made product") === false)) :
            if($lang=='en')
            {
                $attributesToDisplay = [];
                foreach ($ProductFeatures as $spec) {
                    $attributeName = $spec['attibutename']; // Main attribute name
                    $attributeImgUrl = $spec['attribute_imgurl']; // Head-level image
                    $attrValue = $spec['attr_value'];       // Attribute value
                    $imgUrl = $spec['attrvalue_imgurls'];  // Image URL for the attribute value
                    $subwidth = $spec['datasheet_width'];  // Image URL for the attribute value
                    $subheight = $spec['datasheet_height'];  // Image URL for the attribute value
                    $headwidth = $spec['head_datasheet_width'];  // Image URL for the attribute value
                    $headheight = $spec['head_datasheet_height'];  // Image URL for the attribute value
                    
                
                    // Ensure the structure exists for the attribute name
                    if (!isset($attributesToDisplay[$attributeName])) {
                        $attributesToDisplay[$attributeName] = [
                            'label' => strtoupper($attributeName), // Convert label to uppercase
                            'image_urls' => [], // Initialize an empty array for image_urls
                            'image_url' =>  $attributeImgUrl,
                            'width' => $headwidth . 'px',
                            'height' => $headheight . 'px',
                        ];
                    }
                
                    // Add the specific attribute value and its associated image URL
                    $attributesToDisplay[$attributeName]['image_urls'][$attrValue] = [
                        'url' => $imgUrl,
                        'width' => $subwidth . 'px',
                        'height' => $subheight . 'px',
                    ];
                }
            }elseif($lang=='sv')
            {
                $attributesToDisplay = [];
                foreach ($ProductFeatures as $spec) {
                $attributeName = $spec['attibutename']; // Main attribute name 
                $attributetranslation = $spec['attribute_translation'];
                $attributeImgUrl = $spec['attribute_imgurl']; // Head-level image
                $attrValuetranslation = $spec['attr_value_translation'];       // Attribute value
                $imgUrl = $spec['attrvalue_imgurls'];  // Image URL for the attribute value
                $subwidth = $spec['datasheet_width'];  // Image URL for the attribute value
                $subheight = $spec['datasheet_height'];  // Image URL for the attribute value
                $headwidth = $spec['head_datasheet_width'];  // Image URL for the attribute value
                $headheight = $spec['head_datasheet_height'];
            
                // Ensure the structure exists for the attribute name
                if (!isset($attributesToDisplay[$attributeName])) {
                    $attributesToDisplay[$attributeName] = [
                        'label' => $attributetranslation, // Convert label to uppercase
                        'image_urls' => [], // Initialize an empty array for image_urls
                        'image_url' =>  $attributeImgUrl,
                        'width' => $headwidth . 'px',
                        'height' => $headheight . 'px',
                    ];
                }
            
                // Add the specific attribute value and its associated image URL
                $attributesToDisplay[$attributeName]['image_urls'][$attrValuetranslation] = [
                    'url' => $imgUrl,
                    'width' => $subwidth . 'px',
                    'height' => $subheight . 'px',
                ];
                }
            }       
            foreach ($attributesToDisplay as $attributeKey => $attributeData) {
                if (isset($cleaned_result[$attributeKey])) {

                    echo '<div style="width: 9.5%; align-items: center; padding: 2px; display: inline-block; box-sizing: border-box; margin: 5px 23px; ' . $divHeight . '">';
                    echo '<div style="width: 106px; border: 1px solid; padding: 2px; display: flex; align-items: center; margin-bottom: -1px; border-color: #374151; display: flex; justify-content: center; align-items: center; position: relative; ' . $divHeight . ';">';
        ?>
                    <span style="position: absolute;font-family: 'Poppins', sans-serif; top: 5px; left: 0; right: 0; text-align: center; font-size: 8px; color: black; font-weight:bold; display: flex; justify-content: center; width: 100%; height: 5px;">
                        <?php echo $attributeData['label']; ?>
                    </span>
                    <div style="<?php echo $imgheight; ?>; text-align: center; padding-top: 30px;  display: flex; padding-left:10px; justify-content: center; margin-top: 5px; align-items: center;">
                        <?php
                        if (!empty($attributeData['image_urls'])) {
                            // $extensions = explode(' | ', strtolower($cleaned_result[$attributeKey]));
                            $extensions = array_map(
                                function ($extension) {
                                    return str_replace(['×', '&amp;'], ['x', '&'], trim(mb_strtolower($extension, 'UTF-8')));
                                },
                                explode(' | ', $cleaned_result[$attributeKey])
                            );
                            $imageUrlsNormalized = array_change_key_case($attributeData['image_urls'], CASE_LOWER);
                            $imageUrlsNormalized = array_combine(
                                array_map(function ($key) {
                                    return str_replace(['×', '&amp;'], ['x', '&'], strtolower($key));
                                }, array_keys($imageUrlsNormalized)),
                                array_values($imageUrlsNormalized)
                            );

                            $sortedImages = []; // Array to store sorted images
                            $hasImages = false;

                            foreach ($extensions as $extension) {
                                if (isset($imageUrlsNormalized[$extension])) {
                                    $data = $imageUrlsNormalized[$extension];
                                    if (!empty($data['url'])) {
                                        $sortedImages[] = [
                                            'url' => $data['url'], // Direct URL usage
                                            'width' => $data['width'] ?? '30px',
                                            'height' => $data['height'] ?? '30px',
                                        ];
                                    }
                                }
                            }

                            // Render the sorted images
                            foreach ($sortedImages as $image) {
                                $image_url =  $image['url'];
                                $type = pathinfo($image_url, PATHINFO_EXTENSION);
                                $image_data = file_get_contents($image_url);
                                $base64 = 'data:image/' . $type . ';base64,' . base64_encode($image_data);
                                echo '<img style="display: block;  width: ' . $image['width'] . ';" src="' . $base64 . '" alt="Manual" />';
                                $hasImages = true;
                            }
                        
                            // If no images are rendered and a fallback image is available, display it
                            if (!$hasImages && !empty($attributeData['image_url'])) {
                                $image_url = $attributeData['image_url'];
                                $type = pathinfo($image_url, PATHINFO_EXTENSION);
                                $image_data = file_get_contents($image_url);
                                $base64 = 'data:image/' . $type . ';base64,' . base64_encode($image_data);
                                ?>
                                <img style="display: block; width: <?php echo $attributeData['width']; ?>; " src="<?php echo $base64; ?>" alt="Manual" />
                                <?php
                            }
                            echo '<pre>';
                        } elseif (!empty($attributeData['image_url'])) {
                            $image_url = $attributeData['image_url'];
                            $type = pathinfo($image_url, PATHINFO_EXTENSION);
                            $image_data = file_get_contents($image_url);
                            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($image_data);
                            ?>
                            <img style="display: block; width: <?php echo $attributeData['width']; ?>; " src="<?php echo $base64; ?>" alt="Manual" />
                            <?php
                        }
                        ?>
                        
                    </div>
                    <span style="position: absolute; font-family: 'Poppins', sans-serif; left: 0; right: 0; text-align: center; font-size: 9px; color: black; font-weight: normal; display: flex; justify-content: center; width: 100%; padding-bottom: 15px; height: 5px;">
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
$technicalkeysToCheck = array_column($technicalsupport, 'attibutename');
$technicalsupportmatchFound = false;
// Check dynamically if any key exists in $formatted_attributes
foreach ($technicalkeysToCheck as $key) {
    if (isset($cleaned_result[$key])) {
        $technicalsupportmatchFound = true;
        break;
    }
}
$WeightcapacitykeysToCheck = array_column($Weightcapacitysupport, 'attibutename');
$WeightcapacitysupportmatchFound = false;
// Check dynamically if any key exists in $formatted_attributes
foreach ($WeightcapacitykeysToCheck as $key) {
    if (isset($cleaned_result[$key])) {
        $WeightcapacitysupportmatchFound = true;
        break;
    }
}

$weightvolumekeysToCheck = array_column($weightvolumesupport, 'attibutename');
$weightvolumesupportmatchFound = false;
// Check dynamically if any key exists in $formatted_attributes
foreach ($weightvolumekeysToCheck as $key) {
    if (isset($cleaned_result[$key])) {
        $weightvolumesupportmatchFound = true;
        break;
    }
} 

if (!empty($meta['article_price']['product_diagram_file']['url']) ||   !empty($meta['article_price']['product_diagram_file2']['url']) ||   !empty($meta['article_price']['product_diagram_file3']['url'])||
    ($technicalsupportmatchFound) || ($WeightcapacitysupportmatchFound) || ($weightvolumesupportmatchFound)) :?>

    <?php //if  (isset($meta['article_price']['product_diagram_file']['url']) && !empty($meta['article_price']['product_diagram_file']['url'])) {   ?>  
    <div style="display:flex; height:972px;">
    <?php  //}else{ ?>
        <!-- <div style="display:flex; height:972px;"> -->
    <?php //} ?>
    <?php if (!empty($meta['article_price']['product_diagram_file']['url'])) {  ?>
        <div style="height: 680px;">
    <?php }elseif (empty($meta['article_price']['product_diagram_file']['url'])) {  ?>
        <div style="height: 650px;">
    <?php } ?>   
                <?php if (isset($meta['article_price']['product_diagram_file']['url']) && !empty($meta['article_price']['product_diagram_file']['url'])) {    
                    $diagram1 = $meta['article_price']['product_diagram_file']['url']; ?>
                    <div class="diagram-container" style="margin-left: 30px; height: 520px; overflow: hidden; margin-top: -40px;">
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
                <?php if (empty($meta['article_price']['product_diagram_file']['url'])) {  ?>
                <div style="width:600px; height: 250px;  margin-top:10px; padding-bottom:30px;margin-top: -20px;">
                <?php }elseif (!empty($meta['article_price']['product_diagram_file']['url'])) {  ?>
                <div style="width:600px; height: 250px;  margin-top:10px; padding-bottom:30px;">
                <?php } ?>   
                        <?php if (isset($meta['article_price']['product_diagram_file2']['url']) && !empty($meta['article_price']['product_diagram_file2']['url'])) {    
                                $diagram2 = $meta['article_price']['product_diagram_file2']['url']; ?>
                            <div style="height: 175px; float:left; margin-left: 30px;">
                                    <?php
                                    $type = pathinfo($diagram2, PATHINFO_EXTENSION);
                                    $image_data = file_get_contents($diagram2);
                                    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($image_data);
                                    // echo '<img src="' . $base64 . '" alt="Manual" 
                                    // style="margin-bottom:9px; margin-left:50px; margin-right:10px; width:500px; height:300px;">';
                                    echo '<img src="' . $base64 . '" alt="Manual" style="margin-bottom:2px; margin-top:2px; max-width:460px; height: 100%;">';  
                                    ?>                
                            </div>
                        <?php } ?>
                        <?php if (isset($meta['article_price']['product_diagram_file3']['url']) && !empty($meta['article_price']['product_diagram_file3']['url'])) { ?>
                            <?php if (empty($meta['article_price']['product_diagram_file2']['url'])) {  ?>
                                <div style=" height: 175px;margin-left: 50px; float:left; "> 
                                    <?php
                                    $diagram3 = $meta['article_price']['product_diagram_file3']['url'];
                                    $type = pathinfo($diagram3, PATHINFO_EXTENSION);
                                    $image_data = file_get_contents($diagram3);
                                    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($image_data);
                                    // echo '<img src="' . $base64 . '" alt="Manual" 
                                    // style="margin-bottom:9px; margin-left:50px; margin-right:10px; width:500px; height:300px;">';
                                    echo '<img src="' . $base64 . '" alt="Manual" style="margin-bottom:2px; margin-top:2px;  max-width:160px;  height: 100%;">';  
                                    ?> 
                                </div>
                                <?php }elseif (!empty($meta['article_price']['product_diagram_file2']['url'])){?> 
                                <div style=" height: 175px; margin-left:10px; float:left; "> 
                                    <?php
                                    $diagram3 = $meta['article_price']['product_diagram_file3']['url'];
                                    $type = pathinfo($diagram3, PATHINFO_EXTENSION);
                                    $image_data = file_get_contents($diagram3);
                                    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($image_data);
                                    // echo '<img src="' . $base64 . '" alt="Manual" 
                                    // style="margin-bottom:9px; margin-left:50px; margin-right:10px; width:500px; height:300px;">';
                                    echo '<img src="' . $base64 . '" alt="Manual" style="margin-bottom:2px; margin-top:2px;  max-width:160px;  height: 100%;">';  
                                    ?>  
                                </div>
                                <?php } ?>
                        <?php } ?>
                </div>
                </div>
            <br>
            <br>
        
        <br>
        <?php if ((strpos($productcategory, "Tailor-made product") === false)) : ?>
            <?php if($technicalsupportmatchFound) : ?>
            <?php
            if (($technicalsupportmatchFound) && ($WeightcapacitysupportmatchFound || $weightvolumesupportmatchFound)) : ?>

            <div style="height:145px; margin-left: -35px; ">
            <?php
            elseif (($technicalsupportmatchFound) && (!$WeightcapacitysupportmatchFound || !$weightvolumesupportmatchFound)) : ?>
            <div style="height:145px; margin-left: -35px; margin-top:140px;">
            <?php endif; ?>
                <div style="width:900px; padding-bottom:10px;">
                    <span style="font-size:14px; font-family: 'Poppins', sans-serif; font-weight:bold;  margin-left:50px;">
                        <?php if ($lang == "en") { ?> TECHNICAL SPECIFICATIONS <?php } elseif ($lang == "sv") { ?> Teknisk specifikation <?php } ?>
                    </span>
                </div>

                <br>
                <?php

                $divHeight = 'height: 90px;';

                if($lang=='en')
                    {
                        $attributesToDisplay = [];
                        foreach ($technicalsupport as $spec) {
                            $attributeName = $spec['attibutename']; // Main attribute name
                            $attributeImgUrl = $spec['attribute_imgurl']; // Head-level image
                            $attrValue = $spec['attr_value'];       // Attribute value
                            $imgUrl = $spec['attrvalue_imgurls'];  // Image URL for the attribute value
                            $subwidth = $spec['datasheet_width'];  // Image URL for the attribute value
                            $subheight = $spec['datasheet_height'];  // Image URL for the attribute value
                            $headwidth = $spec['head_datasheet_width'];  // Image URL for the attribute value
                            $headheight = $spec['head_datasheet_height'];
                        
                            // Ensure the structure exists for the attribute name
                            if (!isset($attributesToDisplay[$attributeName])) {
                                $attributesToDisplay[$attributeName] = [
                                    'label' => strtoupper($attributeName), // Convert label to uppercase
                                    'image_urls' => [], // Initialize an empty array for image_urls
                                    'image_url' =>  $attributeImgUrl,
                                    'width' => $headwidth . 'px',
                                    'height' => $headheight . 'px',
                                ];
                            }
                        
                            // Add the specific attribute value and its associated image URL
                            $attributesToDisplay[$attributeName]['image_urls'][$attrValue] = [
                                'url' => $imgUrl,
                                'width' => $subwidth . 'px',
                                'height' => $subheight . 'px',
                            ];
                        }
                    }elseif($lang=='sv')
                    {
                        $attributesToDisplay = [];
                        foreach ($technicalsupport as $spec) {
                        $attributeName = $spec['attibutename']; // Main attribute name 
                        $attributetranslation = $spec['attribute_translation'];
                        $attributeImgUrl = $spec['attribute_imgurl']; // Head-level image
                        $attrValuetranslation = $spec['attr_value_translation'];       // Attribute value
                        $imgUrl = $spec['attrvalue_imgurls'];  // Image URL for the attribute value
                        $subwidth = $spec['datasheet_width'];  // Image URL for the attribute value
                        $subheight = $spec['datasheet_height'];  // Image URL for the attribute value
                        $headwidth = $spec['head_datasheet_width'];  // Image URL for the attribute value
                        $headheight = $spec['head_datasheet_height'];
                    
                        // Ensure the structure exists for the attribute name
                        if (!isset($attributesToDisplay[$attributeName])) {
                            $attributesToDisplay[$attributeName] = [
                                'label' => $attributetranslation, // Convert label to uppercase
                                'image_urls' => [], // Initialize an empty array for image_urls
                                'image_url' =>  $attributeImgUrl,
                                'width' => $headwidth . 'px',
                                'height' => $headheight . 'px',
                            ];
                        }
                    
                        // Add the specific attribute value and its associated image URL
                        $attributesToDisplay[$attributeName]['image_urls'][$attrValuetranslation] = [
                            'url' => $imgUrl,
                            'width' => $subwidth . 'px',
                            'height' => $subheight . 'px',
                        ];
                        }
                    }
                $current_index = 0; 
                $max_display_count = 11; // Maximum number of attributes to display in one row
                $attributes_count = count($attributesToDisplay); // Count total attributes

                foreach ($attributesToDisplay as $attributeKey => $attributeData) {

                    if ($current_index >= $max_display_count) {
                        break; // Stop the loop if we have displayed 11 attributes
                    }

                    if (isset($cleaned_result[$attributeKey])) {

                        echo '<div style="width:1.65%; align-items: center;  display: inline-block; box-sizing: border-box; margin-left:52px; ' . $divHeight . '">';
                        echo '<div style="width: 100%; border: 1px solid; display: flex; align-items: center; margin-bottom: -1px; padding-left: 23px; padding-right: 23px; border-color: #374151; display: flex; justify-content: center; align-items: center; position: relative; ' . $divHeight . ';">';
                ?>
                        <span style="position: absolute;font-family: 'Poppins', sans-serif; top: 5px; left: 0; right: 0; text-align: center; font-size: 8px; color: black; font-weight:bold; display: flex; justify-content: center; width: 100%; height: 10px;">
                            <?php echo $attributeData['label']; ?>
                        </span>
                        <div style="height: 35px; text-align: center;  padding-top:30px;  display: flex; justify-content: center;margin-top: 5px;  align-items: center;">
                            <?php
                                if (!empty($attributeData['image_urls'])) {
                                    $extensions = explode(' | ', strtolower($cleaned_result[$attributeKey]));
                                    $hasImages = false;
                                    foreach ($attributeData['image_urls'] as $percentage => $data) {
                                        foreach ($extensions as $extension) {
                                            if (strtolower(trim($extension)) == strtolower(trim($percentage))) {
                                                $type = pathinfo($data['url'], PATHINFO_EXTENSION);
                                                $image_data = file_get_contents($data['url']);
                                                $base64 = 'data:image/' . $type . ';base64,' . base64_encode($image_data);
                                                ?>
                                                <img style="<?php echo $paddingbottom; ?> ; display: block; width: <?php echo $data['width']; ?> ; height: <?php echo $data['height']; ?>" src="<?php echo $base64; ?>" alt="Manual" />
                                                <?php
                                                $hasImages = true;
                                            }
                                        }
                                    }

                                    if (!$hasImages && !empty($attributeData['image_url'])) {
                                        $image_url = $attributeData['image_url'];
                                        $type = pathinfo($image_url, PATHINFO_EXTENSION);
                                        $image_data = file_get_contents($image_url);
                                        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($image_data);?>
                                        <img style=" display: block; width: <?php echo $attributeData['width']; ?>; height: <?php echo $attributeData['height']; ?>" src="<?php echo $base64; ?>" alt="Manual" />
                                <?php }

                                }elseif (!empty($attributeData['image_url'])) {
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
                        <span style="position: absolute;font-family: 'Poppins', sans-serif; left: 0; right: 0; text-align: center; font-size: 8px; color: black; font-weight: normal; display: flex; justify-content: center; width: 100%;  height: 5px;">
                            <?php
                            $keyvalue = $cleaned_result[$attributeKey];
                            echo $keyvalue;
                            ?>
                        </span>

                <?php
                        echo '</div></div>';
                        $current_index++;
                    }
                } 
                ?>
            </div>
        <?php endif; ?>
        <?php endif; ?>
        <?php if ((strpos($productcategory, "Tailor-made product") === false)) : ?>
        <?php
            if (($weightvolumesupportmatchFound) || ($WeightcapacitysupportmatchFound)) :?>
            <?php
            if (($technicalsupportmatchFound) && (($weightvolumesupportmatchFound) || ($WeightcapacitysupportmatchFound))): ?>
                <?php
                if ((in_array('custom_uam_reseller_eur', $user_roles) || in_array('custom_uam_reseller_sek', $user_roles)) &&  (!empty($reseller_color) && isset($reseller_color))){ ?>
                    <span style=" margin-left:16px; width: 700px; margin-top: 0px; margin-bottom: 10px; display: flex;border: 1px solid <?php echo esc_attr($reseller_color); ?>; opacity: 1; "></span>
                <?php }else{ ?>
                    <span style=" margin-left:16px; width: 700px; margin-top: 0px; margin-bottom: 10px; display: flex;border: 1px solid #CC071D;opacity: 1; "></span>
                <?php } ?>
                <?php
                 elseif ((!$technicalsupportmatchFound) && (($weightvolumesupportmatchFound) || ($WeightcapacitysupportmatchFound))): ?>  
            <?php
                if ((in_array('custom_uam_reseller_eur', $user_roles) || in_array('custom_uam_reseller_sek', $user_roles)) &&  (!empty($reseller_color) && isset($reseller_color))){ ?>
                    <span style=" margin-left:16px; width: 700px;margin-top: 140px; margin-bottom: 10px; display: flex;border: 1px solid <?php echo esc_attr($reseller_color); ?>; opacity: 1; "></span>
                <?php }else{ ?>
                    <span style=" margin-left:16px; width: 700px;margin-top: 140px; margin-bottom: 10px; display: flex;border: 1px solid #CC071D;opacity: 1; "></span>
                <?php } ?>                    
	    <?php endif; ?>
 <?php endif; ?>
        <?php
         if (($weightvolumesupportmatchFound) || ($WeightcapacitysupportmatchFound)): ?>
            
        <div style="height:105px; margin-left: -35px; ">
            <?php
            if ($weightvolumesupportmatchFound) : ?>
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

                    if($lang=='en')
                    {
                        $attributesToDisplay = [];
                        foreach ($weightvolumesupport as $spec) {
                            $attributeName = $spec['attibutename']; // Main attribute name
                            $attributeImgUrl = $spec['attribute_imgurl']; // Head-level image
                            $attrValue = $spec['attr_value'];       // Attribute value
                            $imgUrl = $spec['attrvalue_imgurls'];  // Image URL for the attribute value
                            $subwidth = $spec['datasheet_width'];  // Image URL for the attribute value
                            $subheight = $spec['datasheet_height'];  // Image URL for the attribute value
                            $headwidth = $spec['head_datasheet_width'];  // Image URL for the attribute value
                            $headheight = $spec['head_datasheet_height'];
                        
                            // Ensure the structure exists for the attribute name
                            if (!isset($attributesToDisplay[$attributeName])) {
                                $attributesToDisplay[$attributeName] = [
                                    'label' => strtoupper($attributeName), // Convert label to uppercase
                                    'image_urls' => [], // Initialize an empty array for image_urls
                                    'image_url' =>  $attributeImgUrl,
                                    'width' => $headwidth . 'px',
                                    'height' => $headheight . 'px',
                                ];
                            }
                        
                            // Add the specific attribute value and its associated image URL
                            $attributesToDisplay[$attributeName]['image_urls'][$attrValue] = [
                                'url' => $imgUrl,
                                'width' => $subwidth . 'px',
                                'height' => $subheight . 'px',
                            ];
                        }
                    }elseif($lang=='sv')
                    {
                        $attributesToDisplay = [];
                        foreach ($weightvolumesupport as $spec) {
                        $attributeName = $spec['attibutename']; // Main attribute name 
                        $attributetranslation = $spec['attribute_translation'];
                        $attributeImgUrl = $spec['attribute_imgurl']; // Head-level image
                        $attrValuetranslation = $spec['attr_value_translation'];       // Attribute value
                        $imgUrl = $spec['attrvalue_imgurls'];  // Image URL for the attribute value
                        $subwidth = $spec['datasheet_width'];  // Image URL for the attribute value
                        $subheight = $spec['datasheet_height'];  // Image URL for the attribute value
                        $headwidth = $spec['head_datasheet_width'];  // Image URL for the attribute value
                        $headheight = $spec['head_datasheet_height'];
                    
                        // Ensure the structure exists for the attribute name
                        if (!isset($attributesToDisplay[$attributeName])) {
                            $attributesToDisplay[$attributeName] = [
                                'label' => $attributetranslation, // Convert label to uppercase
                                'image_urls' => [], // Initialize an empty array for image_urls
                                'image_url' =>  $attributeImgUrl,
                                'width' => $headwidth . 'px',
                                'height' => $headheight . 'px',
                            ];
                        }
                    
                        // Add the specific attribute value and its associated image URL
                        $attributesToDisplay[$attributeName]['image_urls'][$attrValuetranslation] = [
                            'url' => $imgUrl,
                            'width' => $subwidth . 'px',
                            'height' => $subheight . 'px',
                        ];
                        }
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
                            <span style="position: absolute;font-family: 'Poppins', sans-serif; top: 5px; left: 0; right: 0; text-align: center; font-size: 8px; color: black; font-weight:bold; display: flex; justify-content: center; width: 100%; margin-bottom: 5px; padding-bottom:10px; height: 35px;">
                                <?php echo $attributeData['label']; ?>
                            </span>
                            <div style="height: 35px; text-align: center; padding-top: 30px;  display: flex; justify-content: center; margin-top: 10px; align-items: center;">
                            <?php
                                if (!empty($attributeData['image_urls'])) {
                                    $extensions = explode(' | ', strtolower($cleaned_result[$attributeKey]));
                                    $hasImages = false;
                                    foreach ($attributeData['image_urls'] as $percentage => $data) {
                                        foreach ($extensions as $extension) {
                                            if (strtolower(trim($extension)) == strtolower(trim($percentage))) {
                                                $type = pathinfo($data['url'], PATHINFO_EXTENSION);
                                                $image_data = file_get_contents($data['url']);
                                                $base64 = 'data:image/' . $type . ';base64,' . base64_encode($image_data);
                                                ?>
                                                <img style="<?php echo $paddingbottom; ?> ; display: block; width: <?php echo $data['width']; ?> ; height: <?php echo $data['height']; ?>" src="<?php echo $base64; ?>" alt="Manual" />
                                                <?php
                                                $hasImages = true;
                                            }
                                        }
                                    }

                                    if (!$hasImages && !empty($attributeData['image_url'])) {
                                        $image_url = $attributeData['image_url'];
                                        $type = pathinfo($image_url, PATHINFO_EXTENSION);
                                        $image_data = file_get_contents($image_url);
                                        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($image_data);?>
                                        <img style=" display: block; width: <?php echo $attributeData['width']; ?>; height: <?php echo $attributeData['height']; ?>" src="<?php echo $base64; ?>" alt="Manual" />
                                <?php }

                                }elseif (!empty($attributeData['image_url'])) {
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
                            <span style="position: absolute;font-family: 'Poppins', sans-serif; padding-bottom:2px; left: 0; right: 0; text-align: center; font-size: 8px; color: black; font-weight: normal; display: flex; justify-content: center; width: 100%; height: 5px;">
                                <?php
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
                if ($WeightcapacitysupportmatchFound): ?>
                <?php
                if ($weightvolumesupportmatchFound) : ?>
                <div  style="width:800px; float: left; margin-left:-150px;">
                <?php
                elseif (!$weightvolumesupportmatchFound): ?>
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

                    if($lang=='en')
                    {
                        $attributesToDisplay = [];
                        foreach ($Weightcapacitysupport as $spec) {
                            $attributeName = $spec['attibutename']; // Main attribute name
                            $attributeImgUrl = $spec['attribute_imgurl']; // Head-level image
                            $attrValue = $spec['attr_value'];       // Attribute value
                            $imgUrl = $spec['attrvalue_imgurls'];  // Image URL for the attribute value
                            $subwidth = $spec['datasheet_width'];  // Image URL for the attribute value
                            $subheight = $spec['datasheet_height'];  // Image URL for the attribute value
                            $headwidth = $spec['head_datasheet_width'];  // Image URL for the attribute value
                            $headheight = $spec['head_datasheet_height'];
                        
                            // Ensure the structure exists for the attribute name
                            if (!isset($attributesToDisplay[$attributeName])) {
                                $attributesToDisplay[$attributeName] = [
                                    'label' => strtoupper($attributeName), // Convert label to uppercase
                                    'image_urls' => [], // Initialize an empty array for image_urls
                                    'image_url' =>  $attributeImgUrl,
                                    'width' => $headwidth . 'px',
                                    'height' => $headheight . 'px',
                                ];
                            }
                        
                            // Add the specific attribute value and its associated image URL
                            $attributesToDisplay[$attributeName]['image_urls'][$attrValue] = [
                                'url' => $imgUrl,
                                'width' => $subwidth . 'px',
                                'height' => $subheight . 'px',
                            ];
                        }
                    }elseif($lang=='sv')
                    {
                        $attributesToDisplay = [];
                        foreach ($Weightcapacitysupport as $spec) {
                        $attributeName = $spec['attibutename']; // Main attribute name 
                        $attributetranslation = $spec['attribute_translation'];
                        $attributeImgUrl = $spec['attribute_imgurl']; // Head-level image
                        $attrValuetranslation = $spec['attr_value_translation'];       // Attribute value
                        $imgUrl = $spec['attrvalue_imgurls'];  // Image URL for the attribute value
                        $subwidth = $spec['datasheet_width'];  // Image URL for the attribute value
                        $subheight = $spec['datasheet_height'];  // Image URL for the attribute value
                        $headwidth = $spec['head_datasheet_width'];  // Image URL for the attribute value
                        $headheight = $spec['head_datasheet_height'];
                    
                        // Ensure the structure exists for the attribute name
                        if (!isset($attributesToDisplay[$attributeName])) {
                            $attributesToDisplay[$attributeName] = [
                                'label' => $attributetranslation, // Convert label to uppercase
                                'image_urls' => [], // Initialize an empty array for image_urls
                                'image_url' =>  $attributeImgUrl,
                                'width' => $headwidth . 'px',
                                'height' => $headheight . 'px',
                            ];
                        }
                    
                        // Add the specific attribute value and its associated image URL
                        $attributesToDisplay[$attributeName]['image_urls'][$attrValuetranslation] = [
                            'url' => $imgUrl,
                            'width' => $subwidth . 'px',
                            'height' => $subheight . 'px',
                        ];
                        }
                    }         
                    foreach ($attributesToDisplay as $attributeKey => $attributeData) {
                        if (isset($cleaned_result[$attributeKey])) {

                            echo '<div style="width: 2.5%; align-items: center;  display: inline-block; box-sizing: border-box; margin-left:55px; ' . $divHeight . '">';
                            echo '<div style="width: 100%; border: 1px solid; display: flex; align-items: center; margin-bottom: -1px; padding-left: 23px; padding-right: 23px; border-color: #374151; display: flex; justify-content: center; align-items: center; position: relative; ' . $divHeight . ';">';
                    ?>
                            <span style="position: absolute;font-family: 'Poppins', sans-serif; top: 5px; left: 0; right: 0; text-align: center; font-size: 8px; color: black; font-weight:bold; display: flex; justify-content: center; width: 100%; margin-bottom: 5px; padding-bottom:10px; height: 35px;">
                                <?php echo $attributeData['label']; ?>
                            </span>
                            <div style="height: 35px; text-align: center; padding-top: 30px;  display: flex; justify-content: center; margin-top: 5px; align-items: center;">
                            <?php
                                if (!empty($attributeData['image_urls'])) {
                                    $extensions = explode(' | ', strtolower($cleaned_result[$attributeKey]));
                                    $hasImages = false;
                                    foreach ($attributeData['image_urls'] as $percentage => $data) {
                                        foreach ($extensions as $extension) {
                                            if (strtolower(trim($extension)) == strtolower(trim($percentage))) {
                                                $type = pathinfo($data['url'], PATHINFO_EXTENSION);
                                                $image_data = file_get_contents($data['url']);
                                                $base64 = 'data:image/' . $type . ';base64,' . base64_encode($image_data);
                                                ?>
                                                <img style="<?php echo $paddingbottom; ?> ; display: block; width: <?php echo $data['width']; ?> ; height: <?php echo $data['height']; ?>" src="<?php echo $base64; ?>" alt="Manual" />
                                                <?php
                                                $hasImages = true;
                                            }
                                        }
                                    }

                                    if (!$hasImages && !empty($attributeData['image_url'])) {
                                        $image_url = $attributeData['image_url'];
                                        $type = pathinfo($image_url, PATHINFO_EXTENSION);
                                        $image_data = file_get_contents($image_url);
                                        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($image_data);?>
                                        <img style=" display: block; width: <?php echo $attributeData['width']; ?>; height: <?php echo $attributeData['height']; ?>" src="<?php echo $base64; ?>" alt="Manual" />
                                <?php }

                                }elseif (!empty($attributeData['image_url'])) {
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
                            <span style="position: absolute;font-family: 'Poppins', sans-serif; padding-bottom:2px; left: 0; right: 0; text-align: center; font-size: 8px; color: black; font-weight: normal; display: flex; justify-content: center; width: 100%; height: 5px;">
                                <?php
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
        <?php
        $image_url =  THINGSATWEB_BASE . '/img/smartstoringlogo.png';
        $type = pathinfo($image_url, PATHINFO_EXTENSION);
        $image_data = file_get_contents($image_url);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($image_data);
        ?>
        <div style="width: 683px;background-color:#4B4C4D;padding-top:2px; padding-bottom:4px;">
            <span style="font-size: 18px;  margin-left:25px; color: white; font-family: 'Poppins', sans-serif; text-align: center; ">
            <?php
        if ((in_array('custom_uam_reseller_eur', $user_roles) || in_array('custom_uam_reseller_sek', $user_roles)) &&  (!empty($reseller_website) && isset($reseller_website))){ ?>
            <a href="https://<?php echo esc_attr($reseller_website); ?>/" style="color: white;margin-left:25px; text-decoration: none;"> <?php echo esc_attr($reseller_website); ?></a>
            <?php }else{ ?>
                <a href="https://smartstoring.eu/" style="color: white;margin-left:25px; text-decoration: none;">smartstoring.eu</a>
            <?php } ?>
            </span>
        </div>

        <?php
        if ((in_array('custom_uam_reseller_eur', $user_roles) || in_array('custom_uam_reseller_sek', $user_roles)) &&  (!empty($reseller_color) && isset($reseller_color))){ ?>
            <span style="width: 683px; font-size: 10px; color: white; font-family: 'Poppins', sans-serif; text-align: center; margin-top:3px;padding-top:9px; padding-bottom:9px;background-color:  <?php echo esc_attr($reseller_color); ?>; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; cursor: pointer; display: flex; justify-content: center;"></span>
        <?php }else{ ?>
            <span style="width: 683px; font-size: 10px; color: white; font-family: 'Poppins', sans-serif; text-align: center; margin-top:3px;padding-top:9px; padding-bottom:9px;background-color: #CC071D; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; cursor: pointer; display: flex; justify-content: center;"></span>
        <?php } ?>
    </div>

    <div style="background-color: #374151; float:left; width: 40px; padding-top:5px; padding-bottom:5px; padding-left:6px; padding-right:6px; margin-left:5px;">
        <span style="font-size: 32px; font-family: 'Poppins', sans-serif; color: white; font-weight:bold; text-align: center;opacity: 1; ">02</span>
    </div>

</div>
<?php endif; ?>