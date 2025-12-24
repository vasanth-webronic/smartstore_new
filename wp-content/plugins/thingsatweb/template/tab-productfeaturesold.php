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

// echo "<script>console.log('meth1picking: " . json_encode($formatted_attributes) . "' );</script>";
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

    .loadspace {
      width: 16.66%;
    }

  }

  .mobheight {
    height: 50px !important;
  }

  .mobtypeloadheight {
    height: 30px !important;
  }

  .mobtypeheight {
    height: 40px !important;
  }
</style>
<div class="taw-tab-container">

  <style>
    @media (min-width: 1189px) {
      /* .attrspace {
        margin-left: 10px;
      } */

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
      margin-top: 15px;
    }

  </style>
 
  <div class="w-full flex flex-wrap">
    <?php if (isset($formatted_attributes['Weight capacity'])) : ?>
      <div class="w-1/2 items-center border-b-2 widspace p-2 ">
        <div class="flex border p-2 items-center my-1 relative" style="margin-bottom: -1px; padding-left: 20px; padding-right: 20px; border-color: #AEAEAE; display: flex; justify-content: center; align-items: center;">
          <span class="absolute top-0 left-0 right-0 text-[10px] text-black font-semibold text-center bg-gray-200 flex justify-center" style="width:100%;">
            <?php if ($lang == "en") { ?> WEIGHT CAPACITY <?php } elseif ($lang == "sv") { ?> Viktkapacitet <?php } ?>
          </span>
          <?php
          $weightcap_url = THINGSATWEB_BASE . '/img/Load-capacity.png';
          echo '<img class="mx-auto mobilheight md:medheight w-auto" src="' . $weightcap_url . '" alt="Manual" style="margin-bottom: 9px; margin-left: 10px; margin-right: 10px; border-radius: 4px; display: block;">';
          ?>
        </div>
        <span class="w-full text-[12px]  text-white font-semibold text-center p-1 bg-gray-700  hover:text-clip flex justify-center" style="overflow-wrap: break-word; word-wrap: break-word; hyphens: auto; cursor: pointer; display: inline-block; max-width: 100%;">
          <?php
          $weightcapacity = explode(', ', $formatted_attributes['Weight capacity']);
          echo implode(' | ', $weightcapacity);
          ?>
        </span>
      </div>
    <?php endif; ?>

    <?php if (isset($formatted_attributes['Extension'])) : ?>
      <div class="w-1/2 items-center border-b-2 widspace p-2 ">
        <div class="flex border p-2 items-center my-1 relative" style="margin-bottom: -1px; padding-left: 20px; padding-right: 20px; border-color: #AEAEAE; display: flex; justify-content: center; align-items: center;">
          <span class="absolute top-0 left-0 right-0 text-[10px] text-black font-semibold text-center  bg-gray-200 flex justify-center" style="width:100%;">
            <?php if ($lang == "en") { ?> EXTENSION <?php } elseif ($lang == "sv") { ?> Utdrag <?php } ?>
          </span>
          <?php
          $extension = strtolower($formatted_attributes['Extension']);

          if (strpos(strtolower($extension), '100%') !== false) {
            $extension_url = THINGSATWEB_BASE . '/img/One-Way-100_.png';
            echo '<img class="mx-auto mobilheight md:medheight w-auto" src="' . $extension_url . '" alt="Manual" style="margin-bottom: 9px; margin-left: 10px; margin-right: 10px; border-radius: 4px; display: block;">';
          }
          if (strpos(strtolower($extension), '70%') !== false) {
            $extension_url = THINGSATWEB_BASE . '/img/One-Way-70_.png';
            echo '<img class="mx-auto mobilheight md:medheight w-auto" src="' . $extension_url . '" alt="Manual" style="margin-bottom: 9px; margin-left: 10px; margin-right: 10px; border-radius: 4px; display: block;">';
          }
          if (strpos(strtolower($extension), '85%') !== false) {
            $extension_url = THINGSATWEB_BASE . '/img/One-Way-85_.png';
            echo '<img class="mx-auto mobilheight md:medheight w-auto" src="' . $extension_url . '" alt="Manual" style="margin-bottom: 9px; margin-left: 10px; margin-right: 10px; border-radius: 4px; display: block;">';
          }
          if (strpos(strtolower($extension), '95%') !== false) {
            $extension_url = THINGSATWEB_BASE . '/img/One-Way-95_.png';
            echo '<img class="mx-auto mobilheight md:medheight w-auto" src="' . $extension_url . '" alt="Manual" style="margin-bottom: 9px; margin-left: 10px; margin-right: 10px; border-radius: 4px; display: block;">';
          }
          if (strpos(strtolower($extension), '74%') !== false) {
            $extension_url = THINGSATWEB_BASE . '/img/One-Way-74_.png';
            echo '<img class="mx-auto mobilheight md:medheight w-auto" src="' . $extension_url . '" alt="Manual" style="margin-bottom: 9px; margin-left: 10px; margin-right: 10px; border-radius: 4px; display: block;">';
          }
          if (strpos(strtolower($extension), '65%') !== false) {
            $extension_url = THINGSATWEB_BASE . '/img/One-Way-65_.png';
            echo '<img class="mx-auto mobilheight md:medheight w-auto" src="' . $extension_url . '" alt="Manual" style="margin-bottom: 9px; margin-left: 10px; margin-right: 10px; border-radius: 4px; display: block;">';
          }
          if ((strpos(strtolower($extension), 'two-way 2×70%') !== false) || (strpos(strtolower($extension), 'Tvåvägs 2×70%') !== false) ){
            $extension_url = THINGSATWEB_BASE . '/img/Two-Way-70.png';
            echo '<img class="mx-auto mobilheight md:medheight w-auto" src="' . $extension_url . '" alt="Manual" style="margin-bottom: 9px; margin-left: 10px; margin-right: 10px; border-radius: 4px; display: block;">';
          }

          ?>
        </div>
        <span class="w-full text-[12px] text-white font-semibold text-center p-1 bg-gray-700  hover:text-clip flex justify-center" style="overflow-wrap: break-word; word-wrap: break-word; hyphens: auto; cursor: pointer; display: inline-block; max-width: 100%;">
          <?php
          $extension = explode(', ', $formatted_attributes['Extension']);
          echo implode(' | ', $extension);
          ?>
        </span>
      </div>
    <?php endif; ?>

    <?php if (isset($formatted_attributes['Colour'])) : ?>
      <div class="w-1/2 items-center border-b-2 widspace p-2 ">
        <div class="flex border p-2 items-center my-1 relative" style="margin-bottom: -1px; padding-left: 20px; padding-right: 20px; border-color: #AEAEAE; display: flex; justify-content: center; align-items: center;">
          <span class="absolute top-0 left-0 right-0 text-[10px] text-black font-semibold text-center  bg-gray-200 flex justify-center" style="width:100%;">
            <?php if ($lang == "en") { ?> COLOUR <?php } elseif ($lang == "sv") { ?> Färg <?php } ?>
          </span>
          <?php
          $Colour_url = THINGSATWEB_BASE . '/img/Colour.png';
          echo '<img class="mx-auto mobilheight md:medheight w-auto" src="' . $Colour_url . '" alt="Manual" style="margin-bottom: 9px; margin-left: 10px; margin-right: 10px; border-radius: 4px; display: block;">';
          ?>
        </div>
        <span class="w-full text-[12px] text-white font-semibold text-center p-1 bg-gray-700  hover:text-clip flex justify-center" style="overflow-wrap: break-word; word-wrap: break-word; hyphens: auto; cursor: pointer; display: inline-block; max-width: 100%;">
          <?php
          $colour = explode(', ', $formatted_attributes['Colour']);
          echo implode(' | ', $colour);
          ?>
        </span>
      </div>
    <?php endif; ?>

    <?php if (isset($formatted_attributes['Load metod'])) : ?>
      <div class="w-1/2 items-center border-b-2 widspace p-2 ">
        <div class="flex border p-2 items-center my-1 relative" style="margin-bottom: -1px; padding-left: 20px; padding-right: 20px; border-color: #AEAEAE; display: flex; justify-content: center; align-items: center;">
          <span class="absolute top-0 left-0 right-0 text-[10px] text-black font-semibold text-center bg-gray-200 flex justify-center" style="width:100%;">
            <?php if ($lang == "en") { ?> LOAD METHOD <?php } elseif ($lang == "sv") { ?> Lastmetod <?php } ?>
          </span>

          <?php
          $load_method = strtolower($formatted_attributes['Load metod']);

          if ((strpos($load_method, 'forklift') !== false) || (strpos($load_method, 'gaffeltruck') !== false)) {
            $fork_url = THINGSATWEB_BASE . '/img/ForkLift.png';
            echo '<img class="mx-auto mobilheight md:medheight w-auto" src="' . $fork_url . '" alt="Manual" style="margin-bottom: 9px; width:40px; height:30px; border-radius: 4px; display: block;">';
          }

          if ((strpos($load_method, 'hand pallet truck') !== false) || (strpos(strtolower($load_method), 'palldragare') !== false)) {
            $handpallet_url = THINGSATWEB_BASE . '/img/Hand_pallet_truck.png';
            echo '<img class="mx-auto mobilheight md:medheight w-auto" src="' . $handpallet_url . '" alt="Manual" style="margin-bottom: 9px; width:40px; height:30px; border-radius: 4px; display: block;">';
          }

          if ((strpos($load_method, 'by hand') !== false) || (strpos($load_method, 'manuellt') !== false)) {
            $byhand_url = THINGSATWEB_BASE . '/img/By_hand.png';
            echo '<img class="mx-auto mobilheight md:medheight w-auto" src="' . $byhand_url . '" alt="Manual" style="margin-bottom: 9px; width:40px; height:30px; border-radius: 4px; display: block;">';
          }

          if ((strpos($load_method, 'pallet stacker') !== false) || (strpos($load_method, 'ledstaplare') !== false)) {
            $stackers_url = THINGSATWEB_BASE . '/img/Pallet_stacker.png';
            echo '<img class="mx-auto mobilheight md:medheight w-auto" src="' . $stackers_url . '" alt="Manual" style="margin-bottom: 9px; width:40px; height:30px; border-radius: 4px; display: block;">';
          }

          if ((strpos($load_method, 'overhead crane') !== false) || (strpos($load_method, 'travers') !== false)) {
            $traverse_url = THINGSATWEB_BASE . '/img/Overheadcrane.png';
            echo '<img class="filter-item-image mobheight mx-auto my-0 md:my-4  md:!h-11 w-auto" src="' . $traverse_url . '" alt="Manual" style="margin-bottom: 9px; width:40px; height:30px; border-radius: 4px;">';
          }
          ?>
        </div>
        <span class="w-full text-[12px] text-white font-semibold text-center p-1 bg-gray-700  hover:text-clip flex justify-center" style="overflow-wrap: break-word; word-wrap: break-word; hyphens: auto; cursor: pointer; display: inline-block; max-width: 100%;">
          <?php
          $loadmethod = explode(', ', $formatted_attributes['Load metod']);
          echo implode(' | ', $loadmethod);
          ?>
        </span>
      </div>
    <?php endif; ?>

    <?php if (isset($formatted_attributes['Type of load'])) : ?>
      <div class="w-1/2 items-center border-b-2 widspace p-2 ">
        <div class="flex border p-2 items-center my-1 relative" style="margin-bottom: -1px; padding-left: 20px; padding-right: 20px; border-color: #AEAEAE; display: flex; justify-content: center; align-items: center;">
          <span class="absolute top-0 left-0 right-0 text-[10px] text-black font-semibold text-center bg-gray-200 flex justify-center" style="width:100%;">
            <?php if ($lang == "en") { ?> TYPE OF LOAD <?php } elseif ($lang == "sv") { ?> Typ av last <?php } ?>
          </span>
          <?php
          $typeload = strtolower($formatted_attributes['Type of load']);

          if ((strpos(strtolower($typeload), '½ eur-pallet') !== false) || (strpos(strtolower($typeload), '½ eur-pal') !== false)) {
            $pall_url = THINGSATWEB_BASE . '/img/halfEUR.png';
            echo '<img class="mx-auto mobilheight md:medheight w-auto" src="' . $pall_url . '" alt="Manual" style="margin-bottom: 9px; width:60px; height:30px; margin-left: 2px; margin-right: 2px; border-radius: 4px; display: block;">';
          }

          if ((strpos(strtolower($typeload), 'eur-pallet') !== false) || (strpos(strtolower($typeload), 'eur-pall') !== false)) {
            $eur_url = THINGSATWEB_BASE . '/img/EUR.png';
            echo '<img class="mx-auto mobilheight md:medheight w-auto" src="' . $eur_url . '" alt="Manual" style="margin-bottom: 9px; width:60px; height:30px; margin-left: 2px; margin-right: 2px; border-radius: 4px; display: block;">';
          }

          if ((strpos(strtolower($typeload), 'fin-/ chep-pallet') !== false) || (strpos(strtolower($typeload), 'fin-pall') !== false) ) {
            $fin_url = THINGSATWEB_BASE . '/img/FIN.png';
            echo '<img class="mx-auto mobilheight md:medheight w-auto" src="' . $fin_url . '" alt="Manual" style="margin-bottom: 9px; width:60px; height:30px; margin-left: 2px; margin-right: 2px; border-radius: 4px; display: block;">';
          }
          $typelo = htmlspecialchars_decode(strtolower($formatted_attributes['Type of load']));
          if ((stripos($typelo, 'bins & boxes') !== false) || (strpos(strtolower($typelo), 'lådor') !== false)) {
            $bin_url = THINGSATWEB_BASE . '/img/PlasticBin.png';
            echo '<img class="mx-auto mobilheight md:medheight w-auto" src="' . $bin_url . '" alt="Manual" style="margin-bottom: 9px; width:30px; height:30px; margin-left: 2px; margin-right: 2px; border-radius: 4px; display: block;">';
          }
          if ((strpos(strtolower($typeload), 'gitterbox') !== false) || (strpos(strtolower($typeload), 'nätcontainer') !== false) ) {
            $hmu_url = THINGSATWEB_BASE . '/img/HMUbox.png';
            echo '<img class="mx-auto typeheight md:medheight w-auto" src="' . $hmu_url . '" alt="Manual" style="margin-bottom: 9px; width:30px;  margin-left: 2px; margin-right: 2px; border-radius: 4px; display: block;">';
          }

            if ((strpos(strtolower($typeload), 'pipes') !== false) || (strpos(strtolower($typeload), 'rör') !== false)) {
              $pipe_url = THINGSATWEB_BASE . '/img/Pipe.png';
              echo '<img class="mx-auto mobilheight md:mdheight w-auto" src="' . $pipe_url . '" alt="Manual" style="margin-bottom: 9px; width:30px; height:20px; margin-left: 2px; margin-right: 2px; border-radius: 4px; display: block;">';
            }

            if ((strpos(strtolower($typeload), 'steel frame') !== false) || (strpos(strtolower($typeload), 'Stålram') !== false)) {
              $steelframe_url = THINGSATWEB_BASE . '/img/SteelFrame.png';
              echo '<img class="mx-auto mobilheight md:medheight w-auto" src="' . $steelframe_url . '" alt="Manual" style="margin-bottom: 9px; width:40px; height:30px; margin-left: 2px; margin-right: 2px; border-radius: 4px; display: block;">';
            }
          ?>
        </div>
        <span class="w-full text-[12px] text-white font-semibold text-center p-1 bg-gray-700  hover:text-clip flex justify-center" style="overflow-wrap: break-word; word-wrap: break-word; hyphens: auto; cursor: pointer; display: inline-block; max-width: 100%;">
          <?php
          $typeofload = explode(', ', $formatted_attributes['Type of load']);
          echo implode(' | ', $typeofload);
          ?>
        </span>
      </div>
    <?php endif; ?>

    <?php if (isset($formatted_attributes['Loading'])) : ?>
      <div class="w-1/2 items-center border-b-2 widspace p-2 ">
        <div class="flex border p-2 items-center my-1 relative" style="margin-bottom: -1px; padding-left: 20px; padding-right: 20px; border-color: #AEAEAE; display: flex; justify-content: center; align-items: center;">
          <span class="absolute top-0 left-0 right-0 text-[10px] text-black font-semibold text-center  bg-gray-200 flex justify-center" style="width:100%;">
            <?php if ($lang == "en") { ?> LOADING <?php } elseif ($lang == "sv") { ?> Lastning <?php } ?>
          </span>
          <?php
          $loading = strtolower($formatted_attributes['Loading']);

          if (strpos($loading, 'front loaded') !== false || strpos($loading, 'frontlastad') !== false) {
            $front_url = THINGSATWEB_BASE . '/img/FrontLoadnew.png';
            echo '<img class="mx-auto mobilheight md:medheight w-auto" src="' . $front_url . '" alt="Manual" style="margin-bottom: 9px; width:80px; margin-left: 2px; margin-right: 2px; border-radius: 4px; display: block;">';
          }

          if ((strpos($loading, 'rear loaded') !== false) || (strpos($loading, 'baklastad') !== false)) {
            $rear_url = THINGSATWEB_BASE . '/img/RearLoadnew.png';
            echo '<img class="mx-auto mobilheight md:medheight w-auto" src="' . $rear_url . '" alt="Manual" style="margin-bottom: 9px;width:80px; margin-left: 2px; margin-right: 2px; border-radius: 4px; display: block;">';
          }

          ?>
        </div>
        <span class="w-full text-[12px] text-white font-semibold text-center p-1 bg-gray-700  hover:text-clip flex justify-center" style="overflow-wrap: break-word; word-wrap: break-word; hyphens: auto; cursor: pointer; display: inline-block; max-width: 100%;">
          <?php
          $loading = explode(', ', $formatted_attributes['Loading']);
          echo implode(' | ', $loading);
          ?>
        </span>
      </div>
    <?php endif; ?>

    <?php if (isset($formatted_attributes['Load Way'])) : ?>
      <div class="w-1/2 items-center border-b-2 widspace p-2 ">
        <div class="flex border p-2 items-center my-1 relative" style="margin-bottom: -1px; padding-left: 20px; padding-right: 20px; border-color: #AEAEAE; display: flex; justify-content: center; align-items: center;">
          <span class="absolute top-0 left-0 right-0 text-[10px] text-black font-semibold text-center bg-gray-200 flex justify-center" style="width:100%;">
            <?php if ($lang == "en") { ?> LOAD WAY <?php } elseif ($lang == "sv") { ?> Lastmetod <?php } ?>
          </span>
          <?php
          $load_way = strtolower($formatted_attributes['Load Way']);

          if ((strpos($load_way, 'one-way') !== false) || (strpos($load_way, 'envägs') !== false)) {
            $oneway_url = THINGSATWEB_BASE . '/img/One-Way.png';
            echo '<img class="mx-auto mobilheight md:medheight w-auto" src="' . $oneway_url . '" alt="Manual" style="margin-bottom: 9px; margin-left: 10px; margin-right: 10px; border-radius: 4px; display: block;">';
          }

          if ((strpos($load_way, 'two-way') !== false) || (strpos($load_way, 'tvåvägs') !== false)) {
            $twoway_url = THINGSATWEB_BASE . '/img/Two-Way.png';
            echo '<img class="mx-auto mobilheight md:medheight w-auto" src="' . $twoway_url . '" alt="Manual" style="margin-bottom: 9px; margin-left: 10px; margin-right: 10px; border-radius: 4px; display: block;">';
          }
          ?>
        </div>
        <span class="w-full text-[12px] text-white font-semibold text-center p-1 bg-gray-700  hover:text-clip flex justify-center" style="overflow-wrap: break-word; word-wrap: break-word; hyphens: auto; cursor: pointer; display: inline-block; max-width: 100%;">
          <?php
          $loadway = explode(', ', $formatted_attributes['Load Way']);
          echo implode(' | ', $loadway);
          ?>
        </span>
      </div>
    <?php endif; ?>

    <?php if (isset($formatted_attributes['Picking Method'])) : ?>
      <div class="w-1/2 items-center border-b-2 widspace p-2 ">
        <div class="flex border p-2 items-center my-1 relative" style="margin-bottom: -1px; padding-left: 20px; padding-right: 20px; border-color: #AEAEAE; display: flex; justify-content: center; align-items: center;">
          <span class="absolute top-0 left-0 right-0 text-[10px] text-black font-semibold text-center  bg-gray-200 flex justify-center" style="width:100%;">
            <?php if ($lang == "en") { ?> PICKING METHOD <?php } elseif ($lang == "sv") { ?> Plockningsmetod <?php } ?>
          </span>
          <?php
          $picking_method = strtolower($formatted_attributes['Picking Method']);

          if ((strpos($picking_method, 'by hand') !== false) || (strpos($picking_method, 'manuell') !== false)) {
            $manual_url = THINGSATWEB_BASE . '/img/manual.png';
            echo '<img class="mx-auto mobilheight md:medheight w-auto" src="' . $manual_url . '" alt="Manual" style="margin-bottom: 9px; margin-left: 10px; margin-right: 10px; border-radius: 4px; display: block;">';
          }

          if ((strpos($picking_method, 'overhead crane') !== false) || (strpos($picking_method, 'travers eller lyftverktyg') !== false)) {
            $traverse_url = THINGSATWEB_BASE . '/img/traverse.png';
            echo '<img class="mx-auto mobilheight md:medheight w-auto" src="' . $traverse_url . '" alt="Manual" style="margin-bottom: 9px; margin-left: 10px; margin-right: 10px; border-radius: 4px; display: block;">';
          }

          ?>
        </div>
        <span class="w-full text-[12px] text-white font-semibold text-center p-1 bg-gray-700  hover:text-clip flex justify-center" style="overflow-wrap: break-word; word-wrap: break-word; hyphens: auto; cursor: pointer; display: inline-block; max-width: 100%;">
          <?php
          $pickingmethod = explode(', ', $formatted_attributes['Picking Method']);
          echo implode(' | ', $pickingmethod);
          ?>
        </span>
      </div>
    <?php endif; ?>

    <?php if (isset($formatted_attributes['Short or long side handled'])) : ?>
      <div class="w-1/2 items-center border-b-2 widspace p-2 ">
        <div class="flex border p-2 items-center my-1 relative" style="margin-bottom: -1px; padding-left: 20px; padding-right: 20px; border-color: #AEAEAE; display: flex; justify-content: center; align-items: center;">
          <span class="absolute top-0 left-0 right-0 text-[10px] text-black font-semibold text-center  bg-gray-200 flex justify-center" style="width:100%;">
            <?php if ($lang == "en") { ?> Short or long side handled <?php } elseif ($lang == "sv") { ?> Kort- eller långsideshanterad <?php } ?>
          </span>
          <?php
          $Short_longsidehandled = strtolower($formatted_attributes['Short or long side handled']);

          if ((strpos($Short_longsidehandled, 'long side handled') !== false) || (strpos($Short_longsidehandled, 'långsideshanterad') !== false)) {
            $long_url = THINGSATWEB_BASE . '/img/Long-300x235.png';
            echo '<img class="mx-auto mobilheight md:medheight w-auto" src="' . $long_url . '" alt="Manual" style="margin-bottom: 9px; margin-left: 10px; margin-right: 10px; border-radius: 4px; display: block;">';
          }

          if ((strpos($Short_longsidehandled, 'short side handled') !== false) || (strpos($Short_longsidehandled, 'kortsideshanterad') !== false)) {
            $short_url = THINGSATWEB_BASE . '/img/Short-300x235.png';
            echo '<img class="mx-auto mobilheight md:medheight w-auto" src="' . $short_url . '" alt="Manual" style="margin-bottom: 9px; margin-left: 10px; margin-right: 10px; border-radius: 4px; display: block;">';
          }

          if ((strpos($Short_longsidehandled, '½ eur-pallet long side handled') !== false) || (strpos($Short_longsidehandled, '½ eur-pall långsideshanterad') !== false)) {
            $halflong_url = THINGSATWEB_BASE . '/img/1by2-Long-1-300x235.png';
            echo '<img class="mx-auto mobilheight md:medheight w-auto" src="' . $halflong_url . '" alt="Manual" style="margin-bottom: 9px; margin-left: 10px; margin-right: 10px; border-radius: 4px; display: block;">';
          }

          ?>
        </div>
        <span class="w-full text-[12px] text-white font-semibold text-center p-1 bg-gray-700  hover:text-clip flex justify-center" style="overflow-wrap: break-word; word-wrap: break-word; hyphens: auto; cursor: pointer; display: inline-block; max-width: 100%;">
          <?php
          $Short_longside_handled = explode(', ', $formatted_attributes['Short or long side handled']);
          echo implode(' | ', $Short_longside_handled);
          ?>
        </span>
      </div>
    <?php endif; ?>

    <?php if (isset($formatted_attributes['Shelf lock'])) : ?>
      <div class="w-1/2 items-center border-b-2 widspace p-2 ">
        <div class="flex border p-2 items-center my-1 relative" style="margin-bottom: -1px; padding-left: 20px; padding-right: 20px; border-color: #AEAEAE; display: flex; justify-content: center; align-items: center;">
          <span class="absolute top-0 left-0 right-0 text-[10px] text-black font-semibold text-center  bg-gray-200 flex justify-center" style="width:100%;">
            <?php if ($lang == "en") { ?> SHELF LOCK <?php } elseif ($lang == "sv") { ?> Tippskyddslås <?php } ?>
          </span>
          <?php
           $shelflock_url = THINGSATWEB_BASE . '/img/ShelfLock.png';
           echo '<img class="mx-auto mobilheight md:medheight w-auto" src="' . $shelflock_url . '" alt="Manual" style="margin-bottom: 9px; margin-left: 10px; margin-right: 10px; border-radius: 4px; display: block;">';
          ?>
        </div>
        <span class="w-full text-[12px] text-white font-semibold text-center p-1 bg-gray-700  hover:text-clip flex justify-center" style="overflow-wrap: break-word; word-wrap: break-word; hyphens: auto; cursor: pointer; display: inline-block; max-width: 100%;">
          <?php
          $Shelf_lock = explode(', ', $formatted_attributes['Shelf lock']);
          echo implode(' | ', $Shelf_lock);
          ?>
        </span>
      </div>
    <?php endif; ?>

    <?php if (isset($formatted_attributes['Number of shelves'])) : ?>
      <div class="w-1/2 items-center border-b-2 widspace p-2 ">
        <div class="flex border p-2 items-center my-1 relative" style="margin-bottom: -1px; padding-left: 20px; padding-right: 20px; border-color: #AEAEAE; display: flex; justify-content: center; align-items: center;">
          <span class="absolute top-0 left-0 right-0 text-[10px] text-black font-semibold text-center  bg-gray-200 flex justify-center" style="width:100%;">
            <?php if ($lang == "en") { ?> NUMBER OF SHELVES <?php } elseif ($lang == "sv") { ?> Antal hyllor <?php } ?>
          </span>
          <?php
          $shelves = strtolower($formatted_attributes['Number of shelves']);

          if ((strpos($shelves, '1 shelve') !== false)  || (strpos($shelves, '1 hyllor') !== false)) {
            $shelve_1 = THINGSATWEB_BASE . '/img/1shelve.png';
            echo '<img class="mx-auto mobilheight md:medheight w-auto" src="' . $shelve_1 . '" alt="Manual" style="margin-bottom: 9px; margin-left: 10px; margin-right: 10px; border-radius: 4px; display: block;">';
          }

          if ((strpos($shelves, '2 shelves') !== false)  || (strpos($shelves, '2 hyllor') !== false)) {
            $shelve_2 = THINGSATWEB_BASE . '/img/2shelve.png';
            echo '<img class="mx-auto mobilheight md:medheight w-auto" src="' . $shelve_2 . '" alt="Manual" style="margin-bottom: 9px; margin-left: 10px; margin-right: 10px; border-radius: 4px; display: block;">';
          }

          if ((strpos($shelves, '3 shelves') !== false)  || (strpos($shelves, '3 hyllor') !== false)) {
            $shelve_3 = THINGSATWEB_BASE . '/img/3shelve.png';
            echo '<img class="mx-auto mobilheight md:medheight w-auto" src="' . $shelve_3 . '" alt="Manual" style="margin-bottom: 9px; margin-left: 10px; margin-right: 10px; border-radius: 4px; display: block;">';
          }

          if ((strpos($shelves, '4 shelves') !== false)  || (strpos($shelves, '4 hyllor') !== false)) {
            $shelve_4 = THINGSATWEB_BASE . '/img/4shelve.png';
            echo '<img class="mx-auto mobilheight md:medheight w-auto" src="' . $shelve_4 . '" alt="Manual" style="margin-bottom: 9px; margin-left: 10px; margin-right: 10px; border-radius: 4px; display: block;">';
          }

          if ((strpos($shelves, '5 shelves') !== false)  || (strpos($shelves, '5 hyllor') !== false)) {
            $shelve_5 = THINGSATWEB_BASE . '/img/5shelve.png';
            echo '<img class="mx-auto mobilheight md:medheight w-auto" src="' . $shelve_5 . '" alt="Manual" style="margin-bottom: 9px; margin-left: 10px; margin-right: 10px; border-radius: 4px; display: block;">';
          }

          if ((strpos($shelves, '6 shelves') !== false)  || (strpos($shelves, '6 hyllor') !== false)) {
            $shelve_6 = THINGSATWEB_BASE . '/img/6shelve.png';
            echo '<img class="mx-auto mobilheight md:medheight w-auto" src="' . $shelve_6 . '" alt="Manual" style="margin-bottom: 9px; margin-left: 10px; margin-right: 10px; border-radius: 4px; display: block;">';
          }

          if ((strpos($shelves, '7 shelves') !== false)  || (strpos($shelves, '7 hyllor') !== false)) {
            $shelve_7 = THINGSATWEB_BASE . '/img/7shelve.png';
            echo '<img class="mx-auto mobilheight md:medheight w-auto" src="' . $shelve_7 . '" alt="Manual" style="margin-bottom: 9px; margin-left: 10px; margin-right: 10px; border-radius: 4px; display: block;">';
          }

          if ((strpos($shelves, '8 shelves') !== false)  || (strpos($shelves, '8 hyllor') !== false)) {
            $shelve_8 = THINGSATWEB_BASE . '/img/8shelve.png';
            echo '<img class="mx-auto mobilheight md:medheight w-auto" src="' . $shelve_8 . '" alt="Manual" style="margin-bottom: 9px; margin-left: 10px; margin-right: 10px; border-radius: 4px; display: block;">';
          }

          if ((strpos($shelves, '9 shelves') !== false)  || (strpos($shelves, '9 hyllor') !== false)) {
            $shelve_9 = THINGSATWEB_BASE . '/img/9shelve.png';
            echo '<img class="mx-auto mobilheight md:medheight w-auto" src="' . $shelve_9 . '" alt="Manual" style="margin-bottom: 9px; margin-left: 10px; margin-right: 10px; border-radius: 4px; display: block;">';
          }

          ?>
        </div>
        <span class="w-full text-[12px] text-white font-semibold text-center p-1 bg-gray-700  hover:text-clip flex justify-center" style="overflow-wrap: break-word; word-wrap: break-word; hyphens: auto; cursor: pointer; display: inline-block; max-width: 100%;">
          <?php
          $shelves = explode(', ', $formatted_attributes['Number of shelves']);
          echo implode(' | ', $shelves);
          ?>
        </span>
      </div>
    <?php endif; ?>

  </div>
 
</div>
