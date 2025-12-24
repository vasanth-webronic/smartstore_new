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
<?php if (
  isset($formatted_attributes['Loading Width']) || isset($formatted_attributes['Loading Depth'])|| isset($formatted_attributes['Height']) 
  || isset($formatted_attributes['Rack Depth']) || isset($formatted_attributes['Width']) || isset($formatted_attributes['Rack Depth']) 
  || isset($formatted_attributes['Section width']) || isset($formatted_attributes['Section Height'])
  || isset($formatted_attributes['Depth']) || isset($formatted_attributes['Loading height'])
  ) : ?>
  <div class="w-full flex flex-wrap">
 
    <?php if (isset($formatted_attributes['Loading Width'])) : 
       $loadingwidthvalue = explode(', ',$formatted_attributes['Loading Width']);
       $loadingwidthcount = count($loadingwidthvalue);
       if ($loadingwidthcount >= '1') :?>
        <div class="w-1/2 loadspace p-1 ">
          <div class="flex-1 border p-2 items-center" style="height:180px;">
            <span class="text-xs font-semibold w-1/3 md:w-2/5">
              <?php if ($lang == "en") { ?> LOADING WIDTH <?php } elseif ($lang == "sv") { ?> Lastbredd <?php } ?>
            </span>
              <div class="flex border p-2 items-center my-1 relative justify-center" style="margin-bottom: -1px; padding-left: 20px; padding-right: 20px; border-color: #C5C5C5;">
                <?php
                    $loadwidth_url = THINGSATWEB_BASE . '/img/Loading-Width.png';
                    echo '<img class="filter-item-image mobheight mx-auto my-0 md:my-4  md:!h-11 w-auto"  src="' . $loadwidth_url . '" alt="Manual" style="margin-bottom: 9px;  border-radius: 4px;">';
                ?>
              </div>
              <span class="w-full text-[11px] text-white font-semibold text-center p-1 bg-gray-700  hover:text-clip flex justify-center" style="overflow-wrap: break-word; word-wrap: break-word; hyphens: auto; cursor: pointer; display: inline-block; max-width: 100%;">
                <?php
                echo $formatted_attributes['Loading Width'];
                ?>
              </span>
          </div>
        </div>
      <?php endif; ?>
      <?php endif; ?>

      <?php if (isset($formatted_attributes['Loading Depth'])) : 
         $loadingdepthvalue = explode(', ',$formatted_attributes['Loading Depth']);
         $loadingdepthcount = count($loadingdepthvalue);
         if ($loadingdepthcount >= '1') :?>
        <div class="w-1/2 loadspace p-1 ">
          <div class="flex-1 border p-2 items-center" style="height:180px;">
            <span class="text-xs font-semibold w-1/3 md:w-2/5">
              <?php if ($lang == "en") { ?> LOADING DEPTH <?php } elseif ($lang == "sv") { ?> Lastdjup <?php } ?>
            </span>
              <div class="flex border p-2 items-center my-1 relative justify-center" style="margin-bottom: -1px; padding-left: 20px; padding-right: 20px; border-color: #C5C5C5;">
                <?php
                    $loaddepth_url = THINGSATWEB_BASE . '/img/Loading-Depth.png';
                    echo '<img class="filter-item-image mobheight mx-auto my-0 md:my-4  md:!h-11 w-auto"  src="' . $loaddepth_url . '" alt="Manual" style="margin-bottom: 9px;  border-radius: 4px;">';
                ?>
              </div>
              <span class="w-full text-[11px] text-white font-semibold text-center p-1 bg-gray-700  hover:text-clip flex justify-center" style="overflow-wrap: break-word; word-wrap: break-word; hyphens: auto; cursor: pointer; display: inline-block; max-width: 100%;">
                <?php
                echo $formatted_attributes['Loading Depth'];
                ?>
              </span>
          </div>
        </div>
      <?php endif; ?>
      <?php endif; ?>

      <?php if (isset($formatted_attributes['Height'])) : 
        $heightvalue = explode(', ',$formatted_attributes['Height']);
        $heightcount = count($heightvalue);
        if ($heightcount >= '1') :?>
        <div class="w-1/2 loadspace p-1 ">
          <div class="flex-1 border p-2 items-center" style="height:180px;">
            <span class="text-xs font-semibold w-1/3 md:w-2/5">
              <?php if ($lang == "en") { ?> HEIGHT <?php } elseif ($lang == "sv") { ?> Höjd <?php } ?>
            </span>
              <div class="flex border p-2 items-center my-1 relative justify-center" style="margin-bottom: -1px; padding-left: 20px; padding-right: 20px; border-color: #C5C5C5;">
                <?php
                    $height_url = THINGSATWEB_BASE . '/img/Height.png';
                    echo '<img class="filter-item-image mobheight mx-auto my-0 md:my-4  md:!h-11 w-auto"  src="' . $height_url . '" alt="Manual" style="margin-bottom: 9px;  border-radius: 4px;">';
                ?>
              </div>
              <span class="w-full text-[11px] text-white font-semibold text-center p-1 bg-gray-700  hover:text-clip flex justify-center" style="overflow-wrap: break-word; word-wrap: break-word; hyphens: auto; cursor: pointer; display: inline-block; max-width: 100%;">
                <?php
                echo $formatted_attributes['Height'];
                ?>
              </span>
          </div>
        </div>
      <?php endif; ?>
      <?php endif; ?>

      <?php if (isset($formatted_attributes['Rack Depth'])) : 
        $rackdepthvalue = explode(', ',$formatted_attributes['Rack Depth']);
        $rackdepthcount = count($rackdepthvalue);
        if ($rackdepthcount >= '1') :?>
        <div class="w-1/2 loadspace p-1 ">
          <div class="flex-1 border p-2 items-center" style="height:180px;">
            <span class="text-xs font-semibold w-1/3 md:w-2/5">
              <?php if ($lang == "en") { ?> RACK DEPTH <?php } elseif ($lang == "sv") { ?> Djup pallställ <?php } ?>
            </span>
              <div class="flex border p-2 items-center my-1 relative justify-center" style="margin-bottom: -1px; padding-left: 20px; padding-right: 20px; border-color: #C5C5C5;">
                <?php
                    $rackdepth_url = THINGSATWEB_BASE . '/img/Rack-Depth.png';
                    echo '<img class="filter-item-image mobheight mx-auto my-0 md:my-4  md:!h-11 w-auto"  src="' . $rackdepth_url . '" alt="Manual" style="margin-bottom: 9px;  border-radius: 4px;">';
                ?>
              </div>
              <span class="w-full text-[11px] text-white font-semibold text-center p-1 bg-gray-700  hover:text-clip flex justify-center" style="overflow-wrap: break-word; word-wrap: break-word; hyphens: auto; cursor: pointer; display: inline-block; max-width: 100%;">
                <?php
                echo $formatted_attributes['Rack Depth'];
                ?>
              </span>
          </div>
        </div>
      <?php endif; ?>
      <?php endif; ?>

      <?php if (isset($formatted_attributes['Section width'])) : 
         $sectionwidthvalue = explode(', ',$formatted_attributes['Section width']);
         $sectionwidthcount = count($sectionwidthvalue);
         if ($sectionwidthcount >= '1') :?>
        <div class="w-1/2 loadspace p-1 ">
          <div class="flex-1 border p-2 items-center" style="height:180px;">
            <span class="text-xs font-semibold w-1/3 md:w-2/5">
              <?php if ($lang == "en") { ?> SECTION WIDTH <?php } elseif ($lang == "sv") { ?> Sektionsbredd <?php } ?>
            </span>
              <div class="flex border p-2 items-center my-1 relative justify-center" style="margin-bottom: -1px; padding-left: 20px; padding-right: 20px; border-color: #C5C5C5;">
                <?php
                    $height_url = THINGSATWEB_BASE . '/img/SectionWidth.png';
                    echo '<img class="filter-item-image mobheight mx-auto my-0 md:my-4  md:!h-11 w-auto"  src="' . $height_url . '" alt="Manual" style="margin-bottom: 9px;  border-radius: 4px;">';
                ?>
              </div>
              <span class="w-full text-[11px] text-white font-semibold text-center p-1 bg-gray-700  hover:text-clip flex justify-center" style="overflow-wrap: break-word; word-wrap: break-word; hyphens: auto; cursor: pointer; display: inline-block; max-width: 100%;">
                <?php
                echo $formatted_attributes['Section width'];
                ?>
              </span>
          </div>
        </div>
      <?php endif; ?>
      <?php endif; ?>
      
      <?php if (isset($formatted_attributes['Section Height'])) : 
        $sectionheightvalue = explode(', ',$formatted_attributes['Section Height']);
        $sectionheightcount = count($sectionheightvalue);
        if ($sectionheightcount >= '1') :?>
        <div class="w-1/2 loadspace p-1 ">
          <div class="flex-1 border p-2 items-center" style="height:180px;">
            <span class="text-xs font-semibold w-1/3 md:w-2/5">
              <?php if ($lang == "en") { ?> SECTION HEIGHT <?php } elseif ($lang == "sv") { ?> Sektionshöjd <?php } ?>
            </span>
              <div class="flex border p-2 items-center my-1 relative justify-center" style="margin-bottom: -1px; padding-left: 20px; padding-right: 20px; border-color: #C5C5C5;">
                <?php
                    $height_url = THINGSATWEB_BASE . '/img/Sectionheight.png';
                    echo '<img class="filter-item-image mobheight mx-auto my-0 md:my-4  md:!h-11 w-auto"  src="' . $height_url . '" alt="Manual" style="margin-bottom: 9px;  border-radius: 4px;">';
                ?>
              </div>
              <span class="w-full text-[11px] text-white font-semibold text-center p-1 bg-gray-700  hover:text-clip flex justify-center" style="overflow-wrap: break-word; word-wrap: break-word; hyphens: auto; cursor: pointer; display: inline-block; max-width: 100%;">
                <?php
                echo $formatted_attributes['Section Height'];
                ?>
              </span>
          </div>
        </div>
      <?php endif; ?>
      <?php endif; ?>

      <?php if (isset($formatted_attributes['Width'])) : 
        $widthvalue = explode(', ',$formatted_attributes['Width']);
        $widthcount = count($widthvalue);
        if ($widthcount >= '1') :?>
        <div class="w-1/2 loadspace p-1 ">
          <div class="flex-1 border p-2 items-center" style="height:180px;">
            <span class="text-xs font-semibold w-1/3 md:w-2/5">
              <?php if ($lang == "en") { ?> WIDTH <?php } elseif ($lang == "sv") { ?> Bredd <?php } ?>
            </span>
              <div class="flex border p-2 items-center my-1 relative justify-center" style="margin-bottom: -1px; padding-left: 20px; padding-right: 20px; border-color: #C5C5C5;">
                <?php
                    $width_url = THINGSATWEB_BASE . '/img/Width.png';
                    echo '<img class="filter-item-image mobheight mx-auto my-0 md:my-4  md:!h-11 w-auto"  src="' . $width_url . '" alt="Manual" style="margin-bottom: 9px;  border-radius: 4px;">';
                ?>
              </div>
              <span class="w-full text-[11px] text-white font-semibold text-center p-1 bg-gray-700  hover:text-clip flex justify-center" style="overflow-wrap: break-word; word-wrap: break-word; hyphens: auto; cursor: pointer; display: inline-block; max-width: 100%;">
                <?php
                echo $formatted_attributes['Width'];
                ?>
              </span>
          </div>
        </div>
      <?php endif; ?>
      <?php endif; ?>

      <?php if (isset($formatted_attributes['Length'])) : 
        $lengthvalue = explode(', ',$formatted_attributes['Length']);
        $lengthcount = count($lengthvalue);
        if ($lengthcount >= '1') :?>
        <div class="w-1/2 loadspace p-1 ">
          <div class="flex-1 border p-2 items-center" style="height:180px;">
            <span class="text-xs font-semibold w-1/3 md:w-2/5">
              <?php if ($lang == "en") { ?> LENGTH <?php } elseif ($lang == "sv") { ?> Längd <?php } ?>
            </span>
              <div class="flex border p-2 items-center my-1 relative justify-center" style="margin-bottom: -1px; padding-left: 20px; padding-right: 20px; border-color: #C5C5C5;">
                <?php
                    $length_url = THINGSATWEB_BASE . '/img/Length.png';
                    echo '<img class="filter-item-image mobheight mx-auto my-0 md:my-4  md:!h-11 w-auto"  src="' . $length_url . '" alt="Manual" style="margin-bottom: 9px;  border-radius: 4px;">';
                ?>
              </div>
              <span class="w-full text-[11px] text-white font-semibold text-center p-1 bg-gray-700  hover:text-clip flex justify-center" style="overflow-wrap: break-word; word-wrap: break-word; hyphens: auto; cursor: pointer; display: inline-block; max-width: 100%;">
                <?php
                echo $formatted_attributes['Length'];
                ?>
              </span>
          </div>
        </div>
      <?php endif; ?>
      <?php endif; ?>

      <?php if (isset($formatted_attributes['Depth'])) : 
        $depthvalue = explode(', ',$formatted_attributes['Depth']);
        $depthcount = count($depthvalue);
        if ($depthcount >= '1') :?>
        <div class="w-1/2 loadspace p-1 ">
          <div class="flex-1 border p-2 items-center" style="height:180px;">
            <span class="text-xs font-semibold w-1/3 md:w-2/5">
              <?php if ($lang == "en") { ?> DEPTH <?php } elseif ($lang == "sv") { ?> Djup <?php } ?>
            </span>
              <div class="flex border p-2 items-center my-1 relative justify-center" style="margin-bottom: -1px; padding-left: 20px; padding-right: 20px; border-color: #C5C5C5;">
                <?php
                    $depth_url = THINGSATWEB_BASE . '/img/Depth.png';
                    echo '<img class="filter-item-image mobheight mx-auto my-0 md:my-4  md:!h-11 w-auto"  src="' . $depth_url . '" alt="Manual" style="margin-bottom: 9px;  border-radius: 4px;">';
                ?>
              </div>
              <span class="w-full text-[11px] text-white font-semibold text-center p-1 bg-gray-700  hover:text-clip flex justify-center" style="overflow-wrap: break-word; word-wrap: break-word; hyphens: auto; cursor: pointer; display: inline-block; max-width: 100%;">
                <?php
                echo $formatted_attributes['Depth'];
                ?>
              </span>
          </div>
        </div>
      <?php endif; ?>
      <?php endif; ?>

      <?php if (isset($formatted_attributes['Loading height'])) : 
         $loadingheightvalue = explode(', ',$formatted_attributes['Loading height']);
         $loadingheightcount = count($loadingheightvalue);
         if ($loadingheightcount >= '1') :?>
        <div class="w-1/2 loadspace p-1 ">
          <div class="flex-1 border p-2 items-center" style="height:180px;">
            <span class="text-xs font-semibold w-1/3 md:w-2/5">
              <?php if ($lang == "en") { ?> LOADING HEIGHT <?php } elseif ($lang == "sv") { ?> Höjd <?php } ?>
            </span>
              <div class="flex border p-2 items-center my-1 relative justify-center" style="margin-bottom: -1px; padding-left: 20px; padding-right: 20px; border-color: #C5C5C5;">
                <?php
                    $loadheight_url = THINGSATWEB_BASE . '/img/Loading-height.png';
                    echo '<img class="filter-item-image mobheight mx-auto my-0 md:my-4  md:!h-11 w-auto"  src="' . $loadheight_url . '" alt="Manual" style="margin-bottom: 9px;  border-radius: 4px;">';
                ?>
              </div>
              <span class="w-full text-[11px] text-white font-semibold text-center p-1 bg-gray-700  hover:text-clip flex justify-center" style="overflow-wrap: break-word; word-wrap: break-word; hyphens: auto; cursor: pointer; display: inline-block; max-width: 100%;">
                <?php
                echo $formatted_attributes['Loading height'];
                ?>
              </span>
          </div>
        </div>
      <?php endif; ?>
      <?php endif; ?>
    </div>
    <?php endif; ?>
</div>