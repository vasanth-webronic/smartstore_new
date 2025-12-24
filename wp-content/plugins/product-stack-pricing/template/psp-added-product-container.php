<?php
global $wpdb;
$table_name = $wpdb->prefix . 'product_stack_pricing';
$searchterm = '';
$skus_products = [];

if (!empty($_POST['searchterm'])) {
    $searchterm = sanitize_text_field($_POST['searchterm']);
    $skus_products = $wpdb->get_results($wpdb->prepare(
        "SELECT DISTINCT art_no FROM $table_name WHERE art_no LIKE %s",
        '%' . $wpdb->esc_like($searchterm) . '%'
    ), ARRAY_A);
} else {
    $skus_products = $wpdb->get_results("SELECT DISTINCT art_no FROM $table_name", ARRAY_A);
}
?>
<div class="psp-prod-checkbox-item-container items-center gap-2 hidden" style="padding: 0px 1px  !important; ">
    <input type="checkbox"  id="psp_select_all"> Select All
</div>
<div class="text-center text-gray-500" id="no-product-message" style="display: none;">No product found for searched term "<span id="search-term"></span>"</div>

<?php if (!empty($searchterm) && empty($skus_products)) : ?>
    <div class="text-center text-gray-500">No product found for searched term "<?php echo esc_html($searchterm); ?>"</div>
<?php elseif (!empty($skus_products)) : ?>
    <?php foreach ($skus_products as $sku) : ?>
        <?php
        // Get product by SKU
        $product_id = wc_get_product_id_by_sku($sku['art_no']);
        $edit_url = '';
        if ($product_id) {
            $product = wc_get_product($product_id);
            $edit_url = get_edit_post_link($product_id);
        }
        ?>
        <?php if (isset($product)) : ?>
            <div class="flex gap-2 items-center">
                <div class="psp-prod-checkbox-item-container hidden" style="padding: 0px 1px  !important; ">
                    <input type="checkbox" class="psp_product_checkbox" style=" margin:0px !important;" name="select_artno_for_del_action[]" value="<?php echo esc_html($sku['art_no']); ?>">
                </div>
                <div class="bg-white p-2 w-full rounded cursor-pointer text-black hover:bg-psp-blue hover:!text-white added-product-item <?php echo esc_html($sku['art_no']); ?>" data-editurl="<?php echo esc_url($edit_url); ?>" data-artno="<?php echo esc_html($sku['art_no']); ?>">
                    <div class="font-[900] text-md">ARTICLE NO: <?php echo esc_html($sku['art_no']); ?></div>
                    <p class="text-[12px]"><?php echo esc_html($product->get_name()); ?></p>
                </div>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
<?php else : ?>
    <div class="text-center text-gray-500">Add some products</div>
<?php endif; ?>
