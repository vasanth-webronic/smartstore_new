<?php
/**
 * Single Product Price
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/price.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;

?>
<p class="w-full font-semibold text-lg xl:text-xl text-center lg:text-start text-red-600 mt-4"><?php echo product_price($product->get_price_html()); ?></p>
<?php
// $product_price_html = product_price($product->get_price_html());

// if ($product_price_html !== '-') {
//     echo '<p class="w-full font-semibold text-lg xl:text-xl text-center lg:text-start text-red-600 mt-4">' . $product_price_html . '</p>';
// }
?>
