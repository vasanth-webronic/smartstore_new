<?php

/**
 * Related Products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/related.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     3.9.0
 */

if (!defined('ABSPATH')) {
    exit;
}
global $product;
$productcategory=$product->get_categories();

if ($related_products) : ?>

    <section class="related products">

        <?php
        $lang = getSiteCurrentLang();
        if($lang === 'en'){
            $retitle = 'Related products';
        }elseif ($lang === 'sv') {
        $retitle = 'Relaterade produkter';
        }
        $heading = apply_filters('woocommerce_product_related_products_heading', __($retitle, 'woocommerce'));

        if ($heading) :
        ?>
            <h3 class="font-semibold text-xl xl:text-2xl mt-6"><?php echo esc_html($heading); ?></h3>
        <?php endif; ?>

        <?php woocommerce_product_loop_start(); ?>


        <div class="grid grid-cols-1 sm:hidden  sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3 lg:gap-5 xl:gap-8">
            <?php foreach ($related_products as $related_product) : ?>

                <?php
                $post_object = get_post($related_product->get_id());

                setup_postdata($GLOBALS['post'] = &$post_object); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited, Squiz.PHP.DisallowMultipleAssignments.Found

                wc_get_template_part('content', 'product');
                ?>
            <?php endforeach; ?>

        </div>

        
        <?php woocommerce_product_loop_end(); ?>
        <?php woocommerce_product_loop_start(); ?>
        <!-- <head> -->
            <!-- <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick.css" />
            <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick.min.js"></script> -->
        <!-- </head> -->
<style>
    @media (max-width: 767px) { /* Show on small screens (up to 767px) */
        .slick-carousel {
            display: block;
        }
    }

    @media (min-width: 768px) { /* Hide on medium and larger screens (from 768px and above) */
        .slick-carousel {
            display: none;
        }
    }
</style>
<div class="slick-carousel" style="margin: 0px 20px;">
<?php foreach ($related_products as $related_product) : ?>

<?php
$post_object = get_post($related_product->get_id());

setup_postdata($GLOBALS['post'] = &$post_object); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited, Squiz.PHP.DisallowMultipleAssignments.Found
?>
<div class="w-full md:hidden lg:hidden">
<div class="flex">
<div class="mx-auto border p-3 rounded-md" style="width:75%;">
    <a href="<?php echo get_permalink();?>" class="text-black" style="text-decoration: none;">  
        <?php
            $image = get_the_post_thumbnail_url($related_product->get_id());
            $default = wc_placeholder_img_src(200);
            if(empty($image)) { $image = $default; }
        ?>
        <div class=" !w-auto flex items-center justify-center"  style="height:120px">
            <img class=" mx-auto" style="width: 120px; height: 100px;" src=<?php  echo $image;?>
                onerror="this.onerror=null;this.src='<?php  echo $default;?>'">
        </div>
        
        <h3 class="w-full h-30 text-sm font-semibold text-center mt-3"><?php echo get_the_title() ?></h3>
        <div class="my-7 flex flex-col items-center">
            <span class="text-sm font-semibold text-red-600 py-2 block">
                <?php 
                    $price=$product->get_price_html();
                    echo product_price($product->get_price_html());
                ?>
            </span>
            <a href="<?php echo get_permalink();?>" >  
            <?php if ((strpos($productcategory, "Tailor-made product") !== false) || (strpos($productcategory, "Kundanpassad Produkt") !== false) ) : ?>
                <?php // if($product->is_type('grouped')) : ?>
                <?php  
            $imageurl = THINGSATWEB_BASE . '/img/Group-121.png';
            $type = pathinfo($imageurl, PATHINFO_EXTENSION);
            $image_data = file_get_contents($imageurl);
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($image_data);
            echo '<img class="filter-item-image mx-auto my-0 md:my-4 !h-6 sm:!h-9 md:!h-11 w-auto" src="' . $base64 . '" alt="Manual">';
        ?>
                <?php else : ?>
                <button class="text-sm bg-red-600 w-24 px-6 py-2 text-white rounded-full hover:bg-black">
                    <?php echo product_buy_or_quote($price);?>
                </button>
                <?php endif; ?>
            </a>
        </div>
        <!-- <img class="w-4 mx-0" src="<?php //echo THINGSATWEB_BASE.'/img/heart-o.svg';?>" alt=""> -->
    </a>

</div>

</div>
</div>
<?php endforeach; ?>
    

</div>
<script>
    // Initialize the slick carousel after the content is loaded
    jQuery(document).ready(function($) {
        // Only initialize the carousel on larger screens
        $('.slick-carousel').slick({
            dots: false, // If you want to show dots navigation
            arrows: true, // If you want to show arrows navigation
            slidesToShow: 1, // Number of slides to show at a time
            slidesToScroll: 1, // Number of slides to scroll at a time
            // Add other slick settings as per your requirement
        });

        // Hide the next arrow on the last slide
       
    });
</script>
<?php woocommerce_product_loop_end(); ?>


    </section>
<?php
endif;

wp_reset_postdata();



