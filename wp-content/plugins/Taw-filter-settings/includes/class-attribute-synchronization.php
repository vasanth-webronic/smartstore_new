<?php
class Attribute_Synchronizer {
    private $wpdb;
    private $batch_size = 100;

    public function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;

        add_action('wp_ajax_process_attribute_sync', [$this, 'ajax_process_sync']);
        add_action('wp_ajax_get_sync_stats', [$this, 'ajax_get_sync_stats']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_assets']);

        // Add new hooks for automatic synchronization
        add_action('taw_article_attributes_updated', [$this, 'auto_sync_new_attributes']);
        add_action('save_post_product', [$this, 'check_for_new_attributes'], 20);

        // Add this to your plugin activation or somewhere appropriate
        register_activation_hook(__FILE__, function() {
            if (!wp_next_scheduled('taw_daily_attribute_sync')) {
                // Set Stockholm timezone
        $stockholm_timezone = new DateTimeZone('Europe/Stockholm');
        
        // Create DateTime object for 1 AM Stockholm time
        $scheduled_time = new DateTime('today 1:00', $stockholm_timezone);
        
        // If it's already past 1 AM today, schedule for 1 AM tomorrow
        if ($scheduled_time->getTimestamp() < time()) {
            $scheduled_time->modify('+1 day');
        }
        
        // Schedule the event
        wp_schedule_event($scheduled_time->getTimestamp(), 'daily', 'taw_daily_attribute_sync');
                // wp_schedule_event(time(), 'daily', 'taw_daily_attribute_sync');
            }
        });

        register_deactivation_hook(__FILE__, function() {
            wp_clear_scheduled_hook('taw_daily_attribute_sync');
        });

        // Schedule the daily sync
        add_action('taw_daily_attribute_sync', [$this, 'auto_sync_new_attributes']);
    }

    public function enqueue_assets() {
        wp_enqueue_style('attribute-sync-css', plugins_url('css/attribute-sync.css', __FILE__));
        wp_enqueue_script('attribute-sync-js', plugins_url('js/attribute-sync.js', __FILE__), ['jquery'], '1.0', true);
        wp_localize_script('attribute-sync-js', 'sync_vars', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('attribute_sync_nonce')
        ]);
    }

    public function render_sync_page() {
        include plugin_dir_path(__FILE__) . 'templates/sync-page.php';
    }

    public function ajax_get_sync_stats() {
        $this->verify_nonce();
        wp_send_json_success([
            'total_attributes' => $this->get_total_attributes(),
            'total_categories' => $this->get_total_categories()
        ]);
    }

    public function ajax_process_sync() {
        $this->verify_nonce();

        $step = sanitize_text_field($_POST['step'] ?? 'init');
        $data = [
            'offset' => intval($_POST['offset'] ?? 0),
            'attr_id' => sanitize_text_field($_POST['attr_id'] ?? null),
            'processed' => intval($_POST['processed'] ?? 0),
            'duplicates_removed' => intval($_POST['duplicates_removed'] ?? 0)
        ];

        try {
            switch ($step) {
                case 'init':
                    $response = $this->init_sync();
                    break;
                case 'process_batch':
                    $response = $this->process_batch($data);
                    break;
                case 'complete':
                    $response = $this->complete_sync();
                    break;
                default:
                    throw new Exception('Invalid step');
            }
            wp_send_json_success($response);
        } catch (Exception $e) {
            wp_send_json_error($e->getMessage());
        }
    }

    private function init_sync() {
        return [
            'step' => 'process_batch',
            'message' => 'Starting synchronization...',
            'total_attributes' => $this->get_total_attributes(),
            'total_categories' => $this->get_total_categories()
        ];
    }

    private function process_batch($data) {
        // Get next attribute if none specified
        if (!$data['attr_id']) {
            $data['attr_id'] = $this->get_next_attribute_id();
        }

        // Process categories for current attribute
        $categories = $this->wpdb->get_col($this->wpdb->prepare(
            "SELECT DISTINCT cate_no FROM taw_filter_setting "
        ));

        $processed_in_batch = 0;
        $duplicates_removed = 0;

        foreach ($categories as $category) {
            // Remove duplicates first
            $duplicates_removed += $this->remove_duplicates($data['attr_id'], $category);
            
            // Insert 
            $this->insert_attribute($data['attr_id'], $category);
            $processed_in_batch++;
            
        }

        // Get next attribute
        $next_attr_id = $this->get_next_attribute_id($data['attr_id']);

        // Calculate progress
        $total_attributes = $this->get_total_attributes();
        $current_index = $this->get_attribute_index($data['attr_id']);
        $progress = $total_attributes > 0 ? round(($current_index / $total_attributes) * 100) : 0;

        return [
            'step' => $next_attr_id ? 'process_batch' : 'complete',
            'attr_id' => $next_attr_id,
            'offset' => $data['offset'] + $this->batch_size,
            'processed' => $data['processed'] + $processed_in_batch,
            'duplicates_removed' => $data['duplicates_removed'] + $duplicates_removed,
            'progress' => $progress,
            'message' => sprintf(
                'Processed %s (%d/%d) | Removed %d duplicates',
                $data['attr_id'],
                $current_index,
                $total_attributes,
                $duplicates_removed
            )
        ];
    }

    private function complete_sync() {
        return [
            'step' => 'complete',
            'message' => 'Synchronization completed successfully!',
            'progress' => 100
        ];
    }

    private function remove_duplicates($attr_id, $category) {
        $lang = $this->is_swedish_category($category) ? 0 : 1;
        
        // First remove empty att_value records
        $empty_removed = $this->wpdb->query($this->wpdb->prepare(
            "DELETE FROM taw_filter_setting 
            WHERE attribute = %s 
            AND cate_no = %s 
            AND lang = %d
            AND (att_value IS NULL OR att_value = '')",
            $attr_id, $category, $lang
        ));

        // Then remove records where lang is not 0 or 1
        $invalid_lang_removed = $this->wpdb->query($this->wpdb->prepare(
            "DELETE FROM taw_filter_setting
            WHERE attribute = %s
            AND cate_no = %s
            AND lang NOT IN (0, 1)",
            $attr_id, $category
        ));
        
        // Then remove duplicates (keeping oldest record)
        $duplicates_removed = $this->wpdb->query($this->wpdb->prepare(
            "DELETE t1 FROM taw_filter_setting t1
            INNER JOIN (
                SELECT MIN(id) as min_id, att_value 
                FROM taw_filter_setting 
                WHERE attribute = %s 
                AND cate_no = %s
                AND lang = %d
                AND att_value IS NOT NULL
                AND att_value != ''
                GROUP BY att_value
            ) t2 ON t1.att_value = t2.att_value
            WHERE t1.attribute = %s 
            AND t1.cate_no = %s 
            AND t1.lang = %d
            AND t1.id > t2.min_id",
            $attr_id, $category, $lang,  // For the subquery
            $attr_id, $category, $lang   // For the main query
        ));
        
        return $empty_removed + $duplicates_removed + $invalid_lang_removed;
    }

    private function insert_attribute($attr_id, $category) {
        $lang = $this->is_swedish_category($category) ? 0 : 1;
        $terms = $this->get_attribute_terms($attr_id);

        foreach ($terms as $term) {
            // First check if this exact combination already exists
            $exists = $this->wpdb->get_var($this->wpdb->prepare(
                "SELECT COUNT(*) FROM taw_filter_setting
                WHERE attribute = %s 
                AND cate_no = %s 
                AND att_value = %s 
                AND lang = %d",
                $attr_id, $category, $term, $lang
            ));

            if (!$exists) {
            $this->wpdb->insert('taw_filter_setting', [
                'cate_no' => $category,
                'attribute' => $attr_id,
                'att_value' => $term,
                'lang' => $lang,
                'datasheetfilt_enable' => 1
            ], ['%s', '%s', '%s', '%d', '%d']);
        }
    }
    }

    private function get_attribute_index($attr_id) {
        // Fetch all distinct attr_ids from the taw_article_attributes table and sort them in ascending order
        $attr_ids = $this->wpdb->get_col("SELECT DISTINCT attr_id FROM taw_article_attributes ORDER BY attr_id ASC");

        // Find the index of the given attr_id in the sorted array
        $index = array_search($attr_id, $attr_ids);

        // If the attribute is found, return the index (1-based index), otherwise return 0 (or any appropriate value)
        return $index !== false ? $index + 1 : 0; // +1 for 1-based index
    }


    private function get_next_attribute_id($current_id = null) {
        if ($current_id) {
            return $this->wpdb->get_var($this->wpdb->prepare(
                "SELECT attr_id FROM taw_article_attributes 
                WHERE attr_id > %s ORDER BY attr_id ASC LIMIT 1",
                $current_id
            ));
        }
        return $this->wpdb->get_var(
            "SELECT attr_id FROM taw_article_attributes ORDER BY attr_id ASC LIMIT 1"
        );
    }

    private function attribute_exists($attr_id, $category) {
        return $this->wpdb->get_var($this->wpdb->prepare(
            "SELECT COUNT(*) FROM taw_filter_setting 
            WHERE attribute = %s AND cate_no = %s",
            $attr_id, $category
        )) > 0;
    }

    private function is_swedish_category($category_name) {
        return $this->wpdb->get_var($this->wpdb->prepare(
            "SELECT COUNT(*) FROM tsm_terms t
            JOIN tsm_term_taxonomy tax ON t.term_id = tax.term_id
            JOIN tsm_icl_translations icl ON t.term_id = icl.element_id
            WHERE t.name = %s AND tax.taxonomy = 'product_cat' AND icl.language_code = 'sv'",
            $category_name
        )) > 0;
    }

    private function get_attribute_terms($attr_id) {
        $terms = $this->wpdb->get_col($this->wpdb->prepare(
            "SELECT DISTINCT term_ids FROM taw_article_attributes WHERE attr_id = %s",
            $attr_id
        ));
        return array_unique(explode(',', implode(',', $terms)));
    }

    private function get_total_attributes() {
        return $this->wpdb->get_var("SELECT COUNT(DISTINCT attr_id) FROM taw_article_attributes");
    }

    private function get_total_categories() {
        return $this->wpdb->get_var("SELECT COUNT(DISTINCT cate_no) FROM taw_filter_setting");
    }

    private function verify_nonce() {
        if (!wp_verify_nonce($_POST['nonce'] ?? '', 'attribute_sync_nonce')) {
            throw new Exception('Invalid nonce');
        }
    }

        /**
     * Automatically sync new attributes when triggered
     */
    public function auto_sync_new_attributes() {
        // Get all attributes currently in filter settings
        $synced_attributes = $this->wpdb->get_col("SELECT DISTINCT attribute FROM taw_filter_setting");
        
        // Get all attributes from article attributes table
        $all_attributes = $this->wpdb->get_col("SELECT DISTINCT attr_id FROM taw_article_attributes");
        
        // Find attributes that aren't synced yet
        $new_attributes = array_diff($all_attributes, $synced_attributes);
        
        if (!empty($new_attributes)) {
            // Process each new attribute
            foreach ($new_attributes as $attr_id) {
                $this->process_attribute_for_all_categories($attr_id);
            }
            
            // Log the automatic sync
            error_log('Automatically synced new attributes: ' . implode(', ', $new_attributes));
        }
    }
    
    /**
     * Process a single attribute for all categories
     */
    private function process_attribute_for_all_categories($attr_id) {
        $categories = $this->wpdb->get_col("SELECT DISTINCT cate_no FROM taw_filter_setting");
        
        foreach ($categories as $category) {
            // Remove duplicates first
            $this->remove_duplicates($attr_id, $category);
            
            // Insert if not exists
            $this->insert_attribute($attr_id, $category);
        }
    }
    
    /**
     * Check for new attributes when a product is saved
     */
    public function check_for_new_attributes($post_id) {
        // Only proceed for products
        if ('product' !== get_post_type($post_id)) {
            return;
        }
        
        // Trigger the auto-sync (with a small delay to ensure data is saved)
        wp_schedule_single_event(time() + 5, 'taw_article_attributes_updated');
    }

}