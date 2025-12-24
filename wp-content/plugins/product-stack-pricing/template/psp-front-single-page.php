<?php
global $product, $wpdb;
$price_float = 0;

if ( $product ) {
    $price_float = floatval( $product->get_price() );
  } else {
    // Handle the case when product is not found
    $price_float = 0;
  }
// Get all the product attributes
$attributes = $product->get_attributes();

// Slug names for the specific attributes
$box_slug = 'pa_quantity-in-the-package';
$pallet_slug = 'pa_quantity-on-pallet';

// Function to get attribute terms based on the attribute slug
function get_attribute_values($attribute_slug, $product)
{
  // Get terms related to the product's attribute
  $terms = wp_get_post_terms($product->get_id(), $attribute_slug);

  // If the attribute terms exist
  if (!empty($terms)) {
    $term_names = [];
    foreach ($terms as $term) {
      $term_names[] = $term->name;
    }
    return $term_names; // return array instead of imploded string
  }
  return [];
}

// Get values for box and pallet attributes
$box_values = isset($attributes[$box_slug]) ? get_attribute_values($box_slug, $product) : [];
$pallet_values = isset($attributes[$pallet_slug]) ? get_attribute_values($pallet_slug, $product) : [];
$first_number;

// echo var_dump($pallet_values,$box_values);
// Output box or pallet info
if (!empty($pallet_values)) {
  echo '<p class="font-semibold">1 Pallet = ' . implode(', ', $pallet_values) . '</p>';

  // Extract number from first value
  if (preg_match('/\d+/', $pallet_values[0], $matches)) {
    $first_number = $matches[0];
    echo '<input type="hidden" id="wholepackageCount" value="' . esc_attr($first_number) . '" />';
  }

}
elseif (!empty($box_values)) {
  echo '<p class="font-semibold">1 Box = ' . implode(', ', $box_values) . '</p>';

  // Extract number from first value
  if (preg_match('/\d+/', $box_values[0], $matches)) {
    $first_number = $matches[0];
    echo '<input type="hidden" id="wholepackageCount" value="' . esc_attr($first_number) . '" />';
  }
} 


// Get the logged-in user ID
$current_user_id = get_current_user_id();

$user = wp_get_current_user();
$user_roles = (array) $user->roles;

// Assume single role (or just take the first role for matching)
$user_role = !empty($user_roles) ? $user_roles[0] : '';

$product_sku = $product->get_sku();

$query = $wpdb->prepare(
    "SELECT * FROM {$wpdb->prefix}product_stack_pricing
    WHERE art_no = %s
      AND status = 1
      AND (
          users LIKE %s
          OR (role = %s AND enable_all_users = 1)
      )",
    $product_sku,
    '%' . $wpdb->esc_like($current_user_id) . '%',
    $user_role
);

$rules = $wpdb->get_results($query);

// Get the product price HTML and regular price
$product_price_html = $product->get_price_html();
$regular_price = $product->get_regular_price(); // Get the regular price (before discount)

// Use regex to extract the price and currency symbol separately (if needed)
preg_match('/<span class="woocommerce-Price-amount amount">(\d+[.,]?\d*)<\/span>/', $product_price_html, $matches);

// Extract the price and currency if matched
if (!empty($matches)) {
  $price = $matches[1]; // Extracted price (e.g., "3500" or "3500.50")
  $currency = get_woocommerce_currency_symbol(); // Get dynamic currency symbol (e.g., "kr", "$")
}
?>

<div class="product-price-section text-center lg:text-start">

  <!-- Discounted Price Rules (only if available) -->
  <div class="product-rules mt-4 space-y-4">


    <?php if ($rules): ?>
      <div class="pspPricingContainer ">
        <div class="leftPspPricing">
          <?php  if (!empty($pallet_values)) {
            echo '<div>
			<h6>Quantity- Based Offers</h6>
			<small>When you purchase by quantity, you will receive the items in individual units</small>
		</div>';
          } elseif (!empty($box_values)) {
            echo '<div>
			<h6>Box- Based Offers</h6>
			<small>When you purchase as a box, you will receive '.$first_number.' pieces per box.</small>
		</div>';
          }?>

          <?php
          // 1) Sort rules by qty ascending
          usort($rules, fn($a, $b) => $a->qty <=> $b->qty);

          // 2) Grab the normal price HTML once
          $normal_price_html = $product->get_price_html();

          // 3) If the very first threshold > 1, output a "1–(first–1)" block
          $first_qty = $rules[0]->qty;
          if ($first_qty > 1) : ?>
            <div class="rule mb-2 border rounded-lg shadow-md bg-white flex items-center justify-between sm:flex-col md:flex-row"

              data-rule-qty="1">
              <div class="rule-info ml-4 flex items-center w-full">
                <div class="text-sm text-gray-700 font-semibold flex items-center gap-5 px-5 py-2 w-full">
                  <div class="qty-range w-1/2 border px-5 flex gap-2" style="width:60%; border-top: none; border-bottom: none; border-left: none;">
                    Buy <span class="text-red-600 flex gap-2"><?php echo esc_html("1 – " . ($first_qty - 1)); ?></span>
                    <span style="font-size:10px; line-height:initial; " class="flex items-end text-black"><?php echo empty($pallet_values) ?  'Box' : 'Units'; ?></span>
                  </div>
                  <div class="price-range flex gap-2 text-red-600">
                    <?php echo $price_float == 0 ? icl_t('default', 'Quote', 'Quote') : wp_kses_post($normal_price_html); ?>
                    <span style="font-size:10px; line-height:initial; " class="flex items-end text-black"><?php echo $price_float != 0 ? 'Each' : '';?></span>
                  </div>
                </div>
              </div>
            </div>
          <?php endif; ?>

          <?php
          // 4) Loop through rules as thresholds…
          $count = count($rules);
          foreach ($rules as $i => $rule) :

            $start   = $rule->qty;
            $is_last = ($i === $count - 1);

            if (! $is_last) {
              // next threshold minus one
              $end = $rules[$i + 1]->qty - 1;
              $label = "{$start} – {$end}";
            } else {
              // open-ended
              $label = "{$start}+";
            }
          ?>
            <div
              class="rule mb-2 border rounded-lg shadow-md bg-white flex items-center justify-between sm:flex-col md:flex-row"

              data-rule-id="rule-<?php echo esc_attr($rule->id); ?>"
              data-rule-price="<?php echo esc_attr($rule->rule_price); ?>"
              data-rule-qty="<?php echo esc_attr($rule->qty); ?>">
              <div class="rule-info ml-4 flex items-center w-full">
                <div class="text-sm text-gray-700 font-semibold flex items-center gap-5 px-5 py-2 w-full">

                  <!-- Qty range -->
                  <div class="qty-range w-1/2 border px-5 flex gap-2" style="width:60%; border-top: none; border-bottom: none; border-left: none;">
                    Buy <span class="text-red-600"><?php echo esc_html($label); ?></span>
                    <span style="font-size:10px; line-height:initial; " class="flex items-end text-black"><?php echo empty($pallet_values) ?  'Box' : 'Units'; ?></span>
                  </div>

                  <!-- Tiered Price -->
                  <div class="price-range ">
                    <span class="text-red-600 flex gap-2">
                      <?php echo esc_html($rule->rule_price . ' ' . get_woocommerce_currency_symbol()); ?>
                      <span style="font-size:10px; line-height:initial; " class="flex items-end text-black">Each</span>
                    </span>
                  </div>

                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div> <?php if (!empty($pallet_values)): ?><div class="qty-range w-1/2 border px-5  gap-2 ml-3 rightPspPricing" style="border-top: none; border-bottom: none; border-right: none;">
            <h6>Your Order</h6><small>Total Quantity Selected:</small>
            <div class="text-sm text-gray-700 font-semibold flex items-center justify-between gap-5 px-5 py-2 w-full mb-2 border rounded-lg shadow-md">

              <span class="text-red-600 flex gap-2" id="totalQtyDply">
                -
              </span>
              <span style="font-size:10px; line-height:initial; " class="flex items-end text-black">Units</span>

            </div><small>You will receive your order as:</small>
            <div class="text-sm text-gray-700 font-semibold flex items-center justify-between gap-5 px-5 py-2 w-full mb-2 border rounded-lg shadow-md">

              <span class="text-red-600 flex gap-2">
                <?php echo $pallet_values ?  'Pallets' : 'Boxes'; ?>
              </span>
              <span style="font-size:10px; line-height:initial; " class="flex items-end text-black" id="wholePackageSelected">-</span>

            </div>
           
          </div><?php endif; ?>
      </div>
    <?php endif; ?>



  </div>
  <style>
    .woocommerce-Price-amount bdi {
      display: flex;
      align-items: end;
      vertical-align: bottom;
    }

    .pspPricingContainer{
      display: flex;
    }
    .leftPspPricing{
        width: 65%;
      }
      .rightPspPricing{
        width: 34%;
      }
    /* For better spacing and display on mobile */
    @media (max-width: 768px) {
      .product-price-section {
        text-align: center;
      }

      .product-rules {
        margin-top: 10px;
      }

      .rule {
        flex-direction: column;
        align-items: center;
      }

      .rule-info {
        text-align: center;
      }
       .pspPricingContainer{
      display: flex;
      flex-direction: column;
    }

      .leftPspPricing,.rightPspPricing{
        width: 100%;
      }
      .rightPspPricing {
        border-left: 0 !important;
        margin-left: 0 !important;
        margin-top: 10px;
        text-align: left;
      }
    }

    /* Highlight the rule border */
    .highlighted-rule {
      border: 2px solid red !important;
    }
  </style>
</div>