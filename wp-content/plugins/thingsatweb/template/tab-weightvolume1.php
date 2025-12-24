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

//echo $product_cat_name;
$lang = getSiteCurrentLang();
?>

<style>
  @media (min-width: 1189px) {
    .attrspace {
      margin-left: 10px;
    }
    .weightwidthspace {
      width: 16.5%;
    }

  }
  .moheight {
    height: 60px !important;
    margin-top: 15px;
  }
  .qtyheight {
    height: 75px !important;
   
  }
</style>

<div class="taw-tab-container">
<div class="w-full flex flex-wrap">

<?php if (isset($formatted_attributes['Product Weight'])) : 
  $productweightvalue = explode(', ',$formatted_attributes['Product Weight']);
  $productweightcount = count($productweightvalue);
  if ($productweightcount >= '1') :?>
  <div class="w-1/2 weightwidthspace p-2 ">
    <span class="text-[12px] text-white font-semibold text-center flex justify-center" style="width:100%; background-color: #C70039; padding-top: 0.66875rem; padding-bottom: 0.56875rem; border-top-left-radius: 10px; border-top-right-radius: 10px;">
       <?php if ($lang == "en") { ?> PRODUCT WEIGHT <?php } elseif ($lang == "sv") { ?> Produktens vikt <?php } ?>
    </span>

    <div class="flex border p-2 items-center my-1 relative" style=" background-color:#374151; margin-bottom: 2px; padding-left: 20px; padding-right: 20px; border-color: #AEAEAE; display: flex; justify-content: center; align-items: center;">
      <?php
      if (isset($formatted_attributes['Product Weight'])) {
        $loaddepth_url = THINGSATWEB_BASE . '/img/productweight.png';
        echo '<img class="mx-auto moheight md:medheight w-auto" src="' . $loaddepth_url . '" alt="Manual" style="margin-bottom: 9px; margin-left: 10px; margin-right: 10px; border-radius: 4px; display: block; ">';
      }
      ?>
    </div>
    <span class="w-full text-[12px] text-white font-semibold text-center p-1  truncate hover:text-clip flex justify-center" style="background-color: #C70039;padding-top: 2px; padding-bottom: 2px; border-bottom-left-radius: 10px; border-bottom-right-radius: 10px;">
      <?php
      if (isset($formatted_attributes['Product Weight'])) {
        echo $formatted_attributes['Product Weight'];
      }
      ?>
    </span>
  </div>
  <?php endif; ?>
  <?php endif; ?>

      <!-- QUANTITY AT PALLET -->
  <?php if (isset($formatted_attributes['Quantity at pallet'])) : 
    $qtyatpalletvalue = explode(', ',$formatted_attributes['Quantity at pallet']);
    $qtyatpalletcount = count($qtyatpalletvalue);
    if ($qtyatpalletcount >= '1') :?>
  <div class="w-1/2 weightwidthspace p-2 ">
    <span class="text-[12px] text-white font-semibold text-center flex justify-center" style="width:100%; background-color: #C70039; padding-top: 0.66875rem; padding-bottom: 0.56875rem; border-top-left-radius: 10px; border-top-right-radius: 10px;">
      <?php if ($lang == "en") { ?> QUANTITY AT PALLET <?php } elseif ($lang == "sv") { ?> Antal på en pall <?php } ?> 
    </span>

    <div class="flex border p-2 items-center my-1  relative" style="background-color:#374151; margin-bottom: 2px; padding-left: 20px; padding-right: 20px; border-color: #AEAEAE; display: flex; justify-content: center; align-items: center;">
      <?php
      if (isset($formatted_attributes['Quantity at pallet'])) {
        $loaddepth_url = THINGSATWEB_BASE . '/img/qtypallet.png';
        echo '<img class="mx-auto qtyheight md:medheight w-auto" src="' . $loaddepth_url . '" alt="Manual" style="margin-bottom: 9px; margin-left: 10px; margin-right: 10px; border-radius: 4px; display: block; ">';
      }
      ?>
    </div>
    <span class="w-full text-[12px] text-white font-semibold text-center p-1  truncate hover:text-clip flex justify-center" style="background-color: #C70039;padding-top: 2px; padding-bottom: 2px; border-bottom-left-radius: 10px; border-bottom-right-radius: 10px;">
      <?php
      if (isset($formatted_attributes['Quantity at pallet'])) {
        echo $formatted_attributes['Quantity at pallet'];
      }
      ?>
    </span>
  </div>
  <?php endif; ?>
  <?php endif; ?>

  <?php if (isset($formatted_attributes['Package weight'])) : 
    $packageweightvalue = explode(', ',$formatted_attributes['Package weight']);
    $packageweightcount = count($packageweightvalue);
    if ($packageweightcount >= '1') :?>
  <div class="w-1/2 weightwidthspace p-2 ">
    <span class="text-[12px] text-white font-semibold text-center flex justify-center" style="width:100%; background-color: #C70039; padding-top: 0.66875rem; padding-bottom: 0.56875rem; border-top-left-radius: 10px; border-top-right-radius: 10px;">
      <?php if ($lang == "en") { ?> PACKAGE WEIGHT <?php } elseif ($lang == "sv") { ?> Paketsvikt <?php } ?>  
    </span>

    <div class="flex border p-2 items-center my-1 relative" style="background-color:#374151; margin-bottom: 2px; padding-left: 20px; padding-right: 20px; border-color: #AEAEAE; display: flex; justify-content: center; align-items: center;">
      <?php
      if (isset($formatted_attributes['Package weight'])) {
        $loaddepth_url = THINGSATWEB_BASE . '/img/packageweight.png';
        echo '<img class="mx-auto moheight md:medheight w-auto" src="' . $loaddepth_url . '" alt="Manual" style="margin-bottom: 9px; margin-left: 10px; margin-right: 10px; border-radius: 4px; display: block; ">';
      }
      ?>
    </div>
    <span class="w-full text-[12px] text-white font-semibold text-center p-1  truncate hover:text-clip flex justify-center" style="background-color: #C70039;padding-top: 2px; padding-bottom: 2px; border-bottom-left-radius: 10px; border-bottom-right-radius: 10px;">
      <?php
      if (isset($formatted_attributes['Package weight'])) {
        echo $formatted_attributes['Package weight'];
      }
      ?>
    </span>
  </div>
  <?php endif; ?>
  <?php endif; ?>

  <?php if (isset($formatted_attributes['Number of pallets / Trailer'])) : 
    $noofpalletvalue = explode(', ',$formatted_attributes['Number of pallets / Trailer']);
    $noofpalletcount = count($noofpalletvalue);
    if ($noofpalletcount >= '1') :?>
  <div class="w-1/2 weightwidthspace  p-2">
    <?php if ($lang == "en") { ?>
      <span class="text-[12px] text-white font-semibold text-center flex justify-center" style="width:100%; background-color: #C70039; padding-top: 0.16875rem; border-top-left-radius: 10px; border-top-right-radius: 10px;">
           NUMBER OF PALLETS /TRAILER
      </span>
     <?php } elseif ($lang == "sv") { ?>
        <span class="text-[12px] text-white font-semibold text-center flex justify-center" style="width:100%; background-color: #C70039; padding-top: 0.66875rem; padding-bottom: 0.56875rem; border-top-left-radius: 10px; border-top-right-radius: 10px;">
            Antal på en lastbil   
        </span>
    <?php } ?> 

    <div class="flex border p-2 items-center my-1 relative" style="background-color:#374151; margin-bottom: 2px; padding-left: 20px; padding-right: 20px; border-color: #AEAEAE; display: flex; justify-content: center; align-items: center;">
      <?php
      if (isset($formatted_attributes['Number of pallets / Trailer'])) {
        $loaddepth_url = THINGSATWEB_BASE . '/img/numberpallet.png';
        echo '<img class="mx-auto moheight md:medheight w-auto" src="' . $loaddepth_url . '" alt="Manual" style="margin-bottom: 9px; margin-left: 10px; margin-right: 10px; border-radius: 4px; display: block; ">';
      }
      ?>
    </div>
    <span class="w-full text-[12px] text-white font-semibold text-center p-1  truncate hover:text-clip flex justify-center" style="background-color: #C70039;padding-top: 2px; padding-bottom: 2px; border-bottom-left-radius: 10px; border-bottom-right-radius: 10px;">
      <?php
      if (isset($formatted_attributes['Number of pallets / Trailer'])) {
        echo $formatted_attributes['Number of pallets / Trailer'];
      }
      ?>
    </span>
  </div>
  <?php endif; ?>
  <?php endif; ?>

  <?php if (isset($formatted_attributes['Quantity in the Package'])) : 
    $qtyinpackagevalue = explode(', ',$formatted_attributes['Quantity in the Package']);
    $qtyinpackagecount = count($qtyinpackagevalue);
    if ($qtyinpackagecount >= '1') :?>
  <div class="w-1/2 weightwidthspace p-2 ">
    <span class="text-[12px] text-white font-semibold text-center flex justify-center" style="width:100%; background-color: #C70039; padding-top: 0.66875rem; padding-bottom: 0.56875rem; border-top-left-radius: 10px; border-top-right-radius: 10px;">
      <?php if ($lang == "en") { ?> QUANTITY IN THE PACKAGE <?php } elseif ($lang == "sv") { ?> Antal / förpackning <?php } ?>  
    </span>

    <div class="flex border p-2 items-center my-1 relative" style="background-color:#374151; margin-bottom: 2px; padding-left: 20px; padding-right: 20px; border-color: #AEAEAE; display: flex; justify-content: center; align-items: center;">
      <?php
      if (isset($formatted_attributes['Quantity in the Package'])) {
        $loaddepth_url = THINGSATWEB_BASE . '/img/Quantity-in-the-Package.png';
        echo '<img class="mx-auto moheight md:medheight w-auto" src="' . $loaddepth_url . '" alt="Manual" style="margin-bottom: 9px; margin-left: 10px; margin-right: 10px; border-radius: 4px; display: block; ">';
      }
      ?>
    </div>
    <span class="w-full text-[12px] text-white font-semibold text-center p-1  truncate hover:text-clip flex justify-center" style="background-color: #C70039;padding-top: 2px; padding-bottom: 2px; border-bottom-left-radius: 10px; border-bottom-right-radius: 10px;">
      <?php
      if (isset($formatted_attributes['Quantity in the Package'])) {
        echo $formatted_attributes['Quantity in the Package'];
      }
      ?>
    </span>
  </div>
  <?php endif; ?>
  <?php endif; ?>

  <?php if (isset($formatted_attributes['Package Size'])) : 
    $packagesizevalue = explode(', ',$formatted_attributes['Package Size']);
    $packagesizecount = count($packagesizevalue);
    if ($packagesizecount >= '1') :?>
  <div class="w-1/2 weightwidthspace p-2 ">
    <span class="text-[12px] text-white font-semibold text-center flex justify-center" style="width:100%; background-color: #C70039; padding-top: 0.66875rem; padding-bottom: 0.56875rem; border-top-left-radius: 10px; border-top-right-radius: 10px;">
      <?php if ($lang == "en") { ?> PACKAGE SIZE <?php } elseif ($lang == "sv") { ?> Storlek försändelse <?php } ?>  
    </span>

    <div class="flex border p-2 items-center my-1 relative" style="background-color:#374151; margin-bottom: 2px; padding-left: 20px; padding-right: 20px; border-color: #AEAEAE; display: flex; justify-content: center; align-items: center;">
      <?php
      if (isset($formatted_attributes['Package Size'])) {
        $loaddepth_url = THINGSATWEB_BASE . '/img/Package-size.png';
        echo '<img class="mx-auto moheight md:medheight w-auto" src="' . $loaddepth_url . '" alt="Manual" style="margin-bottom: 9px; margin-left: 10px; margin-right: 10px; border-radius: 4px; display: block; ">';
      }
      ?>
    </div>
    <span class="w-full text-[12px] text-white font-semibold text-center p-1  truncate hover:text-clip flex justify-center" style="background-color: #C70039;padding-top: 2px; padding-bottom: 2px; border-bottom-left-radius: 10px; border-bottom-right-radius: 10px;">
      <?php
      if (isset($formatted_attributes['Package Size'])) {
        echo $formatted_attributes['Package Size'];
      }
      ?>
    </span>
  </div>
  <?php endif; ?>
  <?php endif; ?>

</div>
</div>
