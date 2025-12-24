<?php

/**
 * Review order table
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/review-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 5.2.0
 */

defined('ABSPATH') || exit;
$is_logined = is_user_logged_in() || current_user_can('c_uam_cap_price') || current_user_can('c_uam_cap_reseller_price');
?>
<table class="shop_table woocommerce-checkout-review-order-table">
	<thead class="bg-gray-200">
		<tr>
			<th class="!border-none !py-3 product-name"><?php esc_html_e('Product', 'woocommerce'); ?></th>
			<?php if ($is_logined): ?>
				<th class="!border-none !py-3 product-total"><?php esc_html_e('Subtotal', 'woocommerce'); ?></th>
			<?php endif; ?>
		</tr>
	</thead>
	<tbody>
		<?php
		do_action('woocommerce_review_order_before_cart_contents');

		foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
			$_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);


			if ($_product) {
				$price_float = floatval($_product->get_price());
			} else {
				// Handle the case when product is not found
				$price_float = 0;
			}

			if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key)) {
				$isCollection = false;
				$pspData = array();
				if (class_exists('Product_Stack_Pricing')) {
					$pspMain = new Product_Stack_Pricing;
					$pspData  =  $pspMain->custom_quantity_display_values($_product, $cart_item['quantity']);
					$isCollection = $pspData['isPallet'] || $pspData['isBox'];
				}
		?>
				<tr class="<?php echo esc_attr(apply_filters('woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key)); ?>">
					<td class="product-name border-none !py-4 !bg-white">
						<span class="text-red-600 font-semibold"><?php echo wp_kses_post(apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key)) . '&nbsp;'; ?></span>
						<?php echo apply_filters('woocommerce_checkout_cart_item_quantity', ' <strong class="product-quantity">' . sprintf('&times;&nbsp;%s', $cart_item['quantity']) . '</strong>', $cart_item, $cart_item_key); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
						?>
						<?php echo wc_get_formatted_cart_item_data($cart_item);  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
						?>
					</td>
					<?php if ($is_logined): ?>
						<td class="product-total border-none !py-4 !bg-white">

							<?php

							$price = WC()->cart->get_product_subtotal($_product, $cart_item['quantity']);
							if (strcmp(product_price($price), "-") == 0) {
								$price =  "-";
							}
							//echo apply_filters( 'woocommerce_cart_item_subtotal', $price, $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
							$single_price = apply_filters('woocommerce_cart_item_subtotal', $price, $cart_item, $cart_item_key);
							$doc = new DOMDocument();
							$doc->loadHTML($single_price);
							$textContent = $doc->getElementsByTagName('span')->item(0)->textContent;

							// Remove non-numeric characters to get the numeric value
							$numericValue = preg_replace('/[^0-9]/', '', $textContent);

							

							// Check if the numeric value is "0"
							if (!$isCollection) {
								if ($numericValue == 0) {
									$Quote = icl_t('default', 'Quote', 'Quote');
									echo $Quote;
								} else {

									echo $single_price;
								}
							}

							?>
						</td>
					<?php endif; ?>

				</tr>
				<?php if ($isCollection): ?>
					<?php if ($pspData['isPallet']): ?>

					
						<?php if ($pspData['whole_packages']): ?>
							<tr class="<?php echo esc_attr(apply_filters('woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key)); ?>">
								<td class="product-total border-none !py-4 !bg-white">
									<span class="text-red-600 font-semibold"><?php echo $pspData['isPallet'] ? 'Pallets' : 'Boxes'; ?> <span style="color: black;">×<?php echo $pspData['whole_packages']; ?></span></span>
									<span style="font-size: 12px; color:#575656;">(Each <?php echo $pspData['isPallet'] ? 'Pallet' : 'Box'; ?> contains <?php echo $pspData['units_for_whole_packages']; ?> units, You have selected <?php echo floatval($cart_item['quantity']); ?> units)</span>
								</td>
								<td class="product-total border-none !py-4 !bg-white">
									<span style="color: black;"><?php  echo ($numericValue == 0) ? '-' :(floatval($cart_item['quantity'])) * $price_float . ' ' .get_woocommerce_currency_symbol();
																?></span>
								</td>
							</tr>

						<?php endif; ?>

					<?php endif; ?>

					<?php if ($pspData['isBox']): ?>
						<tr class="<?php echo esc_attr(apply_filters('woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key)); ?>">
							<td class="product-total border-none !py-4 !bg-white">
								<span class="text-red-600 font-semibold"><?php echo $pspData['isPallet'] ? 'Pallets' : 'Boxes'; ?> <span style="color: black;">×<?php echo $cart_item['quantity']; ?></span></span>
								<span style="font-size: 12px; color:#575656;">(Each <?php echo $pspData['isPallet'] ? 'Pallet' : 'Box'; ?> contains <?php echo $pspData['units_for_whole_packages']; ?> units, You have selected <?php echo floatval($pspData['units_for_whole_packages']) * floatval($cart_item['quantity']); ?> units)</span>
							</td>
							<td class="product-total border-none !py-4 !bg-white">
								<span style="color: black;"><?php  echo ($numericValue == 0) ? '-' : floatval($cart_item['quantity']) * $price_float  . ' ' .get_woocommerce_currency_symbol()
															; ?></span>
							</td>
						</tr>
					<?php endif; ?>
				<?php endif; ?>
		<?php
			}
		}

		do_action('woocommerce_review_order_after_cart_contents');
		?>
	</tbody>
	<?php if ($is_logined): ?>
		<tfoot>

			<tr class="cart-subtotal">
				<th class="border-none !py-4 !bg-white"><?php esc_html_e('Subtotal', 'woocommerce'); ?></th>
				<td class="border-none !py-4 !bg-white"><?php
														$subTotal = product_price(WC()->cart->get_subtotal());
														if (strcmp(product_price(WC()->cart->get_subtotal()), "-") == 0 || !$subTotal) {
															echo "-";
														} else {
															wc_cart_totals_subtotal_html();
														} ?>
				</td>
			</tr>

			<?php foreach (WC()->cart->get_coupons() as $code => $coupon) : ?>
				<tr class="cart-discount coupon-<?php echo esc_attr(sanitize_title($code)); ?>">
					<th><?php wc_cart_totals_coupon_label($coupon); ?></th>
					<td><?php wc_cart_totals_coupon_html($coupon); ?></td>
				</tr>
			<?php endforeach; ?>

			<?php if ($is_logined && WC()->cart->needs_shipping() && WC()->cart->show_shipping()) : ?>

				<?php do_action('woocommerce_review_order_before_shipping'); ?>

				<span class="!text-black"><?php wc_cart_totals_shipping_html(); ?></span>

				<?php do_action('woocommerce_review_order_after_shipping'); ?>

			<?php endif; ?>

			<?php foreach (WC()->cart->get_fees() as $fee) : ?>
				<tr class="fee">
					<th class="border-none !py-4 !bg-white"><?php echo esc_html($fee->name); ?></th>
					<td class="border-none !py-4 !bg-white"><?php wc_cart_totals_fee_html($fee); ?></td>
				</tr>
			<?php endforeach; ?>

			<?php if (wc_tax_enabled() && ! WC()->cart->display_prices_including_tax()) : ?>
				<?php if ('itemized' === get_option('woocommerce_tax_total_display')) : ?>
					<?php foreach (WC()->cart->get_tax_totals() as $code => $tax) : // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited 
					?>
						<tr class="tax-rate tax-rate-<?php echo esc_attr(sanitize_title($code)); ?>">
							<th class="border-none !py-4 !bg-white"><?php echo esc_html($tax->label); ?></th>
							<td class="border-none !py-4 !bg-white"><?php echo wp_kses_post($tax->formatted_amount); ?></td>
						</tr>
					<?php endforeach; ?>
				<?php else : ?>
					<tr class="tax-total">
						<th class="border-none !py-4 !bg-white"><?php echo esc_html(WC()->countries->tax_or_vat()); ?></th>
						<td class="border-none !py-4 !bg-white"><?php wc_cart_totals_taxes_total_html(); ?></td>
					</tr>
				<?php endif; ?>
			<?php endif; ?>

			<?php do_action('woocommerce_review_order_before_order_total'); ?>

			<tr class="order-total">
				<th class="border-none !py-4 !bg-white"><?php esc_html_e('Total', 'woocommerce'); ?></th>
				<td class="border-none !py-4 !bg-white"><?php
														$allTotall = product_price(WC()->cart->get_total());
														if (strcmp(product_price(WC()->cart->get_total()), "-") == 0 || !$allTotall) {
															echo "-";
														} else {
															wc_cart_totals_order_total_html();
														} ?>
				</td>
			</tr>

			<?php do_action('woocommerce_review_order_after_order_total'); ?>

		</tfoot>
	<?php endif; ?>
</table>