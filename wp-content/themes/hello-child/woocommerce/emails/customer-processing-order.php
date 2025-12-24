<?php
/**
 * Customer processing order email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/customer-processing-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates\Emails
 * @version 3.7.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * @hooked WC_Emails::email_header() Output the email header
 */
if (is_user_logged_in()) {   
    $current_user = wp_get_current_user();
    $user_roles = $current_user->roles;
    $order_items           = $order->get_items( apply_filters( 'woocommerce_purchase_order_item_types', 'line_item' ) );
$has_zero_subtotal = false;

$has_subtotal =  false;
foreach ( $order_items as $item_id => $item ) {
	$price_of_prod = product_price($order->get_formatted_order_total($item));

	$product = $item->get_product();
    $price_of_prod = $product->get_price();


    if ($price_of_prod == 0 || $price_of_prod === '' || $price_of_prod === null) {
		$has_zero_subtotal = true;
    }else{
		$has_subtotal =  true;
	}
}

$is_b2b = false;

if (is_user_logged_in()) {   
    $current_user = wp_get_current_user();
    $user_roles = $current_user->roles;

	$is_b2b = in_array('custom_uam_b2b', $user_roles);
}
// die();

    // Check if the user has the "custom_uam_reseller_eur" role
    if ((in_array('custom_uam_reseller_eur', $user_roles)) || (in_array('custom_uam_reseller_sek', $user_roles)) || (in_array('custom_uam_b2b', $user_roles))){
		//$email_heading='Smart Storing has registered your request.';
		if($has_zero_subtotal && $has_subtotal){
			$email_heading = __( 'Smart Storing has registered your request', 'TAW_TEXT_DOMAIN');
		}
		if($has_subtotal && !$has_zero_subtotal){
		$email_heading = __('Smart Storing has registered your order','woocommerce');
		}
		if($has_zero_subtotal && !$has_subtotal){
			$email_heading = __('Smart Storing has registered your request for quotation','TAW_TEXT_DOMAIN');
		}
	}

	if($is_b2b){
		
			$email_heading = __( 'Smart Storing has registered your request','TAW_TEXT_DOMAIN');

	}
}
do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

<?php /* translators: %s: Customer first name */ ?>
<p><?php printf( esc_html__( 'Dear %s,', 'TAW_TEXT_DOMAIN' ), esc_html( $order->get_billing_first_name() ) ); ?></p>
<?php /* translators: %s: Order number */ ?>
<p><?php printf( esc_html__('Thank you for interest in our products. You will be contacted within shortly', 'TAW_TEXT_DOMAIN' ), esc_html( $order->get_order_number() ) ); ?>!</p>


<?php

/*
 * @hooked WC_Emails::order_details() Shows the order details table.
 * @hooked WC_Structured_Data::generate_order_data() Generates structured data.
 * @hooked WC_Structured_Data::output_structured_data() Outputs structured data.
 * @since 2.5.0
 */
do_action( 'woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email );

/*
 * @hooked WC_Emails::order_meta() Shows order meta data.
 */
do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email );

/*
 * @hooked WC_Emails::customer_details() Shows customer details
 * @hooked WC_Emails::email_address() Shows email address
 */
do_action( 'woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, $email );

/**
 * Show user-defined additional content - this is set in each email's settings.
 */
if ( $additional_content ) {
//	echo wp_kses_post( wpautop( wptexturize( $additional_content ) ) );
}

/*
 * @hooked WC_Emails::email_footer() Output the email footer
 */
do_action( 'woocommerce_email_footer', $email );
