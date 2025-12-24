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
$meta  = get_post_meta($product->get_id(), 'taw_prod_opt');
$meta = isset($meta[0]) ? $meta[0] : array();
if (isset($meta['article_price']['product_diagram_file']['url']) && !empty($meta['article_price']['product_diagram_file']['url'])) {
    $diagram1 = $meta['article_price']['product_diagram_file']['url'];
    ?>
    <div class="diagram-container" style="margin-left: 0px;  width: 100%;  height: auto; overflow: hidden;">
        <?php 
        echo '<img class="diagram-image" src="' . esc_url($diagram1) . '" alt="Manual" style="margin-bottom: 9px; margin-top: 50px; margin-right: 10px; width: 100%; height: auto;">';
        ?>
    </div>
<?php 
} ?>
<style>
    /* Default styles for larger screens */
.diagram-container {
    display: block;
}

/* Hide the image on screens with a max-width of 600px or less (typical mobile devices) */
@media only screen and (max-width: 600px) {
    .diagram-container {
        width: 100%; 
    }
}
</style>
<?php
if ((isset($meta['article_price']['product_diagram_file2']['url']) && !empty($meta['article_price']['product_diagram_file2']['url'])) || (isset($meta['article_price']['product_diagram_file3']['url']) && !empty($meta['article_price']['product_diagram_file3']['url']))) {
    $diagram2 = $meta['article_price']['product_diagram_file2']['url'];
    $diagram3 = $meta['article_price']['product_diagram_file3']['url'];
    ?>
    <div class="diagram-container" style="margin-left: 0px; width: 100%;  height: auto; overflow: hidden; display: flex;">
        <?php if(!empty($meta['article_price']['product_diagram_file2']['url'])){ ?>
            <div style="flex: 0 0 70%; margin-right: 10px;">
                <?php 
                echo '<img class="diagram-image" src="' . esc_url($diagram2) . '" alt="Diagram 2" style="width: 100%; height: auto;">';
                ?>
            </div>
        <?php } ?>
        <?php if(!empty($meta['article_price']['product_diagram_file3']['url'])){ ?>
        <div style="flex: 0 0 30%;">
            <?php 
            echo '<img class="diagram-image" src="' . esc_url($diagram3) . '" alt="Diagram 3" style="width: 100%; height: auto;">';
            ?>
        </div>
        <?php } ?>
    </div>
<?php } ?>