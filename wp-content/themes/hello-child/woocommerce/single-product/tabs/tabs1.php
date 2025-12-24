<?php
/**
 * Single Product tabs
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/tabs/tabs.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.8.0
 */
if (!defined('ABSPATH')) {
	exit;
}

/**
 * Filter tabs and allow third parties to add their own.
 *
 * Each tab is an array containing title, callback and priority.
 *
 * @see woocommerce_default_product_tabs()
 */
$product_tabs = apply_filters('woocommerce_product_tabs', array());

if (!empty($product_tabs)) :
	unset($product_tabs['reviews']); //removed review from array
	$arrow = THINGSATWEB_BASE . '/img/ic_forward_arrow.svg';
	$minus = THINGSATWEB_BASE . '/img/ic_minus.svg';
?>
	<div class="woocommerce-tabs wc-tabs-wrapper">
		<div class="grid grid-cols-1 lg:grid-cols-2 lg:gap-7 xl:gap-10">
			<?php foreach ($product_tabs as $key => $product_tab) : ?> 
				<?php if (($product_tab['title'] !== 'Additional information' && $product_tab['title'] !== 'Ytterligare information') && ($key !== 'diagram')): ?>
					<div class="master <?php if (($key == 'variation') || ($key == 'technical') || ($key == 'productfeatures') || ($key == 'technicalchange') || ($key == 'weightvolume') || ($key == 'weightcapacity') || $product_tab['title'] == 'Description' || $product_tab['title'] == 'Beskrivning') : ?> lg:col-span-2 <?php endif; ?>">
						<div class="flex bg-gray-100 p-3 items-center mt-3 lg:mt-0" id="tab-title-<?php echo esc_attr($key); ?>">
							<div class="w-2.5 h-auto">
								<img src="<?php echo $arrow; ?>" alt="Arrow">
							</div>
							<h3 class="font-semibold ml-2 text-lg my-auto"><?php echo $product_tab['title']; ?></h3>
							<div class="flex ml-auto items-center">
								<?php if ($key == 'accessories') : ?>
									<?php
									global $product;
									$category_id = $product->get_category_ids()[0];
									$category_slug = get_term($category_id)->slug;
									$accessories_slug = 'accessories-to-' . $category_slug;
									$url = site_url('shop/' . $accessories_slug);
									?>
									<a class="target:blank" target="_blank" href="<?php echo $url; ?>">
										<h3 class="font-semibold text-[15px] text-red-600 my-auto">View all</h3>
									</a>
								<?php endif; ?>
								<div class="taw-tab-option w-3 h-auto mx-5 lg:hidden" id=<?php echo esc_attr($key); ?> onclick="clickEvent(this)">
									<img src="<?php echo $minus; ?>" alt="Arrow">
								</div>
							</div>
						</div>
						<?php
						if (isset($product_tab['callback'])) {
							call_user_func($product_tab['callback'], $key, $product_tab);
						}
						?>
					</div>
				<?php endif; ?>

				<?php if  ($key === 'diagram') : ?>
					<div class="master <?php if ($key == 'diagram') : ?> lg:col-span-2 <?php endif; ?>">
						<div class="" id="tab-title-<?php echo esc_attr($key); ?>">
							
							<h3 class="font-semibold ml-2 text-lg my-auto"><?php echo $product_tab['title']; ?></h3>
							<div class="flex ml-auto items-center">
								<?php if ($key == 'accessories') : ?>
									<?php
									global $product;
									$category_id = $product->get_category_ids()[0];
									$category_slug = get_term($category_id)->slug;
									$accessories_slug = 'accessories-to-' . $category_slug;
									$url = site_url('shop/' . $accessories_slug);
									?>
									<a class="target:blank" target="_blank" href="<?php echo $url; ?>">
										<h3 class="font-semibold text-[15px] text-red-600 my-auto">View all</h3>
									</a>
								<?php endif; ?>
								
							</div>
						</div>
						<?php
						if (isset($product_tab['callback'])) {
							call_user_func($product_tab['callback'], $key, $product_tab);
						}
						?>
					</div>
				<?php endif; ?>
			<?php endforeach; ?>
		</div>

		<!-- <div id="	" style="display: none;"> -->
			<?php do_action('woocommerce_product_after_tabs'); ?>
		<!-- </div> -->

	</div>

<?php endif; ?>
<script>
	function clickEvent(context) {
		jQuery(context).toggleClass("active");
		jQuery(context).closest('.master').find('.taw-tab-container').toggle();
	}

	// $(document).ready(function(){
       
    //    $("#hide").click(function(){
    //      $(".woocommerce_product_after_tabs").hide();
    //    });
    //  });
	
</script>