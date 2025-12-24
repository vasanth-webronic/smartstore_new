<?php
/**
 * Thankyou page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/thankyou.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.7.0
 */

defined( 'ABSPATH' ) || exit;
$is_logined = is_user_logged_in() || current_user_can('c_uam_cap_price') || current_user_can('c_uam_cap_reseller_price');

$is_reseller = current_user_can('c_uam_cap_price') || current_user_can('c_uam_cap_reseller_price');



if($is_reseller){

$checkout_title= get_the_title();

$order_items  = $order->get_items( apply_filters( 'woocommerce_purchase_order_item_types', 'line_item' ) );
$has_zero_subtotal = false;

$has_subtotal =  false;


foreach ( $order_items as $item_id => $item ) {
	    // Get the product object
		$product = $item->get_product();
    
		// Get the product price
		$price_of_prod = $product->get_price();

		if ($price_of_prod == 0 || $price_of_prod === '' || $price_of_prod === null) {
		$has_zero_subtotal = true;
		}else{
			$has_subtotal =  true;
		}
}


if ( $has_zero_subtotal  ) {
    $checkout_title = __( 'Your request will be processed shortly', 'TAW_TEXT_DOMAIN' );
    ?>

<h1 class="page-header-checkout">
	<?php echo $checkout_title;?>
</h1>
<style type="text/css">
	.page-header{
		display: none;
	}
	.page-header-checkout{
    font-size: 26px;
    color: #E53935;}
</style>

<?php
}
if ( $has_subtotal && !$has_zero_subtotal) {
    $checkout_title = __( 'Your order will be processed within shortly', 'TAW_TEXT_DOMAIN' );
    ?>

<h1 class="page-header-checkout">
	<?php echo $checkout_title;?>
</h1>
<style type="text/css">
	.page-header{
		display: none;
	}
	.page-header-checkout{
    font-size: 26px;
    color: #E53935;}
</style>

<?php
}

}

// echo var_dump($order);die();
?>

<div class="woocommerce-order">

	<?php
	if ( $order ) :

		do_action( 'woocommerce_before_thankyou', $order->get_id() );
		?>

		<?php if ( $order->has_status( 'failed' ) ) : ?>

			<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed"><?php esc_html_e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'woocommerce' ); ?></p>

			<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed-actions">
				<a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="button pay"><?php esc_html_e( 'Pay', 'woocommerce' ); ?></a>
				<?php if ( is_user_logged_in() ) : ?>
					<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="button pay"><?php esc_html_e( 'My account', 'woocommerce' ); ?></a>
				<?php endif; ?>
			</p>

		<?php else : ?>

			<p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', esc_html__( 'Thank you. We get back as soon as possible.', 'TAW_TEXT_DOMAIN' ), $order ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>

			<ul class="woocommerce-order-overview woocommerce-thankyou-order-details order_details">

				<li class="woocommerce-order-overview__order order">
					<?php esc_html_e( 'Reference number', 'TAW_TEXT_DOMAIN' ); ?>
					<strong><?php echo $order->get_order_number(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
				</li>

				<li class="woocommerce-order-overview__date date">
					<?php esc_html_e( 'Date:', 'woocommerce' ); ?>
					<strong><?php echo wc_format_datetime( $order->get_date_created() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
				</li>

				<?php if ( is_user_logged_in() && $order->get_user_id() === get_current_user_id() && $order->get_billing_email() ) : ?>
					<li class="woocommerce-order-overview__email email">
						<?php esc_html_e( 'Email:', 'woocommerce' ); ?>
						<strong><?php echo $order->get_billing_email(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
					</li>
				<?php endif; ?>

				<?php if($is_logined): ?>
					<li class="woocommerce-order-overview__total total">
						<?php esc_html_e( 'Total:', 'woocommerce' ); ?>
						<strong><?php echo product_price($order->get_formatted_order_total()); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
					</li>
				<?php endif; ?>

				<?php if ( $order->get_payment_method_title() ) : ?>
					<li class="woocommerce-order-overview__payment-method method">
						<?php esc_html_e( 'Payment method:', 'woocommerce' ); ?>
						<strong><?php echo wp_kses_post( $order->get_payment_method_title() ); ?></strong>
					</li>
				<?php endif; ?>

			</ul>

		<?php endif; ?>

		<?php do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() ); ?>
		<?php do_action( 'woocommerce_thankyou', $order->get_id() ); ?>

	<?php else : ?>

		<p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', esc_html__( 'Thank you. Your Quote has been received.', 'woocommerce' ), null ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>

	<?php endif; ?>

</div>
