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

      $formatted_attributes[$attribute_label] = implode(' | ', wc_get_product_terms($product->id, $attribute['name'], array('fields' => 'names')));
    } else {

      $formatted_attributes[$attribute_label] = $attribute['value'];
    }
  }
}

// echo "<script>console.log('meth1picking: " . json_encode($formatted_attributes) . "' );</script>";
// return $formatted_attributes;
//print_r($formatted_attributes);

// print_r($product_attributes);
// $loadMethodValues = $formatted_attributes['Type of load'];
// $valuesArray = explode(', ', $formatted_attributes['Type of load']);
// $count = count($valuesArray);
//print_r($count);
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
      margin-top: 30px;

    }

    .shortlongheight {
      height: 60px !important;
      margin-top: 15px;
    }
  </style>
  <?php
  // $loadMethodValues = $formatted_attributes['Load metod'];
  // $loadmethodvaluesArray = explode(', ', $loadMethodValues);
  // $loadmetodcount = count($loadmethodvaluesArray);
  ?>
  <div class="w-full flex flex-wrap">
    <?php if (isset($formatted_attributes['Weight capacity'])) :
      $Weightcapvalue = explode(', ', $formatted_attributes['Weight capacity']);
      $weightcapacitycount = count($Weightcapvalue);
      if ($weightcapacitycount >= '1') : ?>
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
            $weightcapacity =$formatted_attributes['Weight capacity'];
            echo  $weightcapacity;
            ?>
          </span>
        </div>
      <?php endif; ?>
    <?php endif; ?>

    <?php if (isset($formatted_attributes['Extension'])) :
      $extentionvalue = explode(', ', $formatted_attributes['Extension']);
      $extensioncount = count($extentionvalue);
      if ($extensioncount >= '1') : ?>
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
            if ((strpos(strtolower($extension), 'two-way 2×70%') !== false) || (strpos(strtolower($extension), 'Tvåvägs 2×70%') !== false)) {
              $extension_url = THINGSATWEB_BASE . '/img/Two-Way-70.png';
              echo '<img class="mx-auto mobilheight md:medheight w-auto" src="' . $extension_url . '" alt="Manual" style="margin-bottom: 9px; margin-left: 10px; margin-right: 10px; border-radius: 4px; display: block;">';
            }

            ?>
          </div>
          <span class="w-full text-[12px] text-white font-semibold text-center p-1 bg-gray-700  hover:text-clip flex justify-center" style="overflow-wrap: break-word; word-wrap: break-word; hyphens: auto; cursor: pointer; display: inline-block; max-width: 100%;">
            <?php
            $extension= $formatted_attributes['Extension'];
            echo $extension;
            ?>
          </span>
        </div>
      <?php endif; ?>
    <?php endif; ?>

    <?php if (isset($formatted_attributes['Colour'])) :
      $colourvalue = explode(', ', $formatted_attributes['Colour']);
      $colourcount = count($colourvalue);
      if ($colourcount >= '1') : ?>
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
            $colour = $formatted_attributes['Colour'];
            echo $colour;
            ?>
          </span>
        </div>
      <?php endif; ?>
    <?php endif; ?>

    <?php if (isset($formatted_attributes['Load metod'])) :
      $loadmetodvalue = explode(', ', $formatted_attributes['Load metod']);
      $loadmetodcount = count($loadmetodvalue);
      if ($loadmetodcount >= '1') : ?>
        <div class="w-1/2 items-center border-b-2 widspace p-2 ">
          <div class="flex border p-2 items-center my-1 relative" style="margin-bottom: -1px; padding-left: 20px; padding-right: 20px; border-color: #AEAEAE; display: flex; justify-content: center; align-items: center;">
            <span class="absolute top-0 left-0 right-0 text-[10px] text-black font-semibold text-center bg-gray-200 flex justify-center" style="width:100%;">
              <?php if ($lang == "en") { ?> LOAD METHOD <?php } elseif ($lang == "sv") { ?> Lastmetod <?php } ?>
            </span>

            <?php
            $load_method = strtolower($formatted_attributes['Load metod']);
            if ($lang == 'en') {
              if (strpos($load_method, 'by hand') !== false) {
                $byhand_url = THINGSATWEB_BASE . '/img/By_hand.png';
                echo '<img class="mx-auto mobilheight md:medheight w-auto" src="' . $byhand_url . '" alt="Manual" style="margin-bottom: 9px; width:20px; height:30px; border-radius: 4px; display: block;">';
              }

              if (strpos($load_method, 'forklift') !== false) {
                $fork_url = THINGSATWEB_BASE . '/img/ForkLift.png';
                echo '<img class="mx-auto mobilheight md:medheight w-auto" src="' . $fork_url . '" alt="Manual" style="margin-bottom: 9px; width:40px; height:30px; border-radius: 4px; display: block;">';
              }

              if (strpos($load_method, 'hand pallet truck') !== false) {
                $handpallet_url = THINGSATWEB_BASE . '/img/Hand_pallet_truck.png';
                echo '<img class="mx-auto mobilheight md:medheight w-auto" src="' . $handpallet_url . '" alt="Manual" style="margin-bottom: 9px; width:40px; height:30px; border-radius: 4px; display: block;">';
              }

              if (strpos($load_method, 'overhead crane') !== false) {
                $traverse_url = THINGSATWEB_BASE . '/img/Overheadcrane.png';
                echo '<img class="filter-item-image mobilheight md:medheight mx-auto my-0 md:my-4  md:!h-11 w-auto" src="' . $traverse_url . '" alt="Manual" style="margin-bottom: 4px; margin-top:15px; width:20px; height:30px; border-radius: 4px;">';
              }

              if (strpos($load_method, 'pallet stacker') !== false) {
                $stackers_url = THINGSATWEB_BASE . '/img/Pallet_stacker.png';
                echo '<img class="mx-auto mobilheight md:medheight w-auto" src="' . $stackers_url . '" alt="Manual" style="margin-bottom: 9px; width:40px; height:30px; border-radius: 4px; display: block;">';
              }
            }

            if ($lang == 'sv') {
              if (strpos($load_method, 'gaffeltruck') !== false) {
                $fork_url = THINGSATWEB_BASE . '/img/ForkLift.png';
                echo '<img class="mx-auto mobilheight md:medheight w-auto" src="' . $fork_url . '" alt="Manual" style="margin-bottom: 9px; width:40px; height:30px; border-radius: 4px; display: block;">';
              }
              if (strpos($load_method, 'ledstaplare') !== false) {
                $stackers_url = THINGSATWEB_BASE . '/img/Pallet_stacker.png';
                echo '<img class="mx-auto mobilheight md:medheight w-auto" src="' . $stackers_url . '" alt="Manual" style="margin-bottom: 9px; width:40px; height:30px; border-radius: 4px; display: block;">';
              }
              if (strpos($load_method, 'palldragare') !== false) {
                $handpallet_url = THINGSATWEB_BASE . '/img/Hand_pallet_truck.png';
                echo '<img class="mx-auto mobilheight md:medheight w-auto" src="' . $handpallet_url . '" alt="Manual" style="margin-bottom: 9px; width:40px; height:30px; border-radius: 4px; display: block;">';
              }
              if (strpos($load_method, 'travers') !== false) {
                $traverse_url = THINGSATWEB_BASE . '/img/Overheadcrane.png';
                echo '<img class="filter-item-image mobilheight md:medheight mx-auto my-0 md:my-4  md:!h-11 w-auto" src="' . $traverse_url . '" alt="Manual" style="margin-bottom: 4px; margin-top:15px; width:20px; height:30px; border-radius: 4px;">';
              }
              if (strpos($load_method, 'manuellt') !== false) {
                $byhand_url = THINGSATWEB_BASE . '/img/By_hand.png';
                echo '<img class="mx-auto mobilheight md:medheight w-auto" src="' . $byhand_url . '" alt="Manual" style="margin-bottom: 9px; width:20px; height:30px; border-radius: 4px; display: block;">';
              }
            }


            ?>
          </div>
          <span class="w-full text-[12px] text-white font-semibold text-center p-1 bg-gray-700  hover:text-clip flex justify-center" style="overflow-wrap: break-word; word-wrap: break-word; hyphens: auto; cursor: pointer; display: inline-block; max-width: 100%;">
            <?php
            $loadmethod = $formatted_attributes['Load metod'];
            echo $loadmethod;
            ?>
          </span>
        </div>
      <?php endif; ?>
    <?php endif; ?>

    <?php if (isset($formatted_attributes['Type of load'])) :
      $typeofloadvalue = explode(', ', $formatted_attributes['Type of load']);
      $typeofloadcount = count($typeofloadvalue);
      if ($typeofloadcount >= '1') : ?>
        <div class="w-1/2 items-center border-b-2 widspace p-2 ">
          <div class="flex border p-2 items-center my-1 relative" style="margin-bottom: -1px; padding-left: 20px; padding-right: 20px; border-color: #AEAEAE; display: flex; justify-content: center; align-items: center;">
            <span class="absolute top-0 left-0 right-0 text-[10px] text-black font-semibold text-center bg-gray-200 flex justify-center" style="width:100%;">
              <?php if ($lang == "en") { ?> TYPE OF LOAD <?php } elseif ($lang == "sv") { ?> Typ av last <?php } ?>
            </span>
            <?php
            $typeload = strtolower($formatted_attributes['Type of load']);
            if ($lang == 'en') {
              $typelo = htmlspecialchars_decode(strtolower($formatted_attributes['Type of load']));
              if (stripos($typelo, 'bins & boxes') !== false) {
                $bin_url = THINGSATWEB_BASE . '/img/PlasticBin.png';
                echo '<img class="mx-auto mobilheight md:medheight w-auto" src="' . $bin_url . '" alt="Manual" style="margin-bottom: 9px; width:50px; height:30px; margin-left: 2px; margin-right: 4px; border-radius: 4px; display: block;">';
              }
              if (strpos($typeload, '½eur-pallet') !== false) {
                $pall_url = THINGSATWEB_BASE . '/img/halfEUR.png';
                echo '<img class="mx-auto mobilheight md:medheight w-auto" src="' . $pall_url . '" alt="Manual" style="margin-bottom: 9px; width:60px; height:30px; margin-left: 2px; margin-right: 2px; border-radius: 4px; display: block;">';
              } elseif (strpos($typeload, 'eur-pallet') !== false) {
                $eur_url = THINGSATWEB_BASE . '/img/EUR.png';
                echo '<img class="mx-auto mobilheight md:medheight w-auto" src="' . $eur_url . '" alt="Manual" style="margin-bottom: 9px; width:60px; height:30px; margin-left: 2px; margin-right: 2px; border-radius: 4px; display: block;">';
              }

              if (strpos(strtolower($typeload), 'fin-/ chep-pallet') !== false) {
                $fin_url = THINGSATWEB_BASE . '/img/FIN.png';
                echo '<img class="mx-auto mobilheight md:medheight w-auto" src="' . $fin_url . '" alt="Manual" style="margin-bottom: 9px; width:60px; height:30px; margin-left: 2px; margin-right: 2px; border-radius: 4px; display: block;">';
              }

              if (strpos(strtolower($typeload), 'gitterbox') !== false) {
                $hmu_url = THINGSATWEB_BASE . '/img/HMUbox.png';
                echo '<img class="mx-auto typeheight md:medheight w-auto" src="' . $hmu_url . '" alt="Manual" style="margin-bottom: 14px; width:30px;  margin-left: 2px; margin-right: 2px; border-radius: 4px; display: block;">';
              }

              if (strpos(strtolower($typeload), 'pipes') !== false) {
                $pipe_url = THINGSATWEB_BASE . '/img/Pipe.png';
                echo '<img class="mx-auto mobilheight md:mdheight w-auto" src="' . $pipe_url . '" alt="Manual" style="margin-bottom: 9px; width:20px; height:20px; margin-left: 2px; margin-right: 2px; border-radius: 4px; display: block;">';
              }

              if (strpos(strtolower($typeload), 'steel shelf') !== false) {
                $steelshelf_url = THINGSATWEB_BASE . '/img/SteelShelf.png';
                echo '<img class="mx-auto mobilheight md:medheight w-auto" src="' . $steelshelf_url . '" alt="Manual" style="margin-bottom: 9px; width:40px; height:30px; margin-left: 2px; margin-right: 2px; border-radius: 4px; display: block;">';
              }
            }
            if ($lang == 'sv') {
              if (strpos($typeload, 'eur-pall') !== false) {
                $eur_url = THINGSATWEB_BASE . '/img/EUR.png';
                echo '<img class="mx-auto mobilheight md:medheight w-auto" src="' . $eur_url . '" alt="Manual" style="margin-bottom: 9px; width:60px; height:30px; margin-left: 2px; margin-right: 2px; border-radius: 4px; display: block;">';
              }
              if (strpos(strtolower($typeload), 'nätcontainer') !== false) {
                $hmu_url = THINGSATWEB_BASE . '/img/HMUbox.png';
                echo '<img class="mx-auto typeheight md:medheight w-auto" src="' . $hmu_url . '" alt="Manual" style="margin-bottom: 20px; width:30px;  margin-left: 2px; margin-right: 2px; border-radius: 4px; display: block;">';
              }
              if (strpos($typeload, '½ eur-pal') !== false) {
                $pall_url = THINGSATWEB_BASE . '/img/halfEUR.png';
                echo '<img class="mx-auto mobilheight md:medheight w-auto" src="' . $pall_url . '" alt="Manual" style="margin-bottom: 9px; width:60px; height:30px; margin-left: 2px; margin-right: 2px; border-radius: 4px; display: block;">';
              }
              if (strpos(strtolower($typeload), 'fin-pall') !== false) {
                $fin_url = THINGSATWEB_BASE . '/img/FIN.png';
                echo '<img class="mx-auto mobilheight md:medheight w-auto" src="' . $fin_url . '" alt="Manual" style="margin-bottom: 9px; width:60px; height:30px; margin-left: 2px; margin-right: 2px; border-radius: 4px; display: block;">';
              }
              $typelo = htmlspecialchars_decode(strtolower($formatted_attributes['Type of load']));
              if (strpos(strtolower($typelo), 'lådor') !== false) {
                $bin_url = THINGSATWEB_BASE . '/img/PlasticBin.png';
                echo '<img class="mx-auto mobilheight md:medheight w-auto" src="' . $bin_url . '" alt="Manual" style="margin-bottom: 9px; width:50px; height:30px; margin-left: 2px; margin-right: 4px; border-radius: 4px; display: block;">';
              }
              if (strpos(strtolower($typeload), 'rör') !== false) {
                $pipe_url = THINGSATWEB_BASE . '/img/Pipe.png';
                echo '<img class="mx-auto mobilheight md:mdheight w-auto" src="' . $pipe_url . '" alt="Manual" style="margin-bottom: 9px; width:20px; height:20px; margin-left: 2px; margin-right: 2px; border-radius: 4px; display: block;">';
              }
              if (strpos(strtolower($typeload), 'stålplan') !== false) {
                $steelshelf_url = THINGSATWEB_BASE . '/img/SteelShelf.png';
                echo '<img class="mx-auto mobilheight md:medheight w-auto" src="' . $steelshelf_url . '" alt="Manual" style="margin-bottom: 9px; width:40px; height:30px; margin-left: 2px; margin-right: 2px; border-radius: 4px; display: block;">';
              }
            }
            ?>
          </div>
          <span class="w-full text-[12px] text-white font-semibold text-center p-1 bg-gray-700  hover:text-clip flex justify-center" style="overflow-wrap: break-word; word-wrap: break-word; hyphens: auto; cursor: pointer; display: inline-block; max-width: 100%;">
            <?php
            $typeofload =$formatted_attributes['Type of load'];
            echo $typeofload;
            ?>
          </span>
        </div>
      <?php endif; ?>
    <?php endif; ?>

    <?php if (isset($formatted_attributes['Loading'])) :
      $loadingvalue = explode(', ', $formatted_attributes['Loading']);
      $loadingcount = count($loadingvalue);
      if ($loadingcount >= '1') : ?>
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
            $loading = $formatted_attributes['Loading'];
            echo $loading;
            ?>
          </span>
        </div>
      <?php endif; ?>
    <?php endif; ?>

    <?php if (isset($formatted_attributes['Load Way'])) :
      $loadwayvalue = explode(', ', $formatted_attributes['Load Way']);
      $loadwaycount = count($loadwayvalue);
      if ($loadwaycount >= '1') : ?>
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
            $loadway = $formatted_attributes['Load Way'];
            echo $loadway;
            ?>
          </span>
        </div>
      <?php endif; ?>
    <?php endif; ?>

    <?php if (isset($formatted_attributes['Picking Method'])) :
      $pickingvalue = explode(', ', $formatted_attributes['Picking Method']);
      $pickingcount = count($pickingvalue);
      if ($pickingcount >= '1') : ?>
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
            $pickingmethod = $formatted_attributes['Picking Method'];
            echo $pickingmethod;
            ?>
          </span>
        </div>
      <?php endif; ?>
    <?php endif; ?>

    <?php if (isset($formatted_attributes['Short or long side handled'])) :
      $shortlongsidevalue = explode(', ', $formatted_attributes['Short or long side handled']);
      $shortlongsidecount = count($shortlongsidevalue);
      if ($shortlongsidecount >= '1') : ?>
        <div class="w-1/2 items-center border-b-2 widspace p-2 ">
          <div class="flex border p-2 items-center my-1 relative" style="margin-bottom: -1px; padding-left: 20px; padding-right: 20px; border-color: #AEAEAE; display: flex; justify-content: center; align-items: center;">
            <span class="absolute top-0 left-0 right-0 text-[10px] text-black font-semibold text-center  bg-gray-200 flex justify-center" style="width:100%;">
              <?php if ($lang == "en") { ?> SHORT OR LONG SIDE HANDLED <?php } elseif ($lang == "sv") { ?> Kort- eller långsideshanterad <?php } ?>
            </span>
            <?php
            $Short_longsidehandled = strtolower($formatted_attributes['Short or long side handled']);

            if ((strpos($Short_longsidehandled, 'long side handled') !== false) || (strpos($Short_longsidehandled, 'långsideshanterad') !== false)) {
              $long_url = THINGSATWEB_BASE . '/img/LongSideHandling.png';
              echo '<img class="mx-auto shortlongheight md:medheight w-auto" src="' . $long_url . '" alt="Manual" style="margin-left: 2px; margin-right:2px; border-radius: 4px; display: block;">';
            }

            if ((strpos($Short_longsidehandled, 'short side handled') !== false) || (strpos($Short_longsidehandled, 'kortsideshanterad') !== false)) {
              $short_url = THINGSATWEB_BASE . '/img/ShortSideHandling.png';
              echo '<img class="mx-auto shortlongheight md:medheight w-auto" src="' . $short_url . '" alt="Manual" style="margin-left: 2px; margin-right:2px; border-radius: 4px; display: block;">';
            }

            if ((strpos($Short_longsidehandled, '½ eur-pallet long side handled') !== false) || (strpos($Short_longsidehandled, '½ eur-pall långsideshanterad') !== false)) {
              $halflong_url = THINGSATWEB_BASE . '/img/1by2-Long-1-300x235.png';
              echo '<img class="mx-auto mobilheight md:medheight w-auto" src="' . $halflong_url . '" alt="Manual" style="margin-left: 2px; margin-right:2px; border-radius: 4px; display: block;">';
            }

            ?>
          </div>
          <span class="w-full text-[12px] text-white font-semibold text-center p-1 bg-gray-700  hover:text-clip flex justify-center" style="overflow-wrap: break-word; word-wrap: break-word; hyphens: auto; cursor: pointer; display: inline-block; max-width: 100%;">
            <?php
            $Short_longside_handled = $formatted_attributes['Short or long side handled'];
            echo $Short_longside_handled;
            ?>
          </span>
        </div>
      <?php endif; ?>
    <?php endif; ?>

    <?php if (isset($formatted_attributes['Shelf lock'])) :
      $shelflockvalue = explode(', ', $formatted_attributes['Shelf lock']);
      $shelfloackcount = count($shelflockvalue);
      if ($shelfloackcount >= '1') : ?>
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
            $Shelf_lock = $formatted_attributes['Shelf lock'];
            echo $Shelf_lock;
            ?>
          </span>
        </div>
      <?php endif; ?>
    <?php endif; ?>

    <?php if (isset($formatted_attributes['Number of shelves'])) :
      $numberofshelvesvalue = explode(', ', $formatted_attributes['Number of shelves']);
      $numberofshelvcount = count($numberofshelvesvalue);
      if ($numberofshelvcount >= '1') : ?>
        <div class="w-1/2 items-center border-b-2 widspace p-2 ">
          <div class="flex border p-2 items-center my-1 relative" style="margin-bottom: -1px; padding-left: 20px; padding-right: 20px; border-color: #AEAEAE; display: flex; justify-content: center; align-items: center;">
            <span class="absolute top-0 left-0 right-0 text-[10px] text-black font-semibold text-center  bg-gray-200 flex justify-center" style="width:100%;">
              <?php if ($lang == "en") { ?> NUMBER OF SHELVES <?php } elseif ($lang == "sv") { ?> Antal hyllor <?php } ?>
            </span>
            <?php

            $shelves = strtolower($formatted_attributes['Number of shelves']);
            $shelvesNumber = preg_replace("/[^0-9]/", "", $shelves);
            // $shelvesNumberswedish = preg_replace("/[^0-9]/", "", $shelves);

            if ($shelvesNumber == '1')   {
              $shelve_1 = THINGSATWEB_BASE . '/img/1shelve.png';
              echo '<img class="mx-auto mobilheight md:medheight w-auto" src="' . $shelve_1 . '" alt="Manual" style="margin-bottom: 9px; margin-left: 10px; margin-right: 10px; border-radius: 4px; display: block;">';
            }
            elseif ($shelvesNumber == '2')  {
              $shelve_2 = THINGSATWEB_BASE . '/img/2shelve.png';
              echo '<img class="mx-auto mobilheight md:medheight w-auto" src="' . $shelve_2 . '" alt="Manual" style="margin-bottom: 9px; margin-left: 10px; margin-right: 10px; border-radius: 4px; display: block;">';
            }

            elseif ($shelvesNumber == '3') {
              $shelve_3 = THINGSATWEB_BASE . '/img/3shelve.png';
              echo '<img class="mx-auto mobilheight md:medheight w-auto" src="' . $shelve_3 . '" alt="Manual" style="margin-bottom: 9px; margin-left: 10px; margin-right: 10px; border-radius: 4px; display: block;">';
            }

            elseif ($shelvesNumber == '4')  {
              $shelve_4 = THINGSATWEB_BASE . '/img/4shelve.png';
              echo '<img class="mx-auto mobilheight md:medheight w-auto" src="' . $shelve_4 . '" alt="Manual" style="margin-bottom: 9px; margin-left: 10px; margin-right: 10px; border-radius: 4px; display: block;">';
            }

            elseif ($shelvesNumber == '5')  {
              $shelve_5 = THINGSATWEB_BASE . '/img/5shelve.png';
              echo '<img class="mx-auto mobilheight md:medheight w-auto" src="' . $shelve_5 . '" alt="Manual" style="margin-bottom: 9px; margin-left: 10px; margin-right: 10px; border-radius: 4px; display: block;">';
            }

            elseif ($shelvesNumber == '6')  {
              $shelve_6 = THINGSATWEB_BASE . '/img/6shelve.png';
              echo '<img class="mx-auto mobilheight md:medheight w-auto" src="' . $shelve_6 . '" alt="Manual" style="margin-bottom: 9px; margin-left: 10px; margin-right: 10px; border-radius: 4px; display: block;">';
            }

            elseif ($shelvesNumber == '7') {
              $shelve_7 = THINGSATWEB_BASE . '/img/7shelve.png';
              echo '<img class="mx-auto mobilheight md:medheight w-auto" src="' . $shelve_7 . '" alt="Manual" style="margin-bottom: 9px; margin-left: 10px; margin-right: 10px; border-radius: 4px; display: block;">';
            }

            elseif ($shelvesNumber == '8') {
              $shelve_8 = THINGSATWEB_BASE . '/img/8shelve.png';
              echo '<img class="mx-auto mobilheight md:medheight w-auto" src="' . $shelve_8 . '" alt="Manual" style="margin-bottom: 9px; margin-left: 10px; margin-right: 10px; border-radius: 4px; display: block;">';
            }

            elseif ($shelvesNumber == '9') {
              $shelve_9 = THINGSATWEB_BASE . '/img/9shelve.png';
              echo '<img class="mx-auto mobilheight md:medheight w-auto" src="' . $shelve_9 . '" alt="Manual" style="margin-bottom: 9px; margin-left: 10px; margin-right: 10px; border-radius: 4px; display: block;">';
            }

            elseif ($shelvesNumber == '10')   {
              $shelve_10 = THINGSATWEB_BASE . '/img/10shelve.png';
              echo '<img class="mx-auto mobilheight md:medheight w-auto" src="' . $shelve_10 . '" alt="Manual" style="margin-bottom: 9px; margin-left: 10px; margin-right: 10px; border-radius: 4px; display: block;">';
            }

            elseif ($shelvesNumber == '11') {
              $shelve_11 = THINGSATWEB_BASE . '/img/11shelve.png';
              echo '<img class="mx-auto mobilheight md:medheight w-auto" src="' . $shelve_11 . '" alt="Manual" style="margin-bottom: 9px; margin-left: 10px; margin-right: 10px; border-radius: 4px; display: block;">';
            }

            ?>
          </div>
          <span class="w-full text-[12px] text-white font-semibold text-center p-1 bg-gray-700  hover:text-clip flex justify-center" style="overflow-wrap: break-word; word-wrap: break-word; hyphens: auto; cursor: pointer; display: inline-block; max-width: 100%;">
            <?php
            $shelves = $formatted_attributes['Number of shelves'];
            echo $shelves;
            ?>
          </span>
        </div>
      <?php endif; ?>
    <?php endif; ?>


    <?php if (isset($formatted_attributes['Mounted onto product'])) :
      $mountvalue = explode(', ', $formatted_attributes['Mounted onto product']);
      $mountcount = count($mountvalue);
      if ($mountcount >= '1') : ?>
        <div class="w-1/2 items-center border-b-2 widspace p-2 ">
          <div class="flex border p-2 items-center my-1 relative" style="margin-bottom: -1px; padding-left: 20px; padding-right: 20px; border-color: #AEAEAE; display: flex; justify-content: center; align-items: center;">
            <span class="absolute top-0 left-0 right-0 text-[10px] text-black font-semibold text-center  bg-gray-200 flex justify-center" style="width:100%;">
              <?php if ($lang == "en") { ?> Mounted onto product <?php } elseif ($lang == "sv") { ?> Monterad på produkt <?php } ?>
            </span>
            <?php
            $mounted = strtolower($formatted_attributes['Mounted onto product']);

            if (strpos($mounted, 'yes') !== false || strpos($mounted, 'ja') !== false) {
              $yes_url = THINGSATWEB_BASE . '/img/Mounted-yes.png';
              echo '<img class="mx-auto mobilheight md:medheight w-auto" src="' . $yes_url . '" alt="Manual" style="margin-bottom: 9px; width:70px; margin-left: 2px; margin-right: 2px; border-radius: 4px; display: block;">';
            }

            if ((strpos($mounted, 'no') !== false) || (strpos($mounted, 'nej') !== false)) {
              $no_url = THINGSATWEB_BASE . '/img/Mounted-no.png';
              echo '<img class="mx-auto mobilheight md:medheight w-auto" src="' . $no_url . '" alt="Manual" style="margin-bottom: 9px;width:70px; margin-left: 2px; margin-right: 2px; border-radius: 4px; display: block;">';
            }

            ?>
          </div>
          <span class="w-full text-[12px] text-white font-semibold text-center p-1 bg-gray-700  hover:text-clip flex justify-center" style="overflow-wrap: break-word; word-wrap: break-word; hyphens: auto; cursor: pointer; display: inline-block; max-width: 100%;">
            <?php
            $mountedvalue = $formatted_attributes['Mounted onto product'];
            echo $mountedvalue;
            ?>
          </span>
        </div>
      <?php endif; ?>
    <?php endif; ?>


  </div>

</div>