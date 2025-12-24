<?php
/*
Plugin Name: Rename Product Images
Description: Rename product images to product title.
*/

// Enqueue necessary scripts for AJAX
function enqueue_scripts_for_ajax() {
    wp_enqueue_script('rename-product-images-ajax', plugin_dir_url(__FILE__) . 'rename-product-images-ajax.js', array('jquery'), null, true);
    wp_localize_script('rename-product-images-ajax', 'rename_product_images_ajax', array('ajaxurl' => admin_url('admin-ajax.php')));
}
add_action('admin_enqueue_scripts', 'enqueue_scripts_for_ajax');


add_action('wp_ajax_rename_product_images', 'rename_product_images_ajax');

// AJAX script for triggering the renaming process
// AJAX handler for renaming product images
function rename_product_images_ajax() {
    if (!defined('ABSPATH')) {
        die();
    }

    require_once(ABSPATH . 'wp-load.php');

    // Get parameters from AJAX request
    $offset = isset($_POST['offset']) ? intval($_POST['offset']) : 0;
    $limit = 1; // Process one product at a time

    global $wpdb;

    $args = array(
        'post_type'      => 'product',  // adjust post type if necessary
        'posts_per_page' => $limit,
        'offset'         => $offset
    );

    $products = get_posts($args);

    $response = array(
        'total_processed' => 0,
        'success_count' => 0,
        'failed_count' => 0,
        'failed_products' => array(), // Store IDs of products where renaming failed
        'current' => $offset + 1,
        'total' => wp_count_posts('product')->publish
    );

    foreach ($products as $product) {
        $image_url = get_the_post_thumbnail_url($product->ID);

        // Check if the product has an image
        if ($image_url) {
            // Get the product title
            $product_title = get_the_title($product->ID);

            // Remove special characters and replace spaces with hyphens
            $cleaned_title = preg_replace('/[^\p{L}\p{N}\s]/u', '', $product_title);
            $cleaned_title = str_replace(' ', '-', $cleaned_title);

            // Get the attachment ID from the image URL
            $attachment_id = attachment_url_to_postid($image_url);

            // Get the file path and old file name
            $file_path = get_attached_file($attachment_id);
            $file_info = pathinfo($file_path);

            // Modify the file name
            $new_filename = $cleaned_title . '.' . $file_info['extension'];
            $new_file_path = $file_info['dirname'] . '/' . $new_filename;

            // Rename the file
            $rename_success = rename($file_path, $new_file_path);

            if ($rename_success) {
                // Update the attachment metadata with the new filename
                wp_update_attachment_metadata($attachment_id, array('file' => $new_filename));

                // Update _wp_attached_file post meta with the new file path
                update_post_meta($attachment_id, '_wp_attached_file', $new_file_path);

                // Set the new attachment as the featured image
                set_post_thumbnail($product->ID, $attachment_id);

                $response['success_count']++;
            } else {
                // If renaming failed, add product ID to the failed_products array
                $response['failed_count']++;
                $response['failed_products'][] = $product->ID;
            }
        }
        $response['total_processed']++;
    }

    wp_send_json_success($response);
}

// AJAX script for triggering the renaming process
function rename_product_images_ajax_script() {
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            var offset = 0;
            var isProcessing = false;

            function renameImages() {
                // Disable the button and show loading indicator
                $('#start-rename').prop('disabled', true).text('Processing...');

                $.ajax({
                    url: rename_product_images_ajax.ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'rename_product_images',
                        offset: offset
                    },
                    success: function(response) {
                        if (response.success) {
                            // Display progress
                            $('#rename-results').html('Processing ' + response.data.current + '/' + response.data.total);



                            // Enable the button after completion
                            $('#start-rename').prop('disabled', false).text('Start Renaming');

                            // Trigger next product if there are more products
                            if (response.data.current < response.data.total) {
                                offset++;
                                renameImages();
                            }
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            }

            $('#start-rename').on('click', function() {
                if (!isProcessing) {
                    isProcessing = true;
                    renameImages();
                }
            });
        });
    </script>
    <?php
}
add_action('admin_footer', 'rename_product_images_ajax_script');



// Add submenu page under Tools menu
function add_rename_images_submenu_page() {
    add_submenu_page(
        'edit.php?post_type=product',
        'Rename Product Images', // Page title
        'Rename Product Images', // Menu title
        'manage_options',
        'rename_product_images_page', // Menu slug
        'rename_product_images_page_callback' // Callback function to display page content
    );
}
add_action('admin_menu', 'add_rename_images_submenu_page');

// Callback function to display the submenu page content
function rename_product_images_page_callback() {
    ?>
    <div class="wrap">
        <h2>Rename Product Images</h2>
        <button id="start-rename" class="button button-primary">Start Renaming</button>
        <div id="rename-results" style="margin-top:10px;"></div>
    </div>
    <?php
}

