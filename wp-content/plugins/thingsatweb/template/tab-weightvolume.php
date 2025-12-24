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

$sku_no=$product->get_sku();

$skuquery = $wpdb->prepare(
    "SELECT * FROM taw_attribute_product_segment WHERE art_no = %s",
    $sku_no
);
$skuresult = $wpdb->get_results($skuquery); 

if(!empty($skuresult))
{
    $weightvolumesupportquery = $wpdb->prepare(
        "SELECT  seg.id as attribute_id,  head.`attribute` as attibutename, head.product_imgurl as attribute_imgurl, head.attribute_translation as attribute_translation,
         subhead.attr_value as attr_value, subhead.product_imgurl as attrvalue_imgurls, 
         subhead.product_width as product_width,subhead.product_height as product_height,
         subhead.attr_value_translation as attr_value_translation  
         FROM taw_attribute_heading as head JOIN taw_attribute_product_segment as seg ON head.id = seg.attribute_id 
         LEFT JOIN taw_attribute_subheading as subhead ON subhead.attribute_id = head.id
         WHERE head.heading = 'Weight and volume' AND seg.art_no = %s ORDER BY attribute_id ASC;",
        $sku_no
    );
    
    $weightvolumesupport = $wpdb->get_results($weightvolumesupportquery, ARRAY_A);
}else{
    $weightvolumesupportquery = $wpdb->prepare(
        "SELECT seg.id as attribute_id, head.`attribute` as attibutename, head.product_imgurl as attribute_imgurl, head.attribute_translation as attribute_translation, 
        subhead.attr_value as attr_value, subhead.product_imgurl as attrvalue_imgurls, 
        subhead.product_width as product_width,subhead.product_height as product_height,
        subhead.attr_value_translation as attr_value_translation  
         FROM taw_attribute_heading as head JOIN taw_attribute_segment as seg ON head.id = seg.attribute_id 
         LEFT JOIN taw_attribute_subheading as subhead ON subhead.attribute_id = head.id
         WHERE head.heading = 'Weight and volume' AND seg.cate_no = %s ORDER BY attribute_id ASC;",
        $categorylang
    );
    
    $weightvolumesupport = $wpdb->get_results($weightvolumesupportquery, ARRAY_A);
}            

  if($lang=='en')
  {
    $attributesToDisplay = [];
    foreach ($weightvolumesupport as $spec) {
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
       foreach ($weightvolumesupport as $spec) {
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
$keysToCheck = array_column($weightvolumesupport, 'attibutename');
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
  @media (min-width: 1200px) {
    .attrspace {
      margin-left: 10px;
    }
    .weightwidthspace {
      width: 12.5%;
    }

  }
  .moheight {
    height: 50px !important;
    margin-top: 15px;
  }
  .qtyheight {
    height: 75px !important;
   
  }
</style>

<div class="taw-tab-container">
  <?php if ($matchFound)  { ?>
    <div class="w-full flex flex-wrap">
        <?php foreach ($attributesToDisplay as $attributeKey => $attributeData) {
                if (isset($formatted_attributes[$attributeKey])) { 
                  $productweightvalue = explode(', ',$formatted_attributes[$attributeKey]);
                  $productweightcount = count($productweightvalue);
                    if ($productweightcount >= '1') { ?>
                    <div class="w-1/2 weightwidthspace p-2 ">
                    <span class="text-[12px] text-white font-semibold text-center flex justify-center" 
                        style="width: 100%; background-color: #C70039; padding: 0.5rem 0.2rem; border-top-left-radius: 10px; border-top-right-radius: 10px; line-height: 1.2; min-height: 2.5rem; display: flex; align-items: center; justify-content: center; text-align: center;">
                        <?php echo strtoupper($attributeData['label']); ?>
                    </span>

                      <div class="flex border p-2 items-center my-1 relative" style=" background-color:#374151; margin-bottom: 2px; padding-left: 20px; padding-right: 20px; border-color: #AEAEAE; display: flex; justify-content: center; align-items: center;">
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
                                    echo '<img class="mx-auto moheight md:medheight w-auto" src="' . $oneway_url . '" alt="Manual" style="width: ' . $oneway_width . 'px !important; height: ' . $oneway_height . 'px !important; margin-bottom: 9px; margin-left: 10px; margin-right: 10px; border-radius: 4px; display: block;">';
                                    $hasImages = true; // Mark that at least one image has been rendered
                                }
                            }
                          }
                        }
                    
                        // If no image from image_urls is rendered, check image_url
                        if (!$hasImages && !empty($attributeData['image_url'])) {
                            $front_url = $attributeData['image_url'];
                            echo '<img class="mx-auto moheight md:medheight w-auto" src="' . $front_url . '" alt="Manual" style="margin-bottom: 9px; margin-left: 10px; margin-right: 10px; border-radius: 4px; display: block;">';
                        }
                      } elseif (!empty($attributeData['image_url'])) {
                            // Handle cases where only image_url is populated
                            $front_url = $attributeData['image_url'];
                            echo '<img class="mx-auto moheight md:medheight w-auto" src="' . $front_url . '" alt="Manual" style="margin-bottom: 9px; margin-left: 10px; margin-right: 10px; border-radius: 4px; display: block;">';
                      } 
                      ?>
                      </div>
                      <span class="w-full text-[12px] text-white font-semibold text-center p-1  truncate hover:text-clip flex justify-center" style="background-color: #C70039;padding-top: 2px; padding-bottom: 2px; border-bottom-left-radius: 10px; border-bottom-right-radius: 10px;">
                      <?php
                      //  echo $formatted_attributes[$attributeKey];
                       echo formatAttribute($formatted_attributes[$attributeKey]);
                       ?>
                      </span>
                    </div>
        <?php }}} ?>
    </div>
  <?php }?>
</div>