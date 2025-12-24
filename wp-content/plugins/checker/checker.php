<?php
/*
Plugin Name: Checker
Description: Compare attribute values (ignoring attribute names) of products from taw_product_attributes vs taw_pdf_product_attributes. Shows missing/value-missing and matched values per product. Supports CSV download.
Version: 1.9
Author: Your Name
*/

if (!defined('ABSPATH')) exit;

class Checker {
    private $pdf_table;
    private $prod_table;

    public function __construct() {
        global $wpdb;
        $this->pdf_table = 'taw_pdf_product_attributes';
        $this->prod_table = 'taw_product_attributes';

        add_action('admin_menu', [$this, 'add_checker_submenu']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts']);
        add_action('wp_ajax_checker_get_product_ids', [$this, 'ajax_get_product_ids']);
        add_action('wp_ajax_checker_compare_product', [$this, 'ajax_compare_product']);
    }

    public function add_checker_submenu() {
        add_submenu_page(
            'tools.php',
            'Checker',
            'Checker',
            'manage_options',
            'checker',
            [$this, 'render_checker_page']
        );
    }

    public function enqueue_scripts($hook) {
        if ($hook !== 'tools_page_checker') return;

        wp_add_inline_script('jquery-core', $this->get_js());
        wp_add_inline_style('wp-admin', $this->get_css());
    }

    private function get_js() {
        $nonce = wp_create_nonce('checker_nonce');
        return <<<JS
jQuery(function(\$){
    let productIds = [];
    let currentIndex = 0;
    let checking = false;
    let serialNum = 1;
    let allMissingData = [];
    let allMatchedData = [];

    \$('#checker-start-btn').on('click', function(){
        if(checking) return;
        checking = true;
        serialNum = 1;
        allMissingData = [];
        allMatchedData = [];

        \$('#checker-progress').show();
        \$('#checker-missing-box').show();
        \$('#checker-match-box').show();
        \$('#checker-missing-table tbody').empty();
        \$('#checker-match-table tbody').empty();
        \$('#checker-download-csv').hide();
        \$('#checker-status').text('Fetching product IDs...');

        \$.post(ajaxurl, {
            action: 'checker_get_product_ids',
            nonce: '$nonce'
        }, function(response){
            if(response.success){
                productIds = response.data;
                \$('#checker-status').text('Found ' + productIds.length + ' products. Starting comparison...');
                currentIndex = 0;
                compareNextProduct();
            } else {
                \$('#checker-status').text('Error fetching product IDs.');
                checking = false;
            }
        });
    });

    function compareNextProduct(){
        if(currentIndex >= productIds.length){
            \$('#checker-status').text('Comparison complete.');
            checking = false;
            \$('#checker-download-csv').show();
            return;
        }

        let pid = productIds[currentIndex];
        \$('#checker-status').text('Comparing product ID: ' + pid + ' (' + (currentIndex+1) + ' of ' + productIds.length + ')');

        \$.post(ajaxurl, {
            action: 'checker_compare_product',
            nonce: '$nonce',
            product_id: pid
        }, function(response){
            if(response.success){
                let data = response.data;

                let infoText = serialNum + '. Product ID: ' + data.product_id + ', Name: ' + data.product_name;

                // Append product info row inside missing table
                \$('#checker-missing-table tbody').append('<tr style="background:#f0f0f0; font-weight:bold;"><td colspan="2">' + infoText + '</td></tr>');

                if(data.missing.length === 0){
                    \$('#checker-missing-table tbody').append('<tr><td colspan="2" style="text-align:center;">No missing or value-missing attribute values in PDF table</td></tr>');
                } else {
                    data.missing.forEach(function(row){
                        \$('#checker-missing-table tbody').append(
                            '<tr>' +
                            '<td>' + row.value + '</td>' +
                            '<td>' + row.note + '</td>' +
                            '</tr>'
                        );
                        // Collect for CSV export
                        allMissingData.push({
                            product_serial: serialNum,
                            product_id: data.product_id,
                            product_name: data.product_name,
                            value: row.value,
                            note: row.note
                        });
                    });
                }

                // Append product info row inside matched table
                \$('#checker-match-table tbody').append('<tr style="background:#f0f0f0; font-weight:bold;"><td>' + infoText + '</td></tr>');

                if(data.matched.length === 0){
                    \$('#checker-match-table tbody').append('<tr><td style="text-align:center;">No matched attribute values</td></tr>');
                } else {
                    data.matched.forEach(function(row){
                        \$('#checker-match-table tbody').append(
                            '<tr>' +
                            '<td>' + row.value + '</td>' +
                            '</tr>'
                        );
                        // Collect for CSV export
                        allMatchedData.push({
                            product_serial: serialNum,
                            product_id: data.product_id,
                            product_name: data.product_name,
                            value: row.value
                        });
                    });
                }

                serialNum++;
            } else {
                \$('#checker-status').append('<br>Error comparing product ID: ' + pid);
            }

            currentIndex++;
            setTimeout(compareNextProduct, 150);
        });
    }

    \$('#checker-download-csv').on('click', function() {
        let csvContent = "data:text/csv;charset=utf-8,";
        csvContent += "Missing Attributes\\n";
        csvContent += "Serial,Product ID,Product Name,Attribute Value,Note\\n";
        allMissingData.forEach(function(row) {
            csvContent += [row.product_serial, row.product_id, '"' + row.product_name + '"', '"' + row.value + '"', '"' + row.note + '"'].join(",") + "\\n";
        });
        csvContent += "\\nMatched Attributes\\n";
        csvContent += "Serial,Product ID,Product Name,Attribute Value\\n";
        allMatchedData.forEach(function(row) {
            csvContent += [row.product_serial, row.product_id, '"' + row.product_name + '"', '"' + row.value + '"'].join(",") + "\\n";
        });

        let encodedUri = encodeURI(csvContent);
        let link = document.createElement("a");
        link.setAttribute("href", encodedUri);
        let filename = "attribute_values_comparison_" + new Date().toISOString().slice(0,10) + ".csv";
        link.setAttribute("download", filename);
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    });
});
JS;
    }

    private function get_css() {
        return <<<CSS
#checker-progress {
    font-family: monospace;
    font-size: 13px;
    max-height: 700px;
    overflow: auto;
    border: 1px solid #ddd;
    padding: 10px;
    margin-top: 20px;
    display: flex;
    flex-direction: column;
    gap: 30px;
}
.box {
    border: 1px solid #ccc;
    padding: 12px;
    border-radius: 5px;
}
#checker-missing-table td, #checker-missing-table th,
#checker-match-table td, #checker-match-table th {
    vertical-align: middle;
    word-break: break-word;
}
#checker-download-csv {
    margin-left: 20px;
}
CSS;
    }

    public function render_checker_page() {
        ?>
        <div class="wrap">
            <h1>Attribute Values Checker (Ignoring Attribute Names)</h1>
            <button id="checker-start-btn" class="button button-primary">Start Check</button>
            <button id="checker-download-csv" class="button button-secondary" style="display:none;">Download CSV</button>

            <div id="checker-progress" style="display:none;">
                <div id="checker-missing-box" class="box">
                    <h2>Missing or Value-Missing Attribute Values in PDF Table</h2>
                    <table class="widefat fixed striped" id="checker-missing-table" style="width:100%;">
                        <thead>
                            <tr>
                                <th>Attribute Value</th>
                                <th>Note</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>

                <div id="checker-match-box" class="box">
                    <h2>Matched Attribute Values</h2>
                    <table class="widefat fixed striped" id="checker-match-table" style="width:100%;">
                        <thead>
                            <tr>
                                <th>Attribute Value</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>

            <div id="checker-status" style="margin-top:10px;"></div>
        </div>
        <?php
    }

    public function ajax_get_product_ids() {
        check_ajax_referer('checker_nonce', 'nonce');

        global $wpdb;
        $product_ids = $wpdb->get_col("SELECT DISTINCT product_id FROM {$this->prod_table} ORDER BY product_id ASC");

        wp_send_json_success($product_ids);
    }

    public function ajax_compare_product() {
        check_ajax_referer('checker_nonce', 'nonce');

        if (empty($_POST['product_id'])) {
            wp_send_json_error('Missing product_id');
        }

        $pid = intval($_POST['product_id']);
        global $wpdb;

        // Get product_name for display
        $product_name = $wpdb->get_var($wpdb->prepare("SELECT product_name FROM {$this->prod_table} WHERE product_id = %d LIMIT 1", $pid));

        // Get attribute values ignoring attribute names
        $prod_values = $wpdb->get_col($wpdb->prepare("SELECT attribute_value FROM {$this->prod_table} WHERE product_id = %d", $pid));
        $pdf_values = $wpdb->get_col($wpdb->prepare("SELECT attribute_value FROM {$this->pdf_table} WHERE product_id = %d", $pid));
/*
        // Normalize: trim, filter empty strings
        $prod_values = array_filter(array_map('trim', $prod_values), function($v){ return $v !== ''; });
        $pdf_values = array_filter(array_map('trim', $pdf_values), function($v){ return $v !== ''; });

        // Count duplicates
        $prod_counts = array_count_values($prod_values);
        $pdf_counts = array_count_values($pdf_values);
*/
// Helper to normalize attribute values
// $normalize_value = function($v) {
//     return strtolower(preg_replace('/[\s\-_]+/', '', trim($v)));
// };
// $normalize_value = function($v) {
//     $v = html_entity_decode($v); // Decode HTML entities like &amp;
//     $v = preg_replace('/[,\|\/&]+/u', '', $v); // Remove separators: comma, pipe, slash, ampersand
//     $v = preg_replace('/[\s\p{Z}\-_]+/u', '', $v); // Remove spaces, tabs, non-breaking spaces, dashes, underscores
//     return strtolower(trim($v)); // Lowercase and trim
// };

$normalize_value = function($v) {
    $v = html_entity_decode($v); // Decode &amp;, &nbsp;, etc.

    // Replace all hyphens, en dashes, minus signs with a standard hyphen
    $v = preg_replace('/[‐‑‒–—―−]/u', '-', $v); // Covers U+2010 to U+2015 + U+2212

    // Replace all whitespace (spaces, non-breaking, tabs) with a single space
    $v = preg_replace('/[\s\p{Z}\xA0]+/u', ' ', $v);

    // Remove separators: commas, pipes, slashes, ampersands
    $v = preg_replace('/[,\|\/&]+/u', '', $v);

    // Remove all remaining spaces, underscores, dashes (if you want them merged)
    $v = preg_replace('/[\s\p{Z}\-_]+/u', '', $v);

    return strtolower(trim($v));
};

// Normalize and count values
$prod_values = array_filter(array_map($normalize_value, $prod_values), fn($v) => $v !== '');
$pdf_values  = array_filter(array_map($normalize_value, $pdf_values), fn($v) => $v !== '');

$prod_counts = array_count_values($prod_values);
$pdf_counts  = array_count_values($pdf_values);
        $missing = [];
        $matched = [];

        foreach ($prod_counts as $val => $count) {
            $pdf_count = $pdf_counts[$val] ?? 0;
            if ($pdf_count == 0) {
                $missing[] = [
                    'value' => esc_html($val),
                    'note' => "Value missing in PDF table (expected $count times, found 0)",
                ];
            } elseif ($pdf_count < $count) {
                $missing[] = [
                    'value' => esc_html($val),
                    'note' => "Value partially missing in PDF table (expected $count times, found $pdf_count)",
                ];
                $matched[] = ['value' => esc_html($val)];
            } else {
                $matched[] = ['value' => esc_html($val)];
            }
        }

        wp_send_json_success([
            'product_id' => $pid,
            'product_name' => $product_name ?: '-',
            'missing' => $missing,
            'matched' => $matched,
        ]);
    }
}

new Checker();
