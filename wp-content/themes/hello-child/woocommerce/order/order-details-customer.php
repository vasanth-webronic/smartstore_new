<?php
/**
 * Order Customer Details
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/order/order-details-customer.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 5.6.0
 */

defined( 'ABSPATH' ) || exit;

$show_shipping = ! wc_ship_to_billing_address_only() && $order->needs_shipping_address();
$is_logined    = is_user_logged_in() || current_user_can('c_uam_cap_price') || current_user_can('c_uam_cap_reseller_price');
$billing_address = $order->get_formatted_billing_address( esc_html__( 'N/A', 'woocommerce' ) );
$shipping_address = $order->get_formatted_shipping_address( esc_html__( 'N/A', 'woocommerce' ) );
?>
<section class="woocommerce-customer-details">

	<?php if ( $show_shipping ) : ?>

	<section class="woocommerce-columns woocommerce-columns--2 woocommerce-columns--addresses col2-set addresses">
		<div class="woocommerce-column woocommerce-column--1 woocommerce-column--billing-address col-1">

	<?php endif; ?>

	<span class="block text-lg bg-gray-200 p-3 font-semibold text-black"><?php esc_html_e( 'Billing address', 'woocommerce' ); ?></span>

	<address class="mt-3 text-sm">
		<?php echo wp_kses_post( $order->get_formatted_billing_address( esc_html__( 'N/A', 'woocommerce' ) ) ); ?>

		<?php if ( $order->get_billing_phone() ) : ?>
			<p class="woocommerce-customer-details--phone"><?php echo esc_html( $order->get_billing_phone() ); ?></p>
		<?php endif; ?>

		<?php if ( $order->get_billing_email() ) : ?>
			<p class="woocommerce-customer-details--email"><?php echo esc_html( $order->get_billing_email() ); ?></p>
		<?php endif; ?>
	</address>

	<?php if ( $is_logined && $show_shipping ) : ?>

		</div><!-- /.col-1 -->

		<div class="woocommerce-column woocommerce-column--2 woocommerce-column--shipping-address col-2">

		<span class="block mt-3 lg:mt-0 text-lg bg-gray-200 p-3 font-semibold text-black"><?php esc_html_e( 'Shipping address', 'woocommerce' ); ?></span>
		<address class="mt-3 text-sm">
			<?php //echo wp_kses_post( $order->get_formatted_shipping_address( esc_html__( 'N/A', 'woocommerce' ) ) );
			
			if ($shipping_address !== esc_html__( 'N/A', 'woocommerce' )) {
				echo wp_kses_post($shipping_address);
if ( $order->get_shipping_phone() ) : ?>
					<p class="woocommerce-customer-details--phone"><?php echo esc_html( $order->get_shipping_phone() ); ?></p>
				<?php endif; 

			} else {
				// Display the billing address as a fallback
				if (!empty($order->get_shipping_company())) {
				echo esc_html($order->get_shipping_company()) . '<br>';
echo esc_html($order->get_shipping_first_name()) . '&nbsp;';
echo esc_html($order->get_shipping_last_name()) . '<br>';
				}
				else{
					echo wp_kses_post($billing_address);
				}
				
			?>
			<?php if ( $order->get_billing_phone() ) : ?>
				<p class="woocommerce-customer-details--phone"><?php echo esc_html( $order->get_billing_phone() ); ?></p>
		<?php endif; ?>

		<?php if ( $order->get_billing_email() ) : ?>
			<p class="woocommerce-customer-details--email"><?php echo esc_html( $order->get_billing_email() ); ?></p>
			<?php endif; 
			}
			?>

			
		</address>
		</div><!-- /.col-2 -->

	</section><!-- /.col2-set -->

	<?php endif; ?>

	<?php do_action( 'woocommerce_order_details_after_customer_details', $order ); ?>

</section>
