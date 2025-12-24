<?php
/**
 * Order details
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/order/order-details.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 4.6.0
 */

defined( 'ABSPATH' ) || exit;

$order = wc_get_order( $order_id ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
// $check=0;
// $product_prices = array();
if ( ! $order ) {
	return;
}

$order_items           = $order->get_items( apply_filters( 'woocommerce_purchase_order_item_types', 'line_item' ) );
$show_purchase_note    = $order->has_status( apply_filters( 'woocommerce_purchase_note_order_statuses', array( 'completed', 'processing' ) ) );
$show_customer_details = is_user_logged_in() && $order->get_user_id() === get_current_user_id();
$downloads             = $order->get_downloadable_items();
$show_downloads        = $order->has_downloadable_item() && $order->is_download_permitted();
$is_logined            = is_user_logged_in() || current_user_can('c_uam_cap_price') || current_user_can('c_uam_cap_reseller_price');

if ( $show_downloads ) {
	wc_get_template(
		'order/order-downloads.php',
		array(
			'downloads'  => $downloads,
			'show_title' => true,
		)
	);
}
?>
<section class="woocommerce-order-details">
	<?php do_action( 'woocommerce_order_details_before_order_table', $order ); ?>

	<!--<h3 class="woocommerce-order-details__title" class="text-xl xl:text-2xl"><?php esc_html_e( 'Order details', 'woocommerce' ); ?></h3>-->

	<table class="woocommerce-table woocommerce-table--order-details shop_table order_details">

		<thead class="bg-gray-200">
			<tr>
				<th class="!py-3 !border-none woocommerce-table__product-name product-name"><?php esc_html_e( 'Products', 'TAW_TEXT_DOMAIN' ); ?></th>
				<?php if($is_logined): ?>
					<th class="!py-3 !border-none woocommerce-table__product-table product-total"><?php esc_html_e( 'Total', 'woocommerce' ); ?></th>
				<?php endif; ?>
			</tr>
		</thead>

		<tbody>
			<?php
			do_action( 'woocommerce_order_details_before_order_table_items', $order );

			foreach ( $order_items as $item_id => $item ) {
				$product = $item->get_product();

				wc_get_template(
					'order/order-details-item.php',
					array(
						'order'              => $order,
						'item_id'            => $item_id,
						'item'               => $item,
						'show_purchase_note' => $show_purchase_note,
						'purchase_note'      => $product ? $product->get_purchase_note() : '',
						'product'            => $product,
					)
				);
// $product = $item->get_product(); 
// 				$product_price = $product->get_price(); 
			
				
// 				$product_prices[] = $product_price;
			
			}

			do_action( 'woocommerce_order_details_after_order_table_items', $order );
			?>
		</tbody>

		<?php if($is_logined): ?>
			<tfoot>
				<?php
					foreach ( $order->get_order_item_totals() as $key => $total ) {
						$checking =esc_html($total['label']);
						$lang = getSiteCurrentLang();
						
						if($lang=='sv')
						if (strcmp(esc_html($total['label']), "Frakt:") != 0) { 
							
							?> 
							<tr>
								<th class="!border-none" scope="row"><?php echo esc_html( $total['label'] ); ?></th>
								<td class="!border-none"><?php echo ( 'payment_method' === $key ) ? esc_html( product_price($total['value']) ) : wp_kses_post( product_price($total['value']) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
							</tr>
						<?php
						}
						
						else {
							
							if($is_logined) { ?>
								<tr>
									<th class="!border-none" scope="row"><?php echo esc_html( $total['label'] ); ?></th>									
									<td class="!border-none">
									<?php  		
								// if (in_array(0, $product_prices)) {
									// 	$check = 1;
									// }						
								//	if($check === 1){
										//$ship= icl_t('TAW_TEXT_DOMAIN', 'Excluding shipping', 'Excluding shipping');
										esc_html_e( 'Excluding shipping', 'TAW_TEXT_DOMAIN' );
									//	echo $ship;
										// }
										
								//	else{
									?>	
									<?php //echo ( 'payment_method' === $key ) ? esc_html( $total['value'] ) : wp_kses_post( $total['value'] ); //} // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
										?></td>
								</tr>
							<?php 
							} 
						}

						if($lang=='en')
						if (strcmp(esc_html($total['label']), "Shipping:") != 0) { 
							
							?> 
							<tr>
								<th class="!border-none" scope="row"><?php echo esc_html( $total['label'] ); ?></th>
								<td class="!border-none"><?php echo ( 'payment_method' === $key ) ? esc_html( product_price($total['value']) ) : wp_kses_post( product_price($total['value']) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
							</tr>
						<?php
						}
						
						else {
							
							if($is_logined) { ?>
								<tr>
									<th class="!border-none" scope="row"><?php echo esc_html( $total['label'] ); ?></th>									
									<td class="!border-none">
									<?php  		
								// if (in_array(0, $product_prices)) {
									// 	$check = 1;
									// }						
								//	if($check === 1){
										//$ship= icl_t('TAW_TEXT_DOMAIN', 'Excluding shipping', 'Excluding shipping');
										esc_html_e( 'Excluding shipping', 'TAW_TEXT_DOMAIN' );
									//	echo $ship;
										// }
										
								//	else{
									?>	
									<?php //echo ( 'payment_method' === $key ) ? esc_html( $total['value'] ) : wp_kses_post( $total['value'] ); //} // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
										?></td>
								</tr>
							<?php 
							} 
						}
					

					}
				?>
				<?php if ( $order->get_customer_note() ) : ?>
					<tr>
						<th class="!border-none"><?php esc_html_e( 'Note:', 'woocommerce' ); ?></th>
						<td class="!border-none"><?php echo wp_kses_post( nl2br( wptexturize( $order->get_customer_note() ) ) ); ?></td>
					</tr>
				<?php endif; ?>
			</tfoot>
		<?php endif;  ?>
	</table>

	<?php do_action( 'woocommerce_order_details_after_order_table', $order ); ?>
</section>

<?php
/**
 * Action hook fired after the order details.
 *
 * @since 4.4.0
 * @param WC_Order $order Order data.
 */
do_action( 'woocommerce_after_order_details', $order );

if ( $show_customer_details ) {
	wc_get_template( 'order/order-details-customer.php', array( 'order' => $order ) );
}
