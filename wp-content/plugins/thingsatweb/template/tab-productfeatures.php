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
$lang = getSiteCurrentLang();
global $wpdb;

defined('ABSPATH') || exit;

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

$sku_no=$product->get_sku();

$skuquery = $wpdb->prepare(
    "SELECT * FROM taw_attribute_product_segment WHERE art_no = %s",
    $sku_no
);
$skuresult = $wpdb->get_results($skuquery); 

if(!empty($skuresult))
{
    $ProductFeaturesQuery = $wpdb->prepare(
        "SELECT  seg.id as attribute_id,  head.`attribute` as attibutename, head.product_imgurl as attribute_imgurl, head.attribute_translation as attribute_translation,
         subhead.attr_value as attr_value, subhead.product_imgurl as attrvalue_imgurls, 
         subhead.product_width as product_width,subhead.product_height as product_height,
         subhead.attr_value_translation as attr_value_translation  
         FROM taw_attribute_heading as head JOIN taw_attribute_product_segment as seg ON head.id = seg.attribute_id 
         LEFT JOIN taw_attribute_subheading as subhead ON subhead.attribute_id = head.id
         WHERE head.heading = 'Product Features' AND seg.art_no = %s ORDER BY attribute_id ASC;",
        $sku_no
    );
    
    $ProductFeatures = $wpdb->get_results($ProductFeaturesQuery, ARRAY_A);
}else{
    $ProductFeaturesQuery = $wpdb->prepare(
        "SELECT  seg.id as attribute_id,  head.`attribute` as attibutename, head.product_imgurl as attribute_imgurl, head.attribute_translation as attribute_translation, 
        subhead.attr_value as attr_value, subhead.product_imgurl as attrvalue_imgurls, 
        subhead.product_width as product_width,subhead.product_height as product_height,
        subhead.attr_value_translation as attr_value_translation  
         FROM taw_attribute_heading as head JOIN taw_attribute_segment as seg ON head.id = seg.attribute_id 
         LEFT JOIN taw_attribute_subheading as subhead ON subhead.attribute_id = head.id
         WHERE head.heading = 'Product Features' AND seg.cate_no = %s ORDER BY attribute_id ASC;",
        $categorylang
    );
    
    $ProductFeatures = $wpdb->get_results($ProductFeaturesQuery, ARRAY_A);
}      

  if($lang=='en')
  {
    $attributesToDisplay = [];
    foreach ($ProductFeatures as $spec) {
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
    }}elseif($lang=='sv')
    {
       $attributesToDisplay = [];
       foreach ($ProductFeatures as $spec) {
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
       }}
      $keysToCheck = array_column($ProductFeatures, 'attibutename');
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
    }
  }

  .mobheight {
    height: 50px !important;
  }

  .mobdheight {
    height: 45px !important;
  }

  .mobtypeloadheight {
    height: 30px !important;
  }

  .mobtypeheight {
    height: 40px !important;
  }
  </style>


  <style>
    @media (min-width: 1200px) 
    {
      .widspace {
        width: 16.65%;
      }

    }
    .mobilheight {
      height: 50px !important;
      margin-top: 15px;
    }

    .typeheight {
      height: 30px !important;
      margin-top: 30px;

    }
    .shortlongheight {
      height: 60px !important;
      margin-top: 15px;
    }
  </style>
<div class="taw-tab-container">
  <?php if ($matchFound) { ?>
    <div class="w-full flex flex-wrap">
      <?php foreach ($attributesToDisplay as $attributeKey => $attributeData) {
        if (isset($formatted_attributes[$attributeKey])) {
          $productweightvalue = explode(', ', $formatted_attributes[$attributeKey]);
          $productweightcount = count($productweightvalue);
          if ($productweightcount >= 1) { ?> 
            <div class="w-1/2 flex flex-col border-b-2 widspace p-2">
              <div class="flex border p-2 items-center my-1 relative" style="margin-bottom: -1px; padding-left: 2px; padding-right: 2px; border-color: #AEAEAE; display: flex; justify-content: center; align-items: center;">
                <span class="absolute top-0 left-0 right-0 text-[10px] text-black font-semibold text-center bg-gray-200 flex justify-center" style="width:100%;">
                  <?php echo $attributeData['label']; ?>
                </span>
                <?php
                if (!empty($attributeData['image_urls'])) {
                  $extensions = array_map(
                    function ($extension) {
                      return str_replace(['×', '&amp;'], ['x', '&'], trim(strtolower($extension)));
                    },
                    explode(' | ', $formatted_attributes[$attributeKey])
                  );
                
                  $sortedImages = []; // Array to store sorted images
                  
                  // Normalize the keys of image_urls for case-insensitive matching
                  $imageUrlsNormalized = array_change_key_case($attributeData['image_urls'], CASE_LOWER);
                  
                  // Replace `×` with `x` in the keys of `imageUrlsNormalized`
                  $imageUrlsNormalized = array_combine(
                      array_map(function ($key) {
                        return str_replace(['×', '&amp;'], ['x', '&'], strtolower($key));
                      }, array_keys($imageUrlsNormalized)),
                      array_values($imageUrlsNormalized)
                  );

                  // Sort the images based on the order of extensions
                  foreach ($extensions as $extension) {
                    if (isset($imageUrlsNormalized[$extension])) {
                        $data = $imageUrlsNormalized[$extension];
                        if (!empty($data['url'])) {
                            $sortedImages[] = [
                                'url' => $data['url'],
                                'width' => $data['width'] ?? '30px',
                                'height' => $data['height'] ?? '30px',
                            ];
                        }
                    }
                  }
                  $hasImages = false;

                  // Render the sorted images
                  foreach ($sortedImages as $image) {
                    echo '<div style="height: 50px; display: flex; margin:15px 5px; justify-content: center; align-items: center;">'; // New wrapper div with fixed height
                    echo '<img class="mx-auto mobilheight md:medheight" src="' . $image['url'] . '" alt="Manual" style="width: ' . $image['width'] . 'px !important; max-height: ' . $image['height'] . 'px !important;  margin-top: 12px;  border-radius: 4px; display: block;">';
                    echo '</div>';
                    $hasImages = true;
                  }

                  // If no images are rendered and a fallback image is available, display it
                  if (!$hasImages && !empty($attributeData['image_url'])) {
                    $front_url = $attributeData['image_url'];
                    echo '<div style="height: 50px; display: flex; margin:15px 5px; justify-content: center; align-items: center;">'; // New wrapper div with fixed height
                    echo '<img class="mx-auto mobilheight md:medheight w-auto" src="' . $front_url . '" alt="Manual" style="max-height: 50px;  margin-top: 12px;  border-radius: 4px; display: block;">';
                    echo '</div>';
                  }
                } elseif (!empty($attributeData['image_url'])) {
                  $front_url = $attributeData['image_url'];
                  echo '<div style="height: 50px; display: flex; margin:15px 5px; justify-content: center; align-items: center;">'; // New wrapper div with fixed height
                  echo '<img class="mx-auto mobilheight md:medheight w-auto" src="' . $front_url . '" alt="Manual" style="max-height: 50px;  margin-top: 12px;  border-radius: 4px; display: block;">';
                  echo '</div>';
                }
                ?>
              </div>
              <span class="w-full text-[12px] flex-grow items-center flex justify-center text-white font-semibold text-center p-1 bg-gray-700 hover:text-clip flex justify-center" style="overflow-wrap: break-word; word-wrap: break-word; hyphens: auto; cursor: pointer;  max-width: 100%;">
                <?php echo $formatted_attributes[$attributeKey]; ?>
              </span>
            </div>
          <?php } ?>
        <?php } ?>
      <?php } ?>
    </div>
  <?php } ?>
</div>