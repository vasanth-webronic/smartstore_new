<?php
/**
 * Single Product title
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/title.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see        https://docs.woocommerce.com/document/template-structure/
 * @package    WooCommerce\Templates
 * @version    1.6.4
 */
global $product;
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}?>


<div id="prod-text-info-hld" class="flex flex-row text-3xl md:sticky md:top-0 md:z-50 bg-white" style="padding:5px 0 10px 0;">

<h3 class="basis-1/2" id="prod-title"><?php echo $product->get_title(); ?></h3>
<div class="basis-1/2 text-right" id="prod-price">
	<p style="display:inline-block;" class="cprice <?php echo esc_attr( apply_filters( 'woocommerce_product_price_class', 'price' ) ); ?>"><span><b style="color:#d6b686;"><?php echo $product->get_price_html(); ?></b></p>
</div>
</div>
