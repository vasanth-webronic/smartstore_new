<?php
/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'woocommerce_before_checkout_form', $checkout );
$is_logined=is_user_logged_in() || current_user_can('c_uam_cap_price') || current_user_can('c_uam_cap_reseller_price');
$is_reseller = current_user_can('c_uam_cap_price') || current_user_can('c_uam_cap_reseller_price');

$is_b2b = false;

if (is_user_logged_in()) {   
    $current_user = wp_get_current_user();
    $user_roles = $current_user->roles;

	$is_b2b = in_array('custom_uam_b2b', $user_roles);
}



// If checkout registration is disabled and not logged in, the user cannot checkout.
if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
	echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) ) );
	return;
}

// chage default title
// Get the current cart
$cart = WC()->cart;
$checkout_title= get_the_title();

// Flag to check if any product has subtotal 0
$has_zero_subtotal = false;
// Get cart items
$cart_items = $cart->get_cart();

$has_subtotal =  false;

foreach ($cart_items as $item_key => $item) {
    // Get the product object
    $product = $item['data'];
    
    // Get the product price
    $price_of_prod = $product->get_price();

    if ($price_of_prod == 0 || $price_of_prod === '' || $price_of_prod === null) {
        $has_zero_subtotal = true;
    }else{
		$has_subtotal =  true;
    }
}
// Modify the title based on the flag
        if($is_reseller){
        if( $has_subtotal && $has_zero_subtotal){			

            $checkout_title = __( 'Quote requests', 'woocommerce' );
			?>
	
			<style type="text/css">
		.page-header{
		display: none;
		}
		.page-header-checkout{
		font-size: 26px;
		color: #E53935;}
		</style>
		<h1 class="page-header-checkout">
		<?php echo $checkout_title;?>
		</h1>
			<?php
        	 
        }
		if($has_subtotal && !$has_zero_subtotal){
        	 $checkout_title = __( 'Order', 'woocommerce' );

			 ?>
	
			 <style type="text/css">
		 .page-header{
		 display: none;
		 }
		 .page-header-checkout{
		 font-size: 26px;
		 color: #E53935;}
		 </style>
		 <h1 class="page-header-checkout">
		 <?php echo $checkout_title;?>
		 </h1>
			 <?php
        }


	}

?>



<form name="checkout" method="post" class="checkout woocommerce-checkout pb-20" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">

	<?php if ( $checkout->get_checkout_fields() ) : ?>

		<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

		<div class="col2-set" id="customer_details">
			<div class="col-1">
				<?php do_action( 'woocommerce_checkout_billing' ); ?>
			</div>

			<div class="col-2">
				<?php do_action( 'woocommerce_checkout_shipping' ); ?>
			</div>
		</div>

		<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

	<?php endif; ?>
	
	<?php do_action( 'woocommerce_checkout_before_order_review_heading' ); ?>
	
	<?php if ($is_logined) { $label = 'Your order'; } else { $label = 'Your quotation'; }  ?>
	<!-- <h4 class="bg-gray-200 p-3 text-black" id="order_review_heading"><?php esc_html_e( $label, 'woocommerce' ); ?></h4> -->
	
	<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

	<div id="order_review" class="woocommerce-checkout-review-order">
		<?php do_action( 'woocommerce_checkout_order_review' ); ?>
	</div>

	<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>

</form>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
