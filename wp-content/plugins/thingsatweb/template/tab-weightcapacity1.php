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

defined('ABSPATH') || exit;

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


// return $formatted_attributes;
//print_r($formatted_attributes);

// print_r($product_attributes);


?>
<?php
global $post;
$terms = get_the_terms($post->ID, 'product_cat');
$nterms = get_the_terms($post->ID, 'product_tag');
foreach ($terms  as $term) {
  $product_cat_id = $term->term_id;
  $product_cat_name = $term->name;
  break;
}
$lang = getSiteCurrentLang();
//echo $product_cat_name;

?>
<?php if (
  isset($formatted_attributes['WCSB 1 pallet']) || isset($formatted_attributes['WCSB 2 pallets'])
  || isset($formatted_attributes['WCSB 3 pallets'])
) : ?>
  <span class="text-[14px] text-black font-semibold text-left flex" style="width:100%; padding-top: 7px; padding-bottom: 7px; ">
      <?php if ($lang == "en") { ?>  MINIMUM WEIGHT CAPACITY / SUPPORT BEAMS PAIR  <?php } 
      elseif ($lang == "sv") { ?> Minsta viktkapacitet stödbalk <?php } ?>
  </span>
  <table>
    <tr style="background-color: #696969; color: white;">
      <?php if (isset($formatted_attributes['WCSB 1 pallet'])) : ?>
        <td style="text-align: center; padding: 2px;">
            1 PALLET
        </td>
      <?php endif; ?>
      <?php if (isset($formatted_attributes['WCSB 2 pallets'])) : ?>
        <td style="text-align: center; padding: 2px;"> 
            2 PALLET 
        </td>
      <?php endif; ?>
      <?php if (isset($formatted_attributes['WCSB 3 pallets'])) : ?>
        <td style="text-align: center; padding: 2px;"> 
            3 PALLET 
        </td>
      <?php endif; ?>
    </tr>
    <tr>
      <?php if (isset($formatted_attributes['WCSB 1 pallet'])) : ?>
        <td style="text-align: center; padding: 4px;">
          <?php
          if (isset($formatted_attributes['WCSB 1 pallet'])) {
            echo $formatted_attributes['WCSB 1 pallet'];
          } else {
            echo '-';
          }
          ?>
        </td>
      <?php endif; ?>
      <?php if (isset($formatted_attributes['WCSB 2 pallets'])) : ?>
        <td style="text-align: center; padding: 4px;">
          <?php
          if (isset($formatted_attributes['WCSB 2 pallets'])) {
            echo $formatted_attributes['WCSB 2 pallets'];
          } else {
            echo '-';
          }
          ?>
        </td>
      <?php endif; ?>
      <?php if (isset($formatted_attributes['WCSB 3 pallets'])) : ?>
        <td style="text-align: center; padding: 4px;">
          <?php
          if (isset($formatted_attributes['WCSB 3 pallets'])) {
            echo $formatted_attributes['WCSB 3 pallets'];
          } else {
            echo '-';
          }
          ?>
        </td>
      <?php endif; ?>
    </tr>
  </table>
  <span class="text-[14px] text-black font-semibold text-left flex" style="width:100%; padding-top: 7px; padding-bottom: 7px; ">
     <?php if ($lang == "en") { ?> OBS! ONLY ONE UNIT PER SECTION MAY BE EXTRACTED AT A TIME! <?php } 
      elseif ($lang == "sv") { ?> OBS!Bara en enhet kan vara i utdraget läge samtidigt. <?php } ?>
      
  </span>
<?php endif; ?>