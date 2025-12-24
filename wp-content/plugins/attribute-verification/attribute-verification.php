<?php
/**
 * Plugin Name: Attribute Verification
 * Description: Processes WooCommerce product attributes category-wise via AJAX and saves to a custom table.
 * Version: 1.1
 * Author: YourName
 */

if (!defined('ABSPATH')) exit;

class Attribute_Verification_Plugin {

    private $option_name = 'avp_progress_data';

    public function __construct() {
        add_action('admin_menu', [$this, 'add_admin_page']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts']);

        add_action('wp_ajax_avp_process_batch', [$this, 'ajax_process_batch']);
        add_action('wp_ajax_avp_reset_progress', [$this, 'ajax_reset_progress']);
    }

    public function add_admin_page() {
        add_menu_page(
            'Attribute Verification',
            'Attribute Verification',
            'manage_options',
            'attribute-verification',
            [$this, 'render_admin_page'],
            'dashicons-list-view',
            56
        );
    }

    public function enqueue_scripts($hook) {
        if ($hook !== 'toplevel_page_attribute-verification') return;

        wp_enqueue_style('avp-style', plugin_dir_url(__FILE__) . 'style.css');
        wp_enqueue_script('avp-script', plugin_dir_url(__FILE__) . 'script.js', ['jquery'], false, true);

        wp_localize_script('avp-script', 'avp_ajax', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('avp_nonce'),
        ]);
    }

    public function render_admin_page() {
        ?>
        <div class="wrap">
            <h1>Attribute Verification</h1>
            <button id="avp_start_process" class="button button-primary">Start Processing</button>
            <div id="avp_progress_box" style="margin-top:20px; border:1px solid #ccc; padding:10px; width: 100%; max-width: 700px; height: 300px; overflow-y: auto; position: relative;">
                <div id="avp_progress_bar_container" style="position: sticky; top: 0; background: #f9f9f9; padding: 5px 0; z-index: 999;">
                    <div id="avp_progress_bar" style="width: 0%; height: 20px; background: #0073aa;"></div>
                </div>
                <div id="avp_log" style="margin-top: 10px; font-family: monospace; white-space: pre-line;"></div>
            </div>
        </div>
        <?php
    }

    public function ajax_reset_progress() {
        check_ajax_referer('avp_nonce', 'nonce');
        delete_option($this->option_name);
        wp_send_json_success();
    }

    public function ajax_process_batch() {
        global $wpdb;
        check_ajax_referer('avp_nonce', 'nonce');

       // $table_name = $wpdb->prefix . 'product_attributes';
$table_name =  'taw_product_attributes';
        $progress = get_option($this->option_name, [
            'categories' => [],
            'current_cat_index' => 0,
            'product_offset' => 0,
            'total_products' => 0,
            'total_categories' => 0,
            'processed_products' => 0,
            'processed_categories' => 0,
            'done' => false,
            'log' => '',
        ]);

        // Initialize categories list if empty
        if (empty($progress['categories'])) {
            $cats = get_terms([
                'taxonomy' => 'product_cat',
                'hide_empty' => false,
                'fields' => 'ids',
            ]);
            $progress['categories'] = $cats;
            $progress['total_categories'] = count($cats);
            $progress['current_cat_index'] = 0;
            $progress['product_offset'] = 0;
            $progress['processed_products'] = 0;
            $progress['processed_categories'] = 0;
            $progress['done'] = false;
            $progress['log'] = '';
            $progress['total_products'] = 0;
        }

        if ($progress['done']) {
            wp_send_json_success([
                'done' => true,
                'log' => $progress['log'],
                'progress_percent' => 100,
            ]);
        }

        $categories = $progress['categories'];
        $current_cat_index = $progress['current_cat_index'];

        if ($current_cat_index >= count($categories)) {
            $progress['done'] = true;
            update_option($this->option_name, $progress);
            wp_send_json_success([
                'done' => true,
                'log' => $progress['log'],
                'progress_percent' => 100,
            ]);
        }

        $cat_id = $categories[$current_cat_index];
        $cat_obj = get_term($cat_id, 'product_cat');
        $cat_name = $cat_obj ? $cat_obj->name : 'Unknown Category';

        if (strpos($progress['log'], "Category: {$cat_name}") === false) {
            $progress['log'] .= "Category: {$cat_name}\n\n";
        }

        $args = [
            'post_type' => 'product',
            'posts_per_page' => 5,
            'post_status' => 'publish',
            'tax_query' => [
                [
                    'taxonomy' => 'product_cat',
                    'field' => 'term_id',
                    'terms' => $cat_id,
                ],
            ],
            'offset' => $progress['product_offset'],
        ];

        $products = get_posts($args);

        if (empty($products)) {
            $progress['log'] .= "\n--- End of category {$cat_name} ---\n\n\n";
            $progress['processed_categories']++;
            $progress['current_cat_index']++;
            $progress['product_offset'] = 0;

            update_option($this->option_name, $progress);

            wp_send_json_success([
                'done' => false,
                'log' => $progress['log'],
                'progress_percent' => intval(100 * ($progress['processed_categories'] / max(1, $progress['total_categories']))),
                'message' => "Finished category: {$cat_name}, moving to next category...",
            ]);
        }

        foreach ($products as $product_post) {
            $product = wc_get_product($product_post->ID);
            if (!$product) continue;

            $product_id = $product->get_id();
            $product_name = $product->get_name();

            // Delete old data for product
            $wpdb->delete($table_name, ['product_id' => $product_id]);

            $attributes = $product->get_attributes();

            if (empty($attributes)) {
                $wpdb->insert($table_name, [
                    'product_id' => $product_id,
                    'product_name' => $product_name,
                    'category_name' => $cat_name,
                    'attribute_name' => '(no attributes)',
                    'attribute_value' => '',
                    'created_at' => current_time('mysql'),
                ]);
            } else {
                foreach ($attributes as $attribute) {
                    if ($attribute->is_taxonomy()) {
                        $taxonomy = $attribute->get_name();
                        $terms = wp_get_post_terms($product_id, $taxonomy, ['fields' => 'names']);
                        $values = implode(', ', $terms);
                        $wpdb->insert($table_name, [
                            'product_id' => $product_id,
                            'product_name' => $product_name,
                            'category_name' => $cat_name,
                            'attribute_name' => $taxonomy,
                            'attribute_value' => $values,
                            'created_at' => current_time('mysql'),
                        ]);
                    } else {
                        $values = $attribute->get_options();
                        $values_str = implode(', ', $values);
                        $wpdb->insert($table_name, [
                            'product_id' => $product_id,
                            'product_name' => $product_name,
                            'category_name' => $cat_name,
                            'attribute_name' => $attribute->get_name(),
                            'attribute_value' => $values_str,
                            'created_at' => current_time('mysql'),
                        ]);
                    }
                }
            }
            $progress['log'] .= "Processed product: {$product_name}\n";
            $progress['processed_products']++;
        }

        $progress['product_offset'] += 5;

        update_option($this->option_name, $progress);

        $percent = intval(100 * ($progress['processed_categories'] + ($progress['product_offset'] / max(1, 5 * 20))) / max(1, $progress['total_categories']));

        wp_send_json_success([
            'done' => false,
            'log' => $progress['log'],
            'progress_percent' => $percent,
            'message' => "Processing category {$cat_name}, batch starting at product offset {$progress['product_offset']}",
        ]);
    }
}

new Attribute_Verification_Plugin();
