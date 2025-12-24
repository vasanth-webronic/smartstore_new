<?php

/**
 * Product attributes
 *
 * Used by list_attributes() in the products class.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-attributes.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

global $product;
global $wpdb;
global $post;

$lang = getSiteCurrentLang();
defined('ABSPATH') || exit;
if (!function_exists('formatAttribute')) {
function formatAttribute($attribute) {
                                // Check if the value matches the pattern of <number>-<unit> with no space between number and unit (e.g., 199mm-250mm)
                                if (preg_match('/^\d+-(\w+)$/', $attribute, $matches)) {
                                    return str_replace('-', ' ', $attribute); // Replace hyphen with space (e.g., 199-mm -> 199 mm)
                                }

                                // Check if the value is a range like '199 mm-250mm'
                                if (preg_match('/^\d+\s\w+-\d+\s\w+$/', $attribute)) {
                                    return $attribute; // No change if it's a range with space
                                }

                                // Check if the value is a range without spaces like '199mm-250mm'
                                if (preg_match('/^\d+\w+-\d+\w+$/', $attribute)) {
                                    return $attribute; // No change if both values are number+unit without space
                                }

                                // If none of the above, just return the original value
                                return $attribute;
                            }
                          }
$attributes = $product->get_attributes();

foreach ($attributes as $attr => $attr_deets) {

  $attribute_label = wc_attribute_label($attr);

  if (isset($attributes[$attr]) || isset($attributes['pa_' . $attr])) {

    $attribute = isset($attributes[$attr]) ? $attributes[$attr] : $attributes['pa_' . $attr];

    if ($attribute['is_taxonomy']) {

      $formatted_attributes[$attribute_label] = implode(', ', wc_get_product_terms($product->id, $attribute['name'], array('fields' => 'names')));
    } else {

      $formatted_attributes[$attribute_label] = $attribute['value'];
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
if($lang=='en')
{
  $categorylang=$categoryName;

}elseif($lang=='sv'){

  $categorylangquery = "SELECT tsm_terms.name FROM tsm_terms WHERE tsm_terms.term_id =(
                        SELECT tsm_icl_translations.element_id FROM tsm_icl_translations
                        WHERE tsm_icl_translations.trid =(
                        SELECT tsm_icl_translations.trid FROM tsm_terms JOIN tsm_icl_translations ON tsm_terms.term_id = tsm_icl_translations.element_id 
                        WHERE tsm_terms.name = '$categoryName' AND tsm_icl_translations.element_type = 'tax_product_cat' AND tsm_icl_translations.language_code = 'sv'
                        ) AND tsm_icl_translations.language_code = 'en');";
  $categorylang = $wpdb->get_var($categorylangquery);

}
// print_r($categorylang);

$sku_no=$product->get_sku();

$skuquery = $wpdb->prepare(
    "SELECT * FROM taw_attribute_product_segment WHERE art_no = %s",
    $sku_no
);
$skuresult = $wpdb->get_results($skuquery); 

if(!empty($skuresult))
{
    $WeightcapacityQuery = $wpdb->prepare(
        "SELECT  seg.id as attribute_id,  head.`attribute` as attibutename, head.product_imgurl as attribute_imgurl, head.attribute_translation as attribute_translation,
         subhead.attr_value as attr_value, subhead.product_imgurl as attrvalue_imgurls, 
         subhead.product_width as product_width,subhead.product_height as product_height,
         subhead.attr_value_translation as attr_value_translation  
         FROM taw_attribute_heading as head JOIN taw_attribute_product_segment as seg ON head.id = seg.attribute_id 
         LEFT JOIN taw_attribute_subheading as subhead ON subhead.attribute_id = head.id
         WHERE head.heading = 'Weight capacity support beam' AND seg.art_no = %s ORDER BY attribute_id ASC;",
        $sku_no
    );
    
    $Weightcapacitysupport = $wpdb->get_results($WeightcapacityQuery, ARRAY_A);
}else{
    $WeightcapacityQuery = $wpdb->prepare(
        "SELECT seg.id as attribute_id, head.`attribute` as attibutename, head.product_imgurl as attribute_imgurl, head.attribute_translation as attribute_translation, 
        subhead.attr_value as attr_value, subhead.product_imgurl as attrvalue_imgurls, 
        subhead.product_width as product_width,subhead.product_height as product_height,
        subhead.attr_value_translation as attr_value_translation  
         FROM taw_attribute_heading as head JOIN taw_attribute_segment as seg ON head.id = seg.attribute_id 
         LEFT JOIN taw_attribute_subheading as subhead ON subhead.attribute_id = head.id
         WHERE head.heading = 'Weight capacity support beam' AND seg.cate_no = %s ORDER BY attribute_id ASC;",
        $categorylang
    );
    
    $Weightcapacitysupport = $wpdb->get_results($WeightcapacityQuery, ARRAY_A);
}            

  if($lang=='en')
  {
    $attributesToDisplay = [];
    foreach ($Weightcapacitysupport as $spec) {
        $attributeName = $spec['attibutename']; // Main attribute name
        $attributeImgUrl = $spec['attribute_imgurl']; // Head-level image
        $attrValue = $spec['attr_value'];       // Attribute value
        $imgUrl = $spec['attrvalue_imgurls'];  // Image URL for the attribute value
        $subwidth = $spec['product_width'];  // Image URL for the attribute value
        $subheight = $spec['product_height'];  // Image URL for the attribute value
    
        // Ensure the structure exists for the attribute name
        if (!isset($attributesToDisplay[$attributeName])) {
            $attributesToDisplay[$attributeName] = [
                'label' => strtoupper($attributeName), // Convert label to uppercase
                'image_urls' => [], // Initialize an empty array for image_urls
                'image_url' =>  $attributeImgUrl,
                'width' => '30px',
                'height' => '30px',
            ];
        }
    
        // Add the specific attribute value and its associated image URL
        $attributesToDisplay[$attributeName]['image_urls'][$attrValue] = [
            'url' => $imgUrl,
            'width' => $subwidth,  // Default width
            'height' => $subheight, // Default height
        ];
    }
  }elseif($lang=='sv')
  {
    // print_r('Weightcapacitysupport:');
    // print_r($Weightcapacitysupport);
       $attributesToDisplay = [];
       foreach ($Weightcapacitysupport as $spec) {
           $attributeName = $spec['attibutename']; // Main attribute name 
           $attributetranslation = $spec['attribute_translation'];
           $attributeImgUrl = $spec['attribute_imgurl']; // Head-level image
           $attrValuetranslation = $spec['attr_value_translation'];       // Attribute value
           $imgUrl = $spec['attrvalue_imgurls'];  // Image URL for the attribute value
           $subwidth = $spec['product_width'];  // Image URL for the attribute value
           $subheight = $spec['product_height'];  // Image URL for the attribute value
       
           // Ensure the structure exists for the attribute name
           if (!isset($attributesToDisplay[$attributeName])) {
               $attributesToDisplay[$attributeName] = [
                   'label' => $attributetranslation, // Convert label to uppercase
                   'image_urls' => [], // Initialize an empty array for image_urls
                   'image_url' =>  $attributeImgUrl,
                   'width' => '30px',
                   'height' => '30px',
               ];
           }
       
           // Add the specific attribute value and its associated image URL
           $attributesToDisplay[$attributeName]['image_urls'][$attrValuetranslation] = [
               'url' => $imgUrl,
               'width' => $subwidth,  // Default width
               'height' => $subheight, // Default height
           ];
       }
      

  }

  $keysToCheck = array_column($Weightcapacitysupport, 'attibutename');
  $matchFound = false;

  // Check dynamically if any key exists in $formatted_attributes
  foreach ($keysToCheck as $key) {
      if (isset($formatted_attributes[$key])) {
          $matchFound = true;
          break;
      }
  }
?>
<style>
  @media (min-width: 1189px) {
    .attrspace {
      margin-left: 10px;
    }

    .loadspace {
      width: 16.66%;
      height: 185px !important;
    }

  }
  .mobheight {
    height: 50px !important;
  }
</style>
<div class="taw-tab-container">
   <?php if ($matchFound)  { ?>
      <span class="text-[14px] text-black font-normal text-left flex" style="width:100%; padding-top: 7px; padding-bottom: 7px; ">
          <?php if ($lang == "en") { ?> OBS! ONLY ONE UNIT PER SECTION MAY BE EXTRACTED AT A TIME! <?php } 
          elseif ($lang == "sv") { ?> OBS! Bara en enhet kan vara i utdraget läge samtidigt. <?php } ?>
      </span>
      <div class="w-full flex flex-wrap">
        <?php
        foreach ($attributesToDisplay as $attributeKey => $attributeData) {
          if (isset($formatted_attributes[$attributeKey])) 
          { 
            $loadingwidthvalue = explode(', ',$formatted_attributes[$attributeKey]);
            $loadingwidthcount = count($loadingwidthvalue);
            if ($loadingwidthcount >= '1') { ?>
            <div class="w-1/2 loadspace p-1 ">
                <div class="flex-1 border p-2 items-center" >
                    <span class="text-xs font-semibold w-1/3 md:w-2/5">
                      <?php echo strtoupper($attributeData['label']); ?>
                    </span>
                    <div class="flex border p-2 items-center my-1 relative justify-center" style="margin-bottom: -1px; padding-left: 20px; padding-right: 20px; border-color: #C5C5C5;">
                      <?php
                      if (!empty($attributeData['image_urls'])) {
                        // Handle cases where image_urls array is populated
                        $extensions = explode(' | ', strtolower($formatted_attributes[$attributeKey]));
                        $hasImages = false; // Flag to track if any image is rendered from image_urls
                    
                        foreach ($attributeData['image_urls'] as $percentage => $data) {
                          if (!empty($data['url'])) { 
                            foreach ($extensions as $extension) {
                                if (strtolower(trim($extension)) == strtolower(trim($percentage))) {
                                    $oneway_url = $data['url'];
                                    $oneway_width = $data['width'] ?? '30px';
                                    $oneway_height = $data['height'] ?? '30px';
                                    // Display the image for the matching value
                                    echo '<img class="filter-item-image mobheight mx-auto my-0 md:my-4  md:!h-11 w-auto" src="' . $oneway_url . '" alt="Manual" style="width: ' . $oneway_width . 'px !important; height: ' . $oneway_height . 'px !important; margin-bottom: 9px;  border-radius: 4px;">';
                                    $hasImages = true; // Mark that at least one image has been rendered
                                }
                            }
                          }
                        }
                    
                        // If no image from image_urls is rendered, check image_url
                        if (!$hasImages && !empty($attributeData['image_url'])) {
                            $front_url = $attributeData['image_url'];
                            echo '<img class="filter-item-image mobheight mx-auto my-0 md:my-4  md:!h-11 w-auto" src="' . $front_url . '" alt="Manual" style="margin-bottom: 9px;  border-radius: 4px;">';
                        }
                      } elseif (!empty($attributeData['image_url'])) {
                            // Handle cases where only image_url is populated
                            $front_url = $attributeData['image_url'];
                            echo '<img class="filter-item-image mobheight mx-auto my-0 md:my-4  md:!h-11 w-auto" src="' . $front_url . '" alt="Manual" style="margin-bottom: 9px;  border-radius: 4px;">';
                      } 
                      ?>
                    </div>
                    <span class="w-full text-[11px] text-white font-semibold text-center p-1 bg-gray-700  hover:text-clip flex justify-center" style="overflow-wrap: break-word; word-wrap: break-word; hyphens: auto; cursor: pointer; display: inline-block; max-width: 100%;">
                      <?php
                      //  echo $formatted_attributes[$attributeKey];
                       echo formatAttribute($formatted_attributes[$attributeKey]);
                       ?>
                    </span>       
                </div>
            </div>
        <?php }}} ?>
      </div>
    <?php } ?>
</div>