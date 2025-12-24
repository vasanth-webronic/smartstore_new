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
    $diagram = $meta['article_price']['product_diagram_file']['url'];
    ?>
    <div style="margin-left: 70px; width: 600px; height: 600px; overflow: hidden;">
        <?php 
        echo '<img src="' . esc_url($diagram) . '" alt="Manual" style="margin-bottom: 9px; margin-top: 50px; margin-right: 10px; width: 100%; height: auto;">';
        ?>
    </div>
<?php 
} ?>