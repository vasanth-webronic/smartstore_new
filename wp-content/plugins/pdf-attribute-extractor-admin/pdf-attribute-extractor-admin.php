<?php
/*
Plugin Name: PDF Attribute Extractor Admin Page
Description: Extract and store product attributes from page-print.php output in batches via admin page.
Version: 1.0
Author: Your Name
*/

// Create custom table on plugin activation
register_activation_hook(__FILE__, function(){
    global $wpdb;
    $table_name = 'taw_pdf_product_attributes';
    if ($table_name !==  'taw_pdf_product_attributes') {
        $table_name =  'taw_pdf_product_attributes'; // Ensure exact table name
    }
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
        product_id bigint(20) unsigned NOT NULL,
        category_name varchar(255) NOT NULL,
        product_name varchar(255) NOT NULL,
        attribute_name varchar(255) NOT NULL,
        attribute_value text NOT NULL,
        PRIMARY KEY (id),
        KEY product_id (product_id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
});


function pdfae_get_pdf_html_for_product($product_id) {
    ob_start();

    // Make $product global for page-print.php
    global $product;
    $product = wc_get_product($product_id);

    // Include your exact page-print.php path here
    include ABSPATH . 'wp-content/plugins/thingsatweb/template/page-print.php';

    return ob_get_clean();
}

// Helper: get page-print.php HTML output for a product
function pdfae_parse_attributes_from_html($html) {
    $attributes = [];

// If input HTML is empty or just whitespace, return empty array early
    if (empty(trim($html))) {
        return $attributes;
    }
    
    $dom = new DOMDocument();
    @$dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
    $xpath = new DOMXPath($dom);

    // Find all spans inside divs with border (likely attribute containers)
    $divs = $xpath->query("//div[contains(@style, 'border')]");

    foreach ($divs as $div) {
        $spans = $div->getElementsByTagName('span');

        $found_name = '';
        $found_value = '';

        foreach ($spans as $span) {
            $style = $span->getAttribute('style');
            $text = trim($span->textContent);

            // Check if span likely an attribute name (bold or semi-bold font-weight)
            if (preg_match('/font-weight:\s*(bold|semi-bold)/i', $style) && strlen($text) > 1) {
                $found_name = $text;
            } 
            // If we have found a name, next non-empty span is value
            else if ($found_name !== '' && $text !== '') {
                $found_value = $text;
                break; // Found a pair, no need to check more spans here
            }
        }

        if ($found_name !== '' && $found_value !== '') {
            $attributes[$found_name] = $found_value;
        }
    }

    // Optional: write debug log to file
    file_put_contents(__DIR__ . '/parse-debug.txt', print_r($attributes, true));

    return $attributes;
}





// AJAX: process 5 products at a time for all categories one by one
add_action('wp_ajax_pdfae_process_all_categories', function(){
    global $wpdb;

    $categories = get_terms([
        'taxonomy' => 'product_cat',
        'hide_empty' => true,
        'orderby' => 'term_id',
        'order' => 'ASC',
    ]);

    $cat_index = intval($_POST['cat_index']);
    $offset = intval($_POST['offset']);

    if (!isset($categories[$cat_index])) {
        wp_send_json_success([
            'finished' => true,
            'message' => 'All categories processed.',
        ]);
        return;
    }

    $category = $categories[$cat_index];

    $args = [
        'post_type' => 'product',
        'posts_per_page' => 2,
        'tax_query' => [
            [
                'taxonomy' => 'product_cat',
                'field' => 'term_id',
                'terms' => $category->term_id,
            ],
        ],
        'offset' => $offset,
        'fields' => 'ids',
    ];

    $product_ids = get_posts($args);

    $upload_dir = wp_upload_dir();
    $base_path = trailingslashit($upload_dir['basedir']) . 'pdf-html-exports/';

    if (!file_exists($base_path)) {
        wp_mkdir_p($base_path);
    }

    $processed = [];

    foreach ($product_ids as $product_id) {
        $product = wc_get_product($product_id);
        $html = pdfae_get_pdf_html_for_product($product_id);

        $filename = sanitize_file_name($product->get_slug() . '-' . $product_id . '.html');
        $filepath = $base_path . $filename;

        file_put_contents($filepath, $html);

        $processed[] = $product->get_title();
    }

    $next_offset = $offset + count($product_ids);
    $next_cat_index = $cat_index;

    if (count($product_ids) < 5) {
        $next_cat_index++;
        $next_offset = 0;
    }

    wp_send_json_success([
        'processed' => $processed,
        'count' => count($product_ids),
        'cat_name' => $category->name,
        'cat_index' => $next_cat_index,
        'offset' => $next_offset,
        'finished' => false,
    ]);
});

// AJAX: parse saved HTML files and insert attributes into DB
add_action('wp_ajax_pdfae_parse_saved_html', function() {
    global $wpdb;

    $upload_dir = wp_upload_dir();
    $base_path = trailingslashit($upload_dir['basedir']) . 'pdf-html-exports/';

    $table_name ='taw_pdf_product_attributes';

    $files = glob($base_path . '*.html');
    if (!$files) {
        wp_send_json_error('No saved HTML files found.');
        return;
    }

    $total_inserted = 0;
    $processed_files = [];

    foreach ($files as $filepath) {
        $html = file_get_contents($filepath);

        if (preg_match('/-(\d+)\.html$/', basename($filepath), $matches)) {
            $product_id = intval($matches[1]);
        } else {
            continue;
        }

        $product = wc_get_product($product_id);
        if (!$product) continue;

        $category_names = wp_get_post_terms($product_id, 'product_cat', ['fields' => 'names']);
        $category_name = !empty($category_names) ? implode(', ', $category_names) : '';

        $attributes = pdfae_parse_attributes_from_html($html);

        $wpdb->delete($table_name, ['product_id' => $product_id]);

        foreach ($attributes as $name => $value) {
            $wpdb->insert($table_name, [
                'product_id' => $product_id,
                'category_name' => $category_name,
                'product_name' => $product->get_title(),
                'attribute_name' => $name,
                'attribute_value' => $value,
            ]);
            $total_inserted++;
        }

        $processed_files[] = basename($filepath);
    }

    wp_send_json_success([
        'files_processed' => $processed_files,
        'total_attributes_inserted' => $total_inserted,
    ]);
});

// Add admin menu page
add_action('admin_menu', function() {
    add_menu_page(
        'PDF Attribute Extractor',
        'PDF Attribute Extractor',
        'manage_options',
        'pdfae-admin',
        'pdfae_admin_page_callback',
        'dashicons-media-document',
        80
    );
});

function pdfae_admin_page_callback() {
    ?>
    <div class="wrap">
        <h1>PDF Attribute Extractor</h1>
        <button id="pdfae-start" class="button button-primary">Start Processing All Categories</button>
        <div id="pdfae-progress-bar" style="width:100%; height:20px; background:#eee; margin-top:15px; border-radius:4px; overflow:hidden; display:none;">
            <div style="height:100%; width:0%; background:#4caf50;"></div>
        </div>

        <textarea id="pdfae-log" readonly style="margin-top:10px; height:300px; width:100%; overflow-y:auto; background:#f9f9f9; border:1px solid #ccc; padding:5px; font-family: monospace; font-size: 13px;"></textarea>

        <button id="pdfae-parse" class="button" style="margin-top: 20px;">Parse Saved HTML Files</button>
    </div>

    <script>
    jQuery(document).ready(function($){
        let cat_index = 0;
        let offset = 0;
        let processing = false;

        $('#pdfae-start').on('click', function(){
            if(processing) return;
            processing = true;
            $('#pdfae-start').prop('disabled', true);
            $('#pdfae-progress-bar').show();
            $('#pdfae-progress-bar div').css('width', '0%');
            $('#pdfae-log').val('');
            cat_index = 0;
            offset = 0;
            processBatch();
        });

        function processBatch() {
            $.post(ajaxurl, {
                action: 'pdfae_process_all_categories',
                cat_index: cat_index,
                offset: offset
            }, function(response){
                if(response.success){
                    let data = response.data;
                    if(data.finished){
                        $('#pdfae-log').val($('#pdfae-log').val() + 'All categories processed.\n');
                        processing = false;
                        $('#pdfae-start').prop('disabled', false);
                        $('#pdfae-progress-bar').hide();
                        $('#pdfae-progress-bar div').css('width', '100%');
                        return;
                    }
                    cat_index = data.cat_index;
                    offset = data.offset;

                    data.processed.forEach(title => {
                        $('#pdfae-log').val($('#pdfae-log').val() + 'Processed: ' + title + ' (Category: ' + data.cat_name + ')\n');
                    });

                    let percent = ((cat_index / 100) * 100);
                    $('#pdfae-progress-bar div').css('width', percent + '%');

                    $('#pdfae-log').scrollTop($('#pdfae-log')[0].scrollHeight);

                    setTimeout(processBatch, 500);
                } else {
                    $('#pdfae-log').val($('#pdfae-log').val() + 'Error during processing\n');
                    processing = false;
                    $('#pdfae-start').prop('disabled', false);
                }
            });
        }

        $('#pdfae-parse').on('click', function(){
            $('#pdfae-log').val('Parsing saved HTML files...\n');
            $.post(ajaxurl, {action: 'pdfae_parse_saved_html'}, function(response){
                if(response.success){
                    $('#pdfae-log').val($('#pdfae-log').val() + 'Processed files:\n' + response.data.files_processed.join('\n') + '\n');
                    $('#pdfae-log').val($('#pdfae-log').val() + 'Total attributes inserted: ' + response.data.total_attributes_inserted + '\n');
                } else {
                    $('#pdfae-log').val($('#pdfae-log').val() + 'Error: ' + response.data + '\n');
                }
            });
        });
    });
    </script>
    <?php
}
add_action('wp_ajax_pdfae_parse_saved_html_files', function() {
    global $wpdb;
    $upload_dir = wp_upload_dir();
    $html_folder = $upload_dir['basedir'] . '/pdf-attribute-html/';
    $table_name = $wpdb->prefix . 'pdf_product_attributes';

    $files = glob($html_folder . '*.html');
    $total_inserted = 0;

    $debug_log = "";

    foreach ($files as $file) {
        $filename = basename($file);
        // Extract product ID from filename (assuming ID is at end before .html)
        if (preg_match('/-(\d+)\.html$/', $filename, $matches)) {
            $product_id = intval($matches[1]);
            $product = wc_get_product($product_id);

            if (!$product) {
                $debug_log .= "Product not found for ID: $product_id\n";
                continue;
            }

            $html = file_get_contents($file);

            // Parse attributes with your parsing function
            $attributes = pdfae_parse_attributes_from_html($html);

            // Log attributes for debugging
            $debug_log .= "Product: " . $product->get_title() . " (ID: $product_id)\n";
            $debug_log .= "Attributes found: " . count($attributes) . "\n";
            $debug_log .= print_r($attributes, true) . "\n\n";

            // Delete old entries
            $wpdb->delete($table_name, ['product_id' => $product_id]);

            // Insert only if attribute name and value are non-empty
            foreach ($attributes as $name => $value) {
                $name_trim = trim($name);
                $value_trim = trim($value);
                if ($name_trim !== '' && $value_trim !== '') {
                    $wpdb->insert($table_name, [
                        'product_id' => $product_id,
                        'category_name' => implode(', ', wp_get_post_terms($product_id, 'product_cat', ['fields' => 'names'])),
                        'product_name' => $product->get_title(),
                        'attribute_name' => $name_trim,
                        'attribute_value' => $value_trim,
                    ]);
                    $total_inserted++;
                }
            }
        } else {
            $debug_log .= "Could not extract product ID from filename: $filename\n";
        }
    }

    file_put_contents(__DIR__ . '/parse-debug.txt', $debug_log);

    wp_send_json_success([
        'message' => "Total attributes inserted: $total_inserted"
    ]);
});

