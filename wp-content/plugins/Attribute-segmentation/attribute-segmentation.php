<?php
/*
Plugin Name: Attribute Segmentation
Description: A plugin to sorting attributes under these headings.
Version: 1.0
Author: Things at Web
*/
// Enqueue styles and scripts here if needed.
define('ATTR_SEGMENT_DIR', plugin_dir_url(__FILE__));
define('ATTR_SEGMENT_VERSION', "?v=1.187");

function attr_segment_menu() {
    add_menu_page(
    'Segmentation',
        'Segmentation',
        'manage_options',
        'attribute-segment',
        'render_attributesegment_product_page',
    );
    add_submenu_page(
        'attribute-segment',
        'Attribute Segment',
        'Attribute Segment',
        'manage_options',
        'attribute-segmention',
        'render_attributesegment_product_page'
    );
    add_submenu_page(
        'attribute-segment',  // Parent menu slug
        'Heading Setting', // Submenu page title
        'Heading Setting', // Submenu menu title
        'manage_options',   // Capability required to access the submenu
        'heading-setting',    // Submenu slug
        'render_datash_page' // Callback function to render the submenu page
    );
}
add_action('admin_menu', 'attr_segment_menu');

function remove_cus_setting_submenu() {
    global $submenu;
    unset($submenu['attribute-segment'][0]); // Replace 'filter-setting' with the actual parent menu slug
}
add_action('admin_menu', 'remove_cus_setting_submenu');

function render_attributesegment_product_page() {
    global $wpdb;

    // Fetch cate_no and art_no values
    // $cate_no_results = $wpdb->get_results("SELECT DISTINCT cate_no FROM taw_attribute_segment", ARRAY_A);
    // $art_no_results = $wpdb->get_results("SELECT DISTINCT art_no FROM taw_attribute_product_segment", ARRAY_A);
    $cate_no_results = $wpdb->get_results("SELECT DISTINCT cate_no FROM taw_attribute_segment ORDER BY cate_no ASC", ARRAY_A);
    $art_no_results = $wpdb->get_results("SELECT DISTINCT art_no FROM taw_attribute_product_segment ORDER BY art_no ASC", ARRAY_A);

    // Create a dropdown to toggle between Category and Product
    echo '<div id="main-dropdown" style="display: flex; align-items: center; gap: 10px;">';
    echo '<label for="main_select" style="font-size: 16px; font-weight: bold; margin-right: 10px; width:125px;">Sort Type</label>';
    echo '<select id="main_select" style="width: 300px; padding: 5px; border-radius: 4px; border: 1px solid #ccc;">';
    echo '<option value="">--Select--</option>';
    echo '<option value="category">Category</option>';
    echo '<option value="product">Product</option>';
    echo '</select>';
    echo '</div>';

    // Category section
    echo '<div id="seg" style="display:none;">';
    echo '<form id="seg_form" method="post" style="display: flex; align-items: center; gap: 10px;">';
    echo '<label for="cate_no" style="font-size: 16px; font-weight: bold; margin-right: 10px;">Select Category</label>';
    echo '<select name="cate_no" id="cate_no" style="width: 300px; padding: 5px; border-radius: 4px; border: 1px solid #ccc;">';
    foreach ($cate_no_results as $row) {
        echo '<option value="' . htmlspecialchars($row['cate_no']) . '">' . htmlspecialchars($row['cate_no']) . '</option>';
    }
    echo '</select>';
    echo '<input type="submit" name="submit" value="Submit" style="padding: 5px 10px; background-color: #0073aa; color: white; border: none; border-radius: 4px; cursor: pointer;">';
    echo '</form>';
    echo '<div id="loader" style="display: none; margin-top: 10px;"><img src="' . esc_url(admin_url('images/spinner.gif')) . '" alt="Loading..."></div>';
    echo '<div id="seg_results" class="d-none"></div>';
    echo '</div>';

    // Product section
    echo '<div id="artseg" style="display:none;">';
    echo '<form id="artseg_form" method="post" style="display: flex; align-items: center; gap: 10px;">';
    echo '<label for="art_no"style="font-size: 16px; font-weight: bold; margin-right: 10px;">Select Article No</label>';
    //echo '<div style="display: flex; align-items: center;">';
    echo '<select name="art_no" id="art_no" style="width: 300px; padding: 5px; border-radius: 4px; border: 1px solid #ccc;">';
    foreach ($art_no_results as $row) {
        echo '<option value="' . htmlspecialchars($row['art_no']) . '">' . htmlspecialchars($row['art_no']) . '</option>';
    }
    echo '</select>';
    echo '<input type="submit" name="submit" style="padding: 5px 10px; background-color: #0073aa; color: white; border: none; border-radius: 4px; cursor: pointer;">';
    // Add button to open modal
    echo '<a href="#TB_inline?&width=300&height=250&inlineId=custom-uam-alert-add-edit-dlg" title="' . esc_html__('Add Artno', 'custom-uam') . '" class="thickbox button button-primary" 
    style="position: absolute; right: 70px; border-radius: 8px;">+ ' . esc_html__('Add', 'custom-uam') . '</a>';
    // echo '</div>';
    echo '</form>';
    echo '<div id="artloader" style="display: none;"><img src="' . esc_url(admin_url('images/spinner.gif')) . '" alt="Loading..."></div>';
    echo '<div id="artseg_results" class="d-none"></div>';
    echo '</div>';

    // Modal for adding products
    echo '<div id="custom-uam-alert-add-edit-dlg" style="display:none;">';
    echo '<form id="custom_uam_save_seg_artno">';
    echo '<input type="text" placeholder="Enter Article Number" id="custom-uam-input-seg-artno" style="width: 79%; margin-right: 10px; margin-top: 10px; border-radius: 4px; padding: 5px;" />';
    echo '<button type="button" id="segadd-artno-btn" class="button button-primary" style="padding: 6px 12px;margin-top: 10px; border-radius: 4px;">Add</button>';
    echo '<div id="seg-search-results" style="position: absolute; background: #fff; border: 1px solid #ccc; display: none; max-height: 200px; overflow-y: auto; width: 69%; z-index: 1000; border-radius: 4px;">';
    echo '</div>';
    echo '<div id="artno-list-container" style="margin-top: 20px; max-height: 300px; overflow-y: auto;">';
    // Dynamically added items will be appended here
    echo '</div>';
    echo '<div class="wpml-dialog-footer" style="display: flex; justify-content: center; ">';
    echo '<button type="button"  id="seg-artno-btn" class="button button-primary" style="padding-left: 20px; padding-right: 20px; font-size: 14px;">Save</button>';
    echo '</div>';
    echo '</form>';
    echo '</div>';
}

add_shortcode('attributesegment_page', 'render_attributesegment_product_page');
function enqueue_thickbox_scripts() {
    wp_enqueue_script('thickbox');
    wp_enqueue_style('thickbox');
}
add_action('admin_enqueue_scripts', 'enqueue_thickbox_scripts');

add_action('wp_ajax_get_attr_category_segment_data', 'get_attr_category_segment_data');
add_action('wp_ajax_nopriv_get_attr_category_segment_data', 'get_attr_category_segment_data'); // For non-logged in users

function add_artno_to_segment() {
    global $wpdb;
    if (isset($_POST['artno']) && !empty($_POST['artno'])) {
        $searchvalue = sanitize_text_field($_POST['artno']);
        $attribute_ids = $wpdb->get_col("SELECT id FROM taw_attribute_heading");

        if (!empty($attribute_ids)) {
            $table_segment = 'taw_attribute_product_segment';

            foreach ($attribute_ids as $attribute_id) {
                $existing_record = $wpdb->get_var(
                    $wpdb->prepare(
                        "SELECT COUNT(*) FROM $table_segment WHERE art_no = %s AND attribute_id = %d",
                        $searchvalue,
                        $attribute_id
                    )
                );

                if ($existing_record == 0) {
                    $segmentdata = array(
                        'art_no' => $searchvalue,
                        'attribute_id' => $attribute_id,
                    );
                    $wpdb->insert($table_segment, $segmentdata);
                }
            }
        }

        wp_send_json_success(['message' => 'Article number added successfully.']);
    } else {
        wp_send_json_error(['message' => 'Invalid article number.']);
    }

    wp_die();
}
add_action('wp_ajax_add_artno_to_segment', 'add_artno_to_segment');
add_action('wp_ajax_nopriv_add_artno_to_segment', 'add_artno_to_segment'); // For non-logged in users

function save_artno_to_database() {
    global $wpdb;

    if (isset($_POST['artnos']) && is_array($_POST['artnos'])) {
        $artnos = array_map('sanitize_text_field', $_POST['artnos']);
        $attribute_ids = $wpdb->get_col("SELECT id FROM taw_attribute_heading");

        $table_segment = 'taw_attribute_product_segment';
        $duplicate_artnos = [];

        foreach ($artnos as $artno) {
            foreach ($attribute_ids as $attribute_id) {
                $existing_record = $wpdb->get_var(
                    $wpdb->prepare(
                        "SELECT COUNT(*) FROM $table_segment WHERE art_no = %s AND attribute_id = %d",
                        $artno,
                        $attribute_id
                    )
                );

                if ($existing_record > 0) {
                    // Add to duplicate article numbers list
                    $duplicate_artnos[] = $artno;
                    break; // No need to check further for this artno
                }
            }
        }

        if (!empty($duplicate_artnos)) {
            // Return error response with all duplicate article numbers
            wp_send_json_error([
                'message' => 'The following Article Numbers are already added to the database: ' . implode(', ', $duplicate_artnos)
            ]);
        }

        // If all article numbers are new, insert them into the database
        foreach ($artnos as $artno) {
            foreach ($attribute_ids as $attribute_id) {
                $segmentdata = array(
                    'art_no' => $artno,
                    'attribute_id' => $attribute_id,
                );
                $wpdb->insert($table_segment, $segmentdata);
            }
        }

        wp_send_json_success(['message' => 'Artno(s) successfully saved.']);
    } else {
        wp_send_json_error(['message' => 'Invalid data.']);
    }

    wp_die();
}
add_action('wp_ajax_save_artno_to_database', 'save_artno_to_database');

// Categorywise Sort

function get_attr_category_segment_data() {
    global $wpdb;

    if (isset($_POST['cate_no'])) {
        $selected_cate_no = sanitize_text_field($_POST['cate_no']);

        $product_features_query = $wpdb->prepare(
            "SELECT head.id, head.`attribute` FROM taw_attribute_segment as seg join taw_attribute_heading as head on seg.attribute_id=head.id
             WHERE seg.cate_no = %s AND head.heading = 'Product Features'",
            $selected_cate_no
        );
        $product_features = $wpdb->get_results($product_features_query, ARRAY_A);

        // Query to get attributes for Technical Specification
        $technical_specifications_query = $wpdb->prepare(
            "SELECT head.id, head.`attribute` FROM taw_attribute_segment as seg join taw_attribute_heading as head on seg.attribute_id=head.id
             WHERE seg.cate_no = %s AND head.heading = 'Technical Specification'",
             $selected_cate_no
        );
        $technical_specifications = $wpdb->get_results($technical_specifications_query, ARRAY_A);

        // Query to get attributes for Weight and volume
        $Weight_and_volume_query = $wpdb->prepare(
            "SELECT head.id, head.`attribute` FROM taw_attribute_segment as seg join taw_attribute_heading as head on seg.attribute_id=head.id
             WHERE seg.cate_no = %s AND head.heading = 'Weight and volume'",
             $selected_cate_no
        );
        $Weight_and_volume = $wpdb->get_results($Weight_and_volume_query , ARRAY_A);

        // Query to get attributes for Weight capacity support beam
        $weight_capacity_query = $wpdb->prepare(
            "SELECT head.id, head.`attribute` FROM taw_attribute_segment as seg join taw_attribute_heading as head on seg.attribute_id=head.id
             WHERE seg.cate_no = %s AND head.heading = 'Weight capacity support beam'",
             $selected_cate_no
        );
        $weight_capacity = $wpdb->get_results($weight_capacity_query, ARRAY_A);

        ob_start(); // Start output buffering
        echo '<center><h2>' . esc_html($selected_cate_no) . '</h2></center>';

        // Product Features Section
        echo '<div style="background-color: #e6e5e5; border-radius:20px; padding:20px; position: relative; box-sizing: border-box; min-height: 200px;">';

        // Save button
        echo '<button id="save_order" style="position: absolute; top:10px; right:20px; font-weight:bold; font-size:16px; background-color: #2271B1; color:white; padding:10px; width:120px; border:none; border-radius:5px; cursor:pointer; z-index: 10;">Save</button>';

        // Product Features Section
        echo '<h2 style="background-color: #2271B1; padding:15px; color:white; position:relative; border-radius: 10px; margin-top:40px;">Product Features';
        echo '<span class="toggle-icon" data-target="product_features" style="position:absolute; right:20px; top:15px; cursor:pointer;">+</span>';
        echo '</h2>';
        echo '<ul id="product_features" class="sortable" style="display:none; margin-top:10px;">';
        foreach ($product_features as $feature) {
            
            echo '<li style="background-color: white; padding:10px; margin-bottom:5px; color:black; border-radius: 5px;" data-id="' . esc_attr($feature['id']) . '">' . esc_html($feature['attribute']) . '</li>';
        }
        echo '</ul>';

        // Technical Specification Section
        echo '<h2 style="background-color: #2271B1; padding:15px; color:white; position:relative; border-radius: 10px; margin-top:20px;">Technical Specification';
        echo '<span class="toggle-icon" data-target="technical_specifications" style="position:absolute; right:20px; top:15px; cursor:pointer;">+</span>';
        echo '</h2>';
        echo '<ul id="technical_specifications" class="sortable" style="display:none; margin-top:10px;">';
        foreach ($technical_specifications as $specification) {
            echo '<li style="background-color: white; padding:10px; margin-bottom:5px; color:black; border-radius: 5px;" data-id="' . esc_attr($specification['id']) . '">' . esc_html($specification['attribute']) . '</li>';
        }
        echo '</ul>';

        // Weight and volume
        echo '<h2 style="background-color: #2271B1; padding:15px; color:white; position:relative; border-radius: 10px; margin-top:20px;">Weight and volume';
        echo '<span class="toggle-icon" data-target="weight_volume" style="position:absolute; right:20px; top:15px; cursor:pointer;">+</span>';
        echo '</h2>';
        echo '<ul id="weight_volume" class="sortable" style="display:none; margin-top:10px;">';
        foreach ($Weight_and_volume as $weightvolume) {
            echo '<li style="background-color: white; padding:10px; margin-bottom:5px; color:black; border-radius: 5px;" data-id="' . esc_attr($weightvolume['id']) . '">' . esc_html($weightvolume['attribute']) . '</li>';
        }
        echo '</ul>';

        // Weight capacity support beam
        echo '<h2 style="background-color: #2271B1; padding:15px; color:white; position:relative; border-radius: 10px; margin-top:20px;">Weight capacity support beam';
        echo '<span class="toggle-icon" data-target="weight_capacity" style="position:absolute; right:20px; top:15px; cursor:pointer;">+</span>';
        echo '</h2>';
        echo '<ul id="weight_capacity" class="sortable" style="display:none; margin-top:10px;">';
        foreach ($weight_capacity as $capacity) {
            echo '<li style="background-color: white; padding:10px; margin-bottom:5px; color:black; border-radius: 5px;" data-id="' . esc_attr($capacity['id']) . '">' . esc_html($capacity['attribute']) . '</li>';
        }
        echo '</ul>';
        echo '</div>';
        $html = ob_get_clean(); // Get the output
        wp_send_json_success(['html' => $html]); // Return the output as JSON
    } else {
        wp_send_json_error(['message' => 'Invalid category selected.']);
    }

    wp_die(); 
 }
 add_action('wp_ajax_save_reordered_attributes', 'save_reordered_attributes');

 function save_reordered_attributes() {
     global $wpdb;
 
     $cate_no = sanitize_text_field($_POST['cate_no']);
     $product_features = isset($_POST['product_features']) ? $_POST['product_features'] : [];
     $technical_specifications = isset($_POST['technical_specifications']) ? $_POST['technical_specifications'] : [];
     $weight_volume = isset($_POST['weight_volume']) ? $_POST['weight_volume'] : [];
     $weight_capacity = isset($_POST['weight_capacity']) ? $_POST['weight_capacity'] : [];
 
     if (empty($cate_no)) {
         wp_send_json_error(['message' => 'Invalid category.']);
     }
 
     $wpdb->query('START TRANSACTION');
 
     try {
         // Delete old entries for the category
         $wpdb->delete('taw_attribute_segment', ['cate_no' => $cate_no]);
 
         // Insert reordered Product Features
         foreach ($product_features as $index => $feature) {
             $wpdb->insert('taw_attribute_segment', [
                 'cate_no' => $cate_no,
                 'attribute_id' => intval($feature['id']), // Save the attribute ID
                 //'order_index' => $index, // Optional: Store order for reference
             ]);
         }
 
         // Insert reordered Technical Specifications
         foreach ($technical_specifications as $index => $specification) {
             $wpdb->insert('taw_attribute_segment', [
                 'cate_no' => $cate_no,
                 'attribute_id' => intval($specification['id']), // Save the attribute ID
                 //'order_index' => $index,
             ]);
         }
 
         // Insert reordered Weight Volume
         foreach ($weight_volume as $index => $wegtvolume) {
             $wpdb->insert('taw_attribute_segment', [
                 'cate_no' => $cate_no,
                 'attribute_id' => intval($wegtvolume['id']), // Save the attribute ID
                 //'order_index' => $index,
             ]);
         }
 
         // Insert reordered Weight Capacity
         foreach ($weight_capacity as $index => $capacity) {
             $wpdb->insert('taw_attribute_segment', [
                 'cate_no' => $cate_no,
                 'attribute_id' => intval($capacity['id']), // Save the attribute ID
                 //'order_index' => $index,
             ]);
         }
 
         $wpdb->query('COMMIT');
         wp_send_json_success(['message' => 'Attributes reordered successfully.']);
     } catch (Exception $e) {
         $wpdb->query('ROLLBACK');
         wp_send_json_error(['message' => 'Failed to reorder attributes.', 'error' => $e->getMessage()]);
     }
 
     wp_die();
 }
 
//  Productwise Sort

add_action('wp_ajax_get_attr_product_segment_data', 'get_attr_product_segment_data');
add_action('wp_ajax_nopriv_get_attr_product_segment_data', 'get_attr_product_segment_data');

function get_attr_product_segment_data() {
    global $wpdb;

    if (isset($_POST['art_no']) && !empty($_POST['art_no'])) {
        $selected_art_no = sanitize_text_field($_POST['art_no']);
        error_log('Received art_no: ' . $selected_art_no);

        $art_no_exists = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT COUNT(*) FROM taw_attribute_product_segment WHERE art_no = %s",
                $selected_art_no
            )
        );

        if (!$art_no_exists) {
            error_log('art_no does not exist in the database: ' . $selected_art_no);
            wp_send_json_error(['message' => 'Invalid Product selected.']);
        }

        $product_features_query = $wpdb->prepare(
            "SELECT head.id, head.`attribute` FROM taw_attribute_product_segment as seg join taw_attribute_heading as head on seg.attribute_id=head.id
             WHERE seg.art_no = %s AND head.heading = 'Product Features'",
            $selected_art_no
        );
        $product_features = $wpdb->get_results($product_features_query, ARRAY_A);
        if ($product_features === false) {
            error_log('Error in Product Features query: ' . $wpdb->last_error);
            wp_send_json_error(['message' => 'Error fetching Product Features.']);
        }
        // Query to get attributes for Technical Specification
        $technical_specifications_query = $wpdb->prepare(
            "SELECT head.id, head.`attribute` FROM taw_attribute_product_segment as seg join taw_attribute_heading as head on seg.attribute_id=head.id
             WHERE seg.art_no = %s AND head.heading = 'Technical Specification'",
             $selected_art_no
        );
        $technical_specifications = $wpdb->get_results($technical_specifications_query, ARRAY_A);

        // Query to get attributes for Weight and volume
        $Weight_and_volume_query = $wpdb->prepare(
            "SELECT head.id, head.`attribute` FROM taw_attribute_product_segment as seg join taw_attribute_heading as head on seg.attribute_id=head.id
             WHERE seg.art_no = %s AND head.heading = 'Weight and volume'",
             $selected_art_no
        );
        $Weight_and_volume = $wpdb->get_results($Weight_and_volume_query , ARRAY_A);

        // Query to get attributes for Weight capacity support beam
        $weight_capacity_query = $wpdb->prepare(
            "SELECT head.id, head.`attribute` FROM taw_attribute_product_segment as seg join taw_attribute_heading as head on seg.attribute_id=head.id
             WHERE seg.art_no = %s AND head.heading = 'Weight capacity support beam'",
             $selected_art_no
        );
        $weight_capacity = $wpdb->get_results($weight_capacity_query, ARRAY_A);

        ob_start(); // Start output buffering
        // echo '<center><h2>' . esc_html($selected_art_no) . '</h2></center>';
        // Center heading with delete button
        echo '<div style="display: flex; align-items: center; justify-content: center; position: relative; margin-bottom: 20px;">';

        // Centered Heading
        echo '<h2 id="artheading" style="margin: 0; font-size: 20px;">' . esc_html($selected_art_no) . '</h2>';
        $deleteicon = ATTR_SEGMENT_DIR . 'img/close-circle.png';
        // Delete Button positioned on the right
        echo '<button id="delete-artbutton" style="position: absolute; right: 10px; background-color: transparent; border: none; cursor: pointer;">
        <img  src="' . $deleteicon . '" alt="Delete" style="width: 30px; height: 30px; display: block;" />
        </button>';        
        echo '</div>';

        // Product Features Section
        echo '<div style="background-color: #e6e5e5; border-radius:20px; padding:20px; position: relative; box-sizing: border-box; min-height: 200px;">';

        // Save button
        echo '<button id="save_artorder" style="position: absolute; top:10px; right:20px; font-weight:bold; font-size:16px; background-color: #2271B1; color:white; padding:10px; width:120px; border:none; border-radius:5px; cursor:pointer; z-index: 10;">Save</button>';

        // Product Features Section
        echo '<h2 style="background-color: #2271B1; padding:15px; color:white; position:relative; border-radius: 10px; margin-top:40px;">Product Features';
        echo '<span class="arttoggle-icon" data-target="artproduct_features" style="position:absolute; right:20px; top:15px; cursor:pointer;">+</span>';
        echo '</h2>';
        echo '<ul id="artproduct_features" class="sortable" style="display:none; margin-top:10px;">';
        foreach ($product_features as $feature) {
            
            echo '<li style="background-color: white; padding:10px; margin-bottom:5px; color:black; border-radius: 5px;" data-id="' . esc_attr($feature['id']) . '">' . esc_html($feature['attribute']) . '</li>';
        }
        echo '</ul>';

        // Technical Specification Section
        echo '<h2 style="background-color: #2271B1; padding:15px; color:white; position:relative; border-radius: 10px; margin-top:20px;">Technical Specification';
        echo '<span class="arttoggle-icon" data-target="arttechnical_specifications" style="position:absolute; right:20px; top:15px; cursor:pointer;">+</span>';
        echo '</h2>';
        echo '<ul id="arttechnical_specifications" class="sortable" style="display:none; margin-top:10px;">';
        foreach ($technical_specifications as $specification) {
            echo '<li style="background-color: white; padding:10px; margin-bottom:5px; color:black; border-radius: 5px;" data-id="' . esc_attr($specification['id']) . '">' . esc_html($specification['attribute']) . '</li>';
        }
        echo '</ul>';

        // Weight and volume
        echo '<h2 style="background-color: #2271B1; padding:15px; color:white; position:relative; border-radius: 10px; margin-top:20px;">Weight and volume';
        echo '<span class="arttoggle-icon" data-target="artweight_volume" style="position:absolute; right:20px; top:15px; cursor:pointer;">+</span>';
        echo '</h2>';
        echo '<ul id="artweight_volume" class="sortable" style="display:none; margin-top:10px;">';
        foreach ($Weight_and_volume as $weightvolume) {
            echo '<li style="background-color: white; padding:10px; margin-bottom:5px; color:black; border-radius: 5px;" data-id="' . esc_attr($weightvolume['id']) . '">' . esc_html($weightvolume['attribute']) . '</li>';
        }
        echo '</ul>';

        // Weight capacity support beam
        echo '<h2 style="background-color: #2271B1; padding:15px; color:white; position:relative; border-radius: 10px; margin-top:20px;">Weight capacity support beam';
        echo '<span class="arttoggle-icon" data-target="artweight_capacity" style="position:absolute; right:20px; top:15px; cursor:pointer;">+</span>';
        echo '</h2>';
        echo '<ul id="artweight_capacity" class="sortable" style="display:none; margin-top:10px;">';
        foreach ($weight_capacity as $capacity) {
            echo '<li style="background-color: white; padding:10px; margin-bottom:5px; color:black; border-radius: 5px;" data-id="' . esc_attr($capacity['id']) . '">' . esc_html($capacity['attribute']) . '</li>';
        }
        echo '</ul>';

        echo '</div>';

        $html = ob_get_clean(); // Get the output
        wp_send_json_success(['html' => $html]); // Return the output as JSON
    } else {
        error_log('art_no not set or empty in the POST request.');
        wp_send_json_error(['message' => 'Invalid Product selected.']);
    }

    wp_die(); 
} 

add_action('wp_ajax_save_reordered_art_attributes', 'save_reordered_art_attributes');

function save_reordered_art_attributes() {
    global $wpdb;

    $art_no = sanitize_text_field($_POST['art_no']);
    $product_features = isset($_POST['product_features']) ? $_POST['product_features'] : [];
    $technical_specifications = isset($_POST['technical_specifications']) ? $_POST['technical_specifications'] : [];
    $weight_volume = isset($_POST['weight_volume']) ? $_POST['weight_volume'] : [];
    $weight_capacity = isset($_POST['weight_capacity']) ? $_POST['weight_capacity'] : [];

    if (empty($art_no)) {
        wp_send_json_error(['message' => 'Invalid category.']);
    }

    $wpdb->query('START TRANSACTION');

    try {
        // Delete old entries for the category
        $wpdb->delete('taw_attribute_product_segment', ['art_no' => $art_no]);

        // Insert reordered Product Features
        foreach ($product_features as $index => $feature) {
            $wpdb->insert('taw_attribute_product_segment', [
                'art_no' => $art_no,
                'attribute_id' => intval($feature['id']), // Save the attribute ID
                //'order_index' => $index, // Optional: Store order for reference
            ]);
        }

        // Insert reordered Technical Specifications
        foreach ($technical_specifications as $index => $specification) {
            $wpdb->insert('taw_attribute_product_segment', [
                'art_no' => $art_no,
                'attribute_id' => intval($specification['id']), // Save the attribute ID
                //'order_index' => $index,
            ]);
        }

        // Insert reordered Weight Volume
        foreach ($weight_volume as $index => $wegtvolume) {
            $wpdb->insert('taw_attribute_product_segment', [
                'art_no' => $art_no,
                'attribute_id' => intval($wegtvolume['id']), // Save the attribute ID
                //'order_index' => $index,
            ]);
        }

        // Insert reordered Weight Capacity
        foreach ($weight_capacity as $index => $capacity) {
            $wpdb->insert('taw_attribute_product_segment', [
                'art_no' => $art_no,
                'attribute_id' => intval($capacity['id']), // Save the attribute ID
                //'order_index' => $index,
            ]);
        }

        $wpdb->query('COMMIT');
        wp_send_json_success(['message' => 'Attributes reordered successfully.']);
    } catch (Exception $e) {
        $wpdb->query('ROLLBACK');
        wp_send_json_error(['message' => 'Failed to reorder attributes.', 'error' => $e->getMessage()]);
    }

    wp_die();
}

function delete_art_attributes() {
    global $wpdb;

    if (!isset($_POST['art_no']) || empty($_POST['art_no'])) {
        error_log('Delete failed: Missing art_no');
        wp_send_json_error(['message' => 'Invalid or missing article number.']);
    }

    $art_no = sanitize_text_field($_POST['art_no']);
    error_log("Attempting to delete: $art_no");

    // Perform the deletion
    $deleted = $wpdb->delete('taw_attribute_product_segment', ['art_no' => $art_no]);

    if ($deleted !== false) {
        error_log("Deleted successfully: $art_no");
        wp_send_json_success(['message' => 'Article attributes deleted successfully.']);
    } else {
        error_log('Delete failed in database query.');
        wp_send_json_error(['message' => 'Failed to delete article attributes.']);
    }

    wp_die(); // Required for WordPress AJAX
}
add_action('wp_ajax_delete_art_attributes', 'delete_art_attributes');
add_action('wp_ajax_nopriv_delete_art_attributes', 'delete_art_attributes');



// ---------------


function render_datash_page() {
    global $wpdb; ?>
    <style>
        ul#c_uam_segcaprole_ul {
        padding: 10px;
    }

    ul#c_uam_segcaprole_ul li {
        padding: 10px;
    }

        /* For custom_segrestrict_ls */
    ul.custom_segrestrict_ls {
        padding: 10px;
    }

    ul.custom_segrestrict_ls li {
        padding: 15px 30px;
        cursor: pointer;
        position: relative;
        background: #FFFFFF 0% 0% no-repeat padding-box;
        color: black;
        border: 1px solid #E5E5E5;
        border-radius: 6px;
        opacity: 1;
        font-weight: bold;
    }

    ul.custom_segrestrict_ls li.active{
        color: white;
        background: rgb(34, 113, 177);
        border: 2px solid #2271B1;
    }

    ul.custom_restrictuser_ls {
        padding: 4px;
    }

    ul.custom_restrictuser_ls li {
        padding: 15px 30px;
        cursor: pointer;
        position: relative;
        background: #FFFFFF 0% 0% no-repeat padding-box;
        color: black;
        border: 1px solid #E5E5E5;
        border-radius: 6px;
        opacity: 1;
        width:375px;
        
    }

    ul.custom_restrictuser_ls li.active{
        color: white;
        background: rgb(34, 113, 177);
        border: 2px solid #2271B1;
    }

    .seg-tab {
        padding: 5px 20px;
        text-decoration: none;
        color: black;
        border: 2px solid #E5E5E5;
        background: #cccccc;
        transition: background-color 0.3s, color 0.3s, border 0.3s;
        margin: 0;
    }

    .seg-tab-active {
        background-color: #2271B1;
        border: 2px solid #2271B1;
        color: white !important;
    }

    nav {
        display: flex;
        gap: 0;
    }

    #entered-artnos li {
    display: flex;
    flex-direction: column;
    background-color: #FFFFFF;
    padding: 10px;
    margin: 5px 0;
    border-radius: 4px;
    }

    #entered-artnos li div {
        display: flex;
        align-items: start;
    }

    #entered-artnos li div span {
        color: #000000;
    }

    #entered-artnos li div span:nth-child(1) {
        flex: 1;
        font-weight: bold;
    }

    #entered-artnos li div span:nth-child(2) {
        flex: 0 0 10px;
        text-align: start;
    }

    #entered-artnos li div span:nth-child(3) {
        flex: 2;
    }

    #entered-artnos li div img {
        cursor: pointer;
        width: 23px;
        height: 23px;
        margin-left: 10px;
    }
  
    #user-search {
    background: url('<?php echo THINGSATWEB_BASE; ?>/img/search.png') no-repeat;
    background-position: 10px center; /* Adjust the position as needed */
    background-size: 14px 14px; /* Adjust the size as needed */
    padding-left: 35px; /* Make room for the icon */
    }

    #custom-uam-alert-delete-dlg {
    display: none;
    position: fixed;
    z-index: 100051;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

#deleteover {
    background: #000;
    opacity: 0.7;
    filter: alpha(opacity = 70);
    position: fixed;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    z-index: 100050;}

    
    .art-search-item:hover {
    background-color: #2271b1; /* Change background color on hover */
    color: #fff; /* Optional: Change text color on hover */
}

.scrollable-list-container {
    max-height: 600px; /* Adjust height as needed */
    overflow-y: auto;
    border: 1px solid #ccc;
    border-radius: 4px;
    margin-top:28px;
    width: 455px;
}

/* Optional: Style the scrollbar */
.scrollable-list-container::-webkit-scrollbar {
    width: 8px;
}

.scrollable-list-container::-webkit-scrollbar-track {
    background: #ccc; 
}

.scrollable-list-container::-webkit-scrollbar-thumb {
    background: #2271B1;
    border-radius: 4px;
}

.scrollable-list-container::-webkit-scrollbar-thumb:hover {
    background: #2271B1;
}

.list-item.active {
    background-color: #2271B1; /* Blue background */
    color: white; /* White text */
}

.restrict-tooltip-arrow {
    position: absolute;
    bottom: -5px; /* Arrow height */
    left: 50%;
    transform: translateX(-50%);
    width: 0;
    height: 0;
    border-left: 5px solid transparent;
    border-right: 5px solid transparent;
    border-top: 5px solid rgba(0, 0, 0, 0.7);
}

.restrict-tooltip {
    position: absolute;
    bottom: calc(100% + 5px); /* Position above the button */
    left: 50%;
    transform: translateX(-50%);
    background-color: rgba(0, 0, 0, 0.7);
    color: #fff;
    padding: 5px 10px;
    border-radius: 4px;
    font-size: 12px;
    display: none; /* Initially hidden */
    z-index: 1;
    white-space: nowrap; /* Prevent line breaks */
}
.restrict-tooltipuser-arrow {
    position: absolute;
    bottom: -5px; /* Arrow height */
    left: 50%;
    transform: translateX(-50%);
    width: 0;
    height: 0;
    border-left: 5px solid transparent;
    border-right: 5px solid transparent;
    border-top: 5px solid rgba(0, 0, 0, 0.7);
}

.restrict-tooltipuser {
    position: absolute;
    bottom: calc(100% + 5px); /* Position above the button */
    left: 50%;
    transform: translateX(-50%);
    background-color: rgba(0, 0, 0, 0.7);
    color: #fff;
    padding: 5px 10px;
    border-radius: 4px;
    font-size: 12px;
    display: none; /* Initially hidden */
    z-index: 1;
    white-space: nowrap; /* Prevent line breaks */
}

.loader {
    border: 4px solid rgba(0, 0, 0, 0.3);
    border-radius: 50%;
    border-top: 4px solid #3498db;
    width: 30px;
    height: 30px;
    animation: spin 1s linear infinite;
    margin: 40px auto;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

#loaderhid {
    display: none;
    position: relative;
    min-height: 80vh;
}

.aloader {
    background-image:url('<?php echo THINGSATWEB_BASE; ?>/img/loading.gif');
    height: 50px;  
    width: 50px;
    background-size: 100% 100%;
    background-repeat: no-repeat;
    position: absolute; /* Position relative to #loaderhid */
    top: 0; /* Align to the top */
    left: 50%; /* Center horizontally */
    transform: translateX(-50%); /* Center horizontally */
    animation: mymove 5s infinite;
}

#loaderuser {
    display: none;
    position: relative;
    min-height: 80vh;
}

.aloaderuser {
    background-image:url('<?php echo THINGSATWEB_BASE; ?>/img/loading.gif');
    height: 50px;  
    width: 50px;
    background-size: 100% 100%;
    background-repeat: no-repeat;
    position: absolute; /* Position relative to #loaderhid */
    top: 0; /* Align to the top */
    left: 50%; /* Center horizontally */
    transform: translateX(-50%); /* Center horizontally */
    animation: mymove 5s infinite;
}

@keyframes mymove {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
}
    </style>

<div id="custom-uam-alert-addattributevalues-dlg" style="display: none; padding: 20px;">
    <div style="display: flex; align-items: center; margin-bottom: 20px; margin-top: 25px;">
        <div style="margin-right: 20px;">
            <label for="addattribute-select" style="font-weight: bold; font-size: 14px;">Select Attribute <span style="color: red;">*</span></label>
        </div>
        <select id="addattribute-select" name="addattribute-select" style="width: 220px; font-size: 14px;">
            <option value="" disabled selected>Select Attribute</option>
        </select>
    </div>

    <!-- Row for Attribute Name -->
    <div style="display: flex; align-items: center; margin-bottom: 20px; margin-top:25px;">
        <div style="margin-right: 20px;">
            <label for="translationaddattribute-name" style="font-weight: bold; font-size: 14px;">Swedish Translation <span style="color: red;">*</span></label>
        </div>
        <input type="text" id="translationaddattribute-name" name="translationaddattribute-name" value="" style="width: 275px; margin-right: 40px; font-size: 14px;">
    </div>

    <!-- Row for Datasheet -->
    <div style="display: flex; align-items: center;">
        <div style="margin-right: 20px;">
            <label for="adddatasheet-image" style="font-weight: bold; font-size: 14px;">Datasheet</label>
        </div>
        <div style="text-align: center; margin-top: 10px; margin-left: 50px; display: flex; flex-direction: column; align-items: center;">
            <div style="position: relative; border: 1px solid #ccc; border-radius: 4px; width: 100px; height: 80px; overflow: hidden; display: flex; justify-content: center; align-items: center;">
                <img id="adddatasheet-image-preview" src="" alt="Datasheet Image"
                    style="width: 100%; height: 100%; object-fit: contain;" />
            </div>
            <button id="adddatasheet-image-btn" type="button" class="button button-secondary" style="margin-top: 10px;">Upload Image</button>
        </div>
        <!-- Width and Depth for Datasheet -->
        <div style="margin-left: 20px; display: flex; flex-direction: column;">
            <label style="font-size: 14px; margin-bottom: 5px; font-weight:bold;">Width:</label>
            <div style="display: flex; align-items: center;">
                <input type="number" id="datasheet-width" name="datasheet-width" value="25" min="1" max="1000" style="width: 60px; font-size: 14px;" />
                <span style="margin-left: 5px;">px</span>
            </div>
            <label style="font-size: 14px; margin-bottom: 5px; margin-top: 10px; font-weight:bold;">Height:</label>
            <div style="display: flex; align-items: center;">
                <input type="number" id="datasheet-height" name="datasheet-height" value="25" min="1" max="1000" style="width: 60px; font-size: 14px;" />
                <span style="margin-left: 5px;">px</span>
            </div>
        </div>
    </div>

    <!-- Row for Product Page -->
    <div style="display: flex; align-items: center; margin-top: 20px;">
        <div style="margin-right: 20px;">
            <label for="addproductpage-image" style="font-weight: bold; font-size: 14px;">Product Page</label>
        </div>
        <div style="text-align: center;margin-top: 10px; margin-left: 30px; display: flex; flex-direction: column; align-items: center;">
            <div style="position: relative; border: 1px solid #ccc; border-radius: 4px; width: 100px; height: 80px; overflow: hidden; display: flex; justify-content: center; align-items: center;">
                <img id="addproductpage-image-preview" src="" alt="Product Page Image"
                    style="width: 100%; height: 100%; object-fit: contain;" />
            </div>
            <button id="addproductpage-image-btn" type="button" class="button button-secondary" style="margin-top: 10px;">Upload Image</button>
        </div>
        <!-- Width and Depth for Product Page -->
        <div style="margin-left: 20px; display: flex; flex-direction: column;">
            <label style="font-size: 14px; margin-bottom: 5px; font-weight:bold;">Width:</label>
            <div style="display: flex; align-items: center;">
                <input type="number" id="productpage-width" name="productpage-width" value="30" min="1" max="1000" style="width: 60px; font-size: 14px;" />
                <span style="margin-left: 5px;">px</span>
            </div>
            <label style="font-size: 14px; margin-bottom: 5px; margin-top: 10px; font-weight:bold;">Height:</label>
            <div style="display: flex; align-items: center;">
                <input type="number" id="productpage-height" name="productpage-height" value="50" min="1" max="1000" style="width: 60px; font-size: 14px;" />
                <span style="margin-left: 5px;">px</span>
            </div>
        </div>
    </div>

    <div class="wpml-dialog-footer" style="display: flex; justify-content: center;">
        <button type="button" id="save-addattribute" class="button button-primary">Save</button>
    </div>
</div>


<div id="custom-uam-alert-diagramadd-edit-dlg" style="display: none; padding: 20px;">
    <input type="hidden" id="attribute-name" name="attribute-name" value="">
    <input type="hidden" id="attribute-id" name="attribute-id" value="">
    <div style="display: flex; align-items: center; margin-top:25px;">
            <div style="margin-right: 0px;">
                <label for="translationeditattribute-name" style="font-weight: bold; font-size: 14px;">Swedish Translation <span style="color: red;">*</span></label>
            </div>
            <input type="text" id="translationeditattribute-name" name="translationeditattribute-name" style="width: 275px; margin-right: 30px; font-size: 14px;">
        </div>
    <!-- Row for Datasheet Image -->
    <div style="display: flex; align-items: center;">
        <!-- Label -->
        <div style="margin-right: 20px;">
            <label for="datasheet-image" style="font-weight: bold; font-size: 14px;">Datasheet</label>
        </div>
        <!-- Image and Upload Button -->
        <div style="text-align: center; margin: 20px; display: flex; flex-direction: column; align-items: center;">
            <div style="position: relative; border: 1px solid #ccc; border-radius: 4px; width: 100px; height: 80px; overflow: hidden; display: flex; justify-content: center; align-items: center;">
                <img id="datasheet-image-preview" src="" alt="Datasheet Image" 
                    style="width: 70%; height: 70%; object-fit: contain; cursor: pointer;" />
            </div>
            <button id="edit-datasheet-image-btn" type="button" class="button button-secondary" style="margin-top: 10px;">Upload Image</button>
        </div>

        <div style="margin-left: 0px; display: flex; flex-direction: column;">
            <label style="font-size: 14px; margin-bottom: 5px; font-weight:bold;">Width:</label>
            <div style="display: flex; align-items: center;">
                <input type="number" id="editdatasheet-width" name="editdatasheet-width"  min="1" max="1000" style="width: 60px; font-size: 14px;" />
                <span style="margin-left: 5px;">px</span>
            </div>
            <label style="font-size: 14px; margin-bottom: 5px; margin-top: 10px; font-weight:bold;">Height:</label>
            <div style="display: flex; align-items: center;">
                <input type="number" id="editdatasheet-height" name="editdatasheet-height"  min="1" max="1000" style="width: 60px; font-size: 14px;" />
                <span style="margin-left: 5px;">px</span>
            </div>
        </div>
    </div>

    <!-- Row for Product Page Image -->
    <div style="display: flex; align-items: center;">
        <!-- Label -->
        <div style="margin-right: 20px;">
            <label for="productpage-image" style="font-weight: bold; font-size: 14px;">Product Page</label>
        </div>
        <!-- Image and Upload Button -->
        <div style="text-align: center; display: flex; flex-direction: column; align-items: center;">
            <div style="position: relative; border: 1px solid #ccc; border-radius: 4px; width: 100px; height: 80px; overflow: hidden; display: flex; justify-content: center; align-items: center;">
                <img id="productpage-image-preview" src="" alt="Product Page Image" 
                    style="width: 70%; height: 70%; object-fit: contain; cursor: pointer;" />
            </div>
            <button id="edit-productpage-image-btn" type="button" class="button button-secondary" style="margin-top: 10px;">Upload Image</button>
        </div>

        <div style="margin-left: 20px; display: flex; flex-direction: column;">
            <label style="font-size: 14px; margin-bottom: 5px; font-weight:bold;">Width:</label>
            <div style="display: flex; align-items: center;">
                <input type="number" id="editproductpage-width" name="editproductpage-width"  min="1" max="1000" style="width: 60px; font-size: 14px;" />
                <span style="margin-left: 5px;">px</span>
            </div>
            <label style="font-size: 14px; margin-bottom: 5px; margin-top: 10px; font-weight:bold;">Height:</label>
            <div style="display: flex; align-items: center;">
                <input type="number" id="editproductpage-height" name="editproductpage-height"  min="1" max="1000" style="width: 60px; font-size: 14px;" />
                <span style="margin-left: 5px;">px</span>
            </div>
        </div>
    </div>

    <div class="wpml-dialog-footer" style="display: flex; justify-content: center; ">
        <button type="button" id="save-attribute" class="button button-primary">Save</button>
    </div>
</div>


<div id="custom-uam-subattribute-add-dlg" style="display:none; padding:20px;">
    <div style="display: flex; align-items: center; margin-bottom: 20px; margin-top:25px;">
        <div style="margin-right: 15px;">
            <label for="subattribute-name" style="font-weight: bold; font-size: 14px;">Attribute Value <span style="color: red;">*</span></label>
        </div>
        <select id="subattribute-name" name="subattribute-name" style="width: 220px; font-size: 14px;">
            <option value="">Select an Attribute Value</option>
        </select>
    </div>

    <!-- Row for Datasheet Image -->
    <div style="display: flex; align-items: center;">
        <div style="margin-right: 20px;">
            <label for="subdatasheet-image" style="font-weight: bold; font-size: 14px;">Datasheet <span style="color: red;">*</span></label>
        </div>
        <div style="text-align: center; margin-top: 10px; margin-left: 40px; display: flex; flex-direction: column; align-items: center;">
            <div style="position: relative; border: 1px solid #ccc; border-radius: 4px; width: 100px; height: 80px; overflow: hidden; display: flex; justify-content: center; align-items: center;">
                <img id="subdatasheet-image-preview" src="" alt="Datasheet Image" 
                    style="width: 100%; height: 100%; object-fit: contain;" />
            </div>
            <button id="subdatasheet-image-btn" type="button" class="button button-secondary" style="margin-top: 10px;">Upload Image</button>
        </div>

        <div style="margin-left: 20px; display: flex; flex-direction: column;">
            <label style="font-size: 14px; margin-bottom: 5px; font-weight:bold;">Width:</label>
            <div style="display: flex; align-items: center;">
                <input type="number" id="subadddatasheet-width" name="subadddatasheet-width" value="25"  min="1" max="1000" style="width: 60px; font-size: 14px;" />
                <span style="margin-left: 5px;">px</span>
            </div>
            <label style="font-size: 14px; margin-bottom: 5px; margin-top: 10px; font-weight:bold;">Height:</label>
            <div style="display: flex; align-items: center;">
                <input type="number" id="subadddatasheet-height" name="subadddatasheet-height" value="25"  min="1" max="1000" style="width: 60px; font-size: 14px;" />
                <span style="margin-left: 5px;">px</span>
            </div>
        </div>
    </div>

    <!-- Row for Product Page Image -->
    <div style="display: flex; align-items: center;">
        <div style="margin-right: 20px;">
            <label for="subproductpage-image" style="font-weight: bold; font-size: 14px;">Product Page <span style="color: red;">*</span></label>
        </div>
        <div style="text-align: center;  margin: 20px; display:flex; flex-direction: column; align-items: center;">
            <div style="position: relative; border: 1px solid #ccc; border-radius: 4px; width: 100px; height: 80px; overflow: hidden; display: flex; justify-content: center; align-items: center;">
                <img id="subproductpage-image-preview" src="" alt="Product Page Image" 
                    style="width: 100%; height: 100%; object-fit: contain;" />
            </div>
            <button id="subproductpage-image-btn" type="button" class="button button-secondary" style="margin-top: 10px;">Upload Image</button>
        </div>

        <div style="margin-left: 0px; display: flex; flex-direction: column;">
            <label style="font-size: 14px; margin-bottom: 5px; font-weight:bold;">Width:</label>
            <div style="display: flex; align-items: center;">
                <input type="number" id="subaddproductpage-width" name="subaddproductpage-width" value="30"  min="1" max="1000" style="width: 60px; font-size: 14px;" />
                <span style="margin-left: 5px;">px</span>
            </div>
            <label style="font-size: 14px; margin-bottom: 5px; margin-top: 10px; font-weight:bold;">Height:</label>
            <div style="display: flex; align-items: center;">
                <input type="number" id="subaddproductpage-height" name="subaddproductpage-height" value="50"  min="1" max="1000" style="width: 60px; font-size: 14px;" />
                <span style="margin-left: 5px;">px</span>
            </div>
        </div>
    </div>

    <div class="wpml-dialog-footer" style="display: flex; justify-content: center; ">
        <button type="button" id="save-subattribute" class="button button-primary">Save</button>
    </div>
</div>

<div id="custom-uam-subdiagram-edit-dlg" style="display: none; padding: 20px;">
    <input type="hidden" id="subeditattribute-name" name="subeditattribute-name" value="">
    <input type="hidden" id="subeditattribute-id" name="attribute-id" value="">

    <!-- Row for Datasheet Image -->
    <div style="display: flex; align-items: center;">
        <!-- Label -->
        <div style="margin-right: 20px;">
            <label for="subeditdatasheet-image" style="font-weight: bold; font-size: 14px;">Datasheet</label>
        </div>
        <!-- Image and Upload Button -->
        <div style="text-align: center; margin: 20px 40px; display: flex; flex-direction: column; align-items: center;">
            <div style="position: relative; border: 1px solid #ccc; border-radius: 4px; width: 100px; height: 80px; overflow: hidden; display: flex; justify-content: center; align-items: center;">
                <img id="subeditdatasheet-image-preview" src="" alt="Datasheet Image" 
                    style="width: 70%; height: 70%; object-fit: contain; cursor: pointer;" />
            </div>
            <button id="subeditedit-datasheet-image-btn" type="button" class="button button-secondary" style="margin-top: 10px;">Upload Image</button>
        </div>

        <div style="margin-left: -20px; display: flex; flex-direction: column;">
            <label style="font-size: 14px; margin-bottom: 5px; font-weight:bold;">Width:</label>
            <div style="display: flex; align-items: center;">
                <input type="number" id="subeditdatasheet-width" name="subeditdatasheet-width"  min="1" max="1000" style="width: 60px; font-size: 14px;" />
                <span style="margin-left: 5px;">px</span>
            </div>
            <label style="font-size: 14px; margin-bottom: 5px; margin-top: 10px; font-weight:bold;">Height:</label>
            <div style="display: flex; align-items: center;">
                <input type="number" id="subeditdatasheet-height" name="subeditdatasheet-height"  min="1" max="1000" style="width: 60px; font-size: 14px;" />
                <span style="margin-left: 5px;">px</span>
            </div>
        </div>
    </div>

    <!-- Row for Product Page Image -->
    <div style="display: flex; align-items: center;">
        <!-- Label -->
        <div style="margin-right: 20px;">
            <label for="subeditproductpage-image" style="font-weight: bold; font-size: 14px;">Product Page</label>
        </div>
        <!-- Image and Upload Button -->
        <div style="text-align: center; margin: 20px; display: flex; flex-direction: column; align-items: center;">
            <div style="position: relative; border: 1px solid #ccc; border-radius: 4px; width: 100px; height: 80px; overflow: hidden; display: flex; justify-content: center; align-items: center;">
                <img id="subeditproductpage-image-preview" src="" alt="Product Page Image" 
                    style="width: 70%; height: 70%; object-fit: contain; cursor: pointer;" />
            </div>
            <button id="subeditedit-productpage-image-btn" type="button" class="button button-secondary" style="margin-top: 10px;">Upload Image</button>
        </div>

        <div style="margin-left: 0px; display: flex; flex-direction: column;">
            <label style="font-size: 14px; margin-bottom: 5px; font-weight:bold;">Width:</label>
            <div style="display: flex; align-items: center;">
                <input type="number" id="subeditproductpage-width" name="subeditproductpage-width"  min="1" max="1000" style="width: 60px; font-size: 14px;" />
                <span style="margin-left: 5px;">px</span>
            </div>
            <label style="font-size: 14px; margin-bottom: 5px; margin-top: 10px; font-weight:bold;">Height:</label>
            <div style="display: flex; align-items: center;">
                <input type="number" id="subeditproductpage-height" name="subeditproductpage-height"  min="1" max="1000" style="width: 60px; font-size: 14px;" />
                <span style="margin-left: 5px;">px</span>
            </div>
        </div>
        
    </div>

    <div class="wpml-dialog-footer" style="display: flex; justify-content: center; ">
        <button type="button" id="save-subeditattribute" class="button button-primary">Save</button>
    </div>
</div>

<div class="wrap">
    <div style="width: 100%; float: left;">
        <p style="margin: 0px; width: 50%; color: #000000; font-weight: bold; font-size: 26px; float: left;">
            <?php esc_html_e('Products Heading', 'custom-uam'); ?>
        </p>
    </div>
    <div style="width: 95%; padding-top: 0px; float: left;">
        <div style="width: 40%; float: left; position: relative;">
            <?php
            global $wpdb;
            $option_query = "SELECT Distinct heading FROM taw_attribute_heading";
            $option_results = $wpdb->get_results($option_query, ARRAY_A);
            ?>
            <div id="segroles-content">
                <?php
                echo '<ul class="custom_segrestrict_ls" style="margin-top: 40px;">';
                foreach ($option_results as $resrole_slug) {
                    echo '<li class="custom-uam-segrestrictproduct-li" data-role="' . $resrole_slug['heading'] . '">';
                    echo '<span id="clickedrole" class="custom-uam-segrestrictproduct-li-title" style="margin-right: auto;">' . $resrole_slug['heading'] . '</span>';
                    echo '</li>';
                }
                echo '</ul>';
                ?>
            </div>
        </div>
        <div style="width: 58%; float: left; margin-left: 5px;" id="right-segroles-content">
            <h3 style="float: left; text-align: left; color: #000000; font-weight: bold; font-size: 20px; margin-left: 10px; margin-top: 5px;">
                <?php echo __("Attributes", 'custom-uam'); ?>
            </h3>
            <div style="display: flex; justify-content: flex-end; align-items: center; margin-right: 5px; border-radius: 8px;">
                <a href="#TB_inline?&width=400&height=425&inlineId=custom-uam-alert-addattributevalues-dlg" title="<?php esc_html_e('Add Attribute', 'custom-uam'); ?>" class="thickbox addattributed button button-primary" style="border-radius: 8px;">+ <?php esc_html_e('Add', 'custom-uam'); ?></a>
            </div>
            <div style="display: flex; justify-content: flex-start; align-items: center; margin-top: 20px;">
                <input type="text" id="segroleproduct-search" placeholder="Search Attribute" style="width: 100%; max-width: 250px; background-color: white; border-radius: 4px; border: 1px solid #ccc;">
            </div>
            <div style="background: #e6e5e5; border-radius: 4px; min-height: 80vh; max-height: auto;">
                <ul id="c_uam_segcaprole_ul" style="display: block;"></ul>
                <div id="loaderhid">
                    <div class="aloader"></div>
                </div>
                <div id="segno-results" style="display: none; background-color: rgb(255, 255, 255); padding: 10px; margin: 10px 15px; border-radius: 4px;">
                    <span style="color: red; font-weight: medium;">No results found</span>
                </div>
            </div>
            <div style="text-align: center;">
                <button class="button button-primary" style="display: none; margin: 5px auto;" id="custom_uam_cap_save_btn">Save</button>
            </div>
        </div>
    </div>
</div>

<!-- <div class="wrap">
    <div class="width:100%;float:left;">
        <p style="margin:0px; width: 50%;color: #000000; font-weight: bold; font-size: 26px;float: left;"><?php //esc_html_e('Products Heading', 'custom-uam'); ?> </p>
    </div>
    <div style="width: 95%; padding-top: 30px;float: left;">
        <div style="width: 40%; float: left; position: relative;"> -->
            <?php
            // global $wpdb;
            // // Check if the role exists in the database
            // $option_query = "SELECT Distinct heading FROM taw_attribute_heading";
            // $option_results = $wpdb->get_results($option_query, ARRAY_A);
            ?>
            <!-- <nav aria-label="<?php //esc_attr_e('Secondary menu'); ?>" style="display: flex; align-items: center; justify-content: space-between; gap: 0;">
                <div style="display: flex; gap: 0;">
                    <a href="#" id="segroles-tab" class="seg-tab seg-tab-active" aria-current="page" style="border-radius: 6px 0 0 6px; font-size: 14px; font-weight: bold;"><?php //esc_html_e('Headings'); ?></a>
                </div>
                <input type="text" id="user-search" placeholder="Search User" style="display: none; width: 50%; background-color: white; border-radius: 8px; border: 1px solid #ccc;">
            </nav> -->

            <!-- <div id="segroles-content">
                <?php
                // echo '<ul class="custom_segrestrict_ls" style="margin-top:18px;">';
                // // Flag to check the first item
                // $is_first = true;
                // foreach ($option_results as $resrole_slug) {
                //         // Add active class to the first item
                //         $active_class = $is_first ? 'active' : '';
                //         echo '<li class="custom-uam-segrestrictproduct-li ' . $active_class . '" data-role="' . $resrole_slug['heading'] . '">';
                //         echo '<span id="clickedrole" class="custom-uam-segrestrictproduct-li-title" style="margin-right: auto;">' . $resrole_slug['heading'] . '</span>';
                //         echo '</li>';
                //         // Set the flag to false after the first iteration
                //         $is_first = false;
                // }
                // echo '</ul>';
                ?>
            </div> 
        </div>
        
        <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>

        <div style="width: 58%; float: left; margin-left: 5px;" id="right-segroles-content">
            <h3 style="float:left; text-align: left; color: #000000; font-weight: bold; font-size: 20px; margin-left: 10px; margin-top: 5px;">
                <?php //echo __("Attributes", 'custom-uam'); ?>
            </h3>
            <div style="display: flex; justify-content: flex-end; align-items: center; margin-right: 5px; border-radius: 8px;">
                <a href="#TB_inline?&width=400&height=400&inlineId=custom-uam-alert-addattributevalues-dlg" title="<?php //esc_html_e('Add Attribute', 'custom-uam'); ?>" class="thickbox addattributed button button-primary" style="border-radius: 8px;">+ <?php esc_html_e('Add', 'custom-uam'); ?></a>
            </div>
            <div style="display: flex; justify-content: flex-start; align-items: center; margin-top: 20px;">
                 <input type="text" id="segroleproduct-search" placeholder="Search Attribute" style="width: 100%; max-width: 250px; background-color: white; border-radius: 4px; border: 1px solid #ccc;">
            </div>            
            <div style="background: #e6e5e5; border-radius: 4px;min-height: 80vh; max-height: auto;">
                <ul id="c_uam_segcaprole_ul" style="display: block;">

                </ul>
                <div id="loaderhid">
                <div class="aloader"></div>
                </div>
                <div id="segno-results" style="display: none; background-color: rgb(255, 255, 255); padding: 10px; margin: 10px 15px; border-radius: 4px;">
                    <span style="color: red; font-weight: medium;">No results found</span>
                </div>
            </div>
            <div style="text-align: center;">
                <button class="button button-primary" style="display: none; margin: 5px auto;" id="custom_uam_cap_save_btn">Save</button>
            </div>
        </div>
    </div>
</div>-->
<?php
}

add_action('wp_ajax_save_addedheadingattribute', 'save_addedheadingattribute_callback');
add_action('wp_ajax_nopriv_save_addedheadingattribute', 'save_addedheadingattribute_callback');

function save_addedheadingattribute_callback() {
    global $wpdb;

    $selectedAttribute = isset($_POST['selectedAttribute']) ? sanitize_text_field($_POST['selectedAttribute']) : '';
    $Attributetranslation = isset($_POST['Attributetranslation']) ? sanitize_text_field($_POST['Attributetranslation']) : '';
    $adddatasheetImage = isset($_POST['adddatasheetImage']) ? esc_url_raw($_POST['adddatasheetImage']) : '';
    $addproductPageImage = isset($_POST['addproductPageImage']) ? esc_url_raw($_POST['addproductPageImage']) : '';
    $datasheetWidth = isset($_POST['datasheetWidth']) ? intval($_POST['datasheetWidth']) : '25';
    $datasheetheight = isset($_POST['datasheetheight']) ? intval($_POST['datasheetheight']) : '25';
    $productPageWidth = isset($_POST['productPageWidth']) ? intval($_POST['productPageWidth']) : '30';
    $productPageheight = isset($_POST['productPageheight']) ? intval($_POST['productPageheight']) : '50';
    $activeTab = isset($_POST['activeTab']) ? sanitize_text_field($_POST['activeTab']) : '';

    if (empty($selectedAttribute)) {
        wp_send_json_error(['message' => 'Attribute is required.']);
    }
    if (empty($Attributetranslation)) {
        wp_send_json_error(['message' => 'Attribute Translation is required.']);
    }
    if (empty($activeTab)) {
        wp_send_json_error(['message' => 'Active tab is required.']);
    }

    // Check if the attribute already exists in the database under any heading
    $existing_heading = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT heading FROM taw_attribute_heading WHERE attribute = %s",
            $selectedAttribute
        )
    );

    if ($existing_heading) {
        wp_send_json_error([
            'message' => sprintf(
                "Attribute '%s' is already assigned to the heading. To add it, please remove it from the '%s' heading.",
                $selectedAttribute,
                $existing_heading
            )
        ]);
    }

    // Insert the new attribute into the `taw_attribute_heading` table
    $insert_addheading = $wpdb->prepare(
        "INSERT INTO taw_attribute_heading (heading, attribute, attribute_translation, attr_imgurl, product_imgurl, datasheet_width, datasheet_height, product_width, product_height) 
         VALUES (%s, %s, %s, %s, %s, %d, %d, %d, %d)",
        $activeTab, $selectedAttribute, $Attributetranslation, $adddatasheetImage, $addproductPageImage, $datasheetWidth, $datasheetheight, $productPageWidth, $productPageheight
    );

    $result = $wpdb->query($insert_addheading);

    if ($result === false) {
        wp_send_json_error([
            'message' => 'Failed to save attribute.',
            'error' => $wpdb->last_error
        ]);
    }

    // Get the ID of the newly inserted attribute
    $newHeadingId = $wpdb->insert_id;

    // Fetch all distinct `cate_no` from `taw_attribute_segment`
    $categories = $wpdb->get_col("SELECT DISTINCT cate_no FROM taw_attribute_segment");

    if (!empty($categories)) {
    // Insert the new heading ID into `taw_attribute_segment` for each category
        foreach ($categories as $cate_no) {
            $insert_segment = $wpdb->prepare(
                "INSERT INTO taw_attribute_segment (cate_no, attribute_id) VALUES (%s, %d)",
                $cate_no, $newHeadingId
            );

            $segmentResult = $wpdb->query($insert_segment);

            if ($segmentResult === false) {
                wp_send_json_error([
                    'message' => 'Failed to insert into attribute segment.',
                    'error' => $wpdb->last_error
                ]);
            }
        }
    }
    
    $art_nos = $wpdb->get_col("SELECT DISTINCT art_no FROM taw_attribute_product_segment");

    if (!empty($art_nos)) {
        foreach ($art_nos as $art_no) {
            $insert_segment = $wpdb->prepare(
                "INSERT INTO taw_attribute_product_segment (art_no, attribute_id) VALUES (%s, %d)",
                $art_no, $newHeadingId
            );

            $segmentResult = $wpdb->query($insert_segment);

            if ($segmentResult === false) {
                wp_send_json_error([
                    'message' => 'Failed to insert into attribute segment.',
                    'error' => $wpdb->last_error
                ]);
            }
        }
    }

    wp_send_json_success(['message' => 'Attribute and segment data saved successfully!']);
    wp_die();
}

function get_subattribute_options_callback() {
    global $wpdb;

    $parentattributeName = isset($_POST['parentattributeName']) ? sanitize_text_field($_POST['parentattributeName']) : '';

    if (empty($parentattributeName)) {
        wp_send_json_error(['message' => 'Parent attribute name is required.']);
    }

    $query = $wpdb->prepare(
        "SELECT tsm_terms.name AS attrvalue, sv_terms.name AS translation_attrvalue
            FROM tsm_terms 
            JOIN tsm_term_taxonomy ON tsm_term_taxonomy.term_id = tsm_terms.term_id
            JOIN tsm_woocommerce_attribute_taxonomies 
            ON CONCAT('pa_', tsm_woocommerce_attribute_taxonomies.attribute_name) = tsm_term_taxonomy.taxonomy
            JOIN tsm_icl_translations ON tsm_terms.term_id = tsm_icl_translations.element_id
            LEFT JOIN tsm_icl_translations AS sv_translation ON tsm_icl_translations.trid = sv_translation.trid
            AND sv_translation.language_code = 'sv' 
            LEFT JOIN tsm_terms AS sv_terms ON sv_translation.element_id = sv_terms.term_id
            WHERE tsm_icl_translations.language_code = 'en' 
            AND tsm_icl_translations.element_type LIKE 'tax_pa_%'
            AND tsm_woocommerce_attribute_taxonomies.attribute_label = %s;",
        $parentattributeName
    );

    $results = $wpdb->get_results($query, ARRAY_A);

    if (!empty($results)) {
        wp_send_json_success($results);
    } else {
        wp_send_json_error(['message' => 'No attributes found.']);
    }

    wp_die();
}
add_action('wp_ajax_get_subattribute_options', 'get_subattribute_options_callback');

add_action('wp_ajax_save_subdiagramattribute', 'save_subdiagramattribute_callback');
add_action('wp_ajax_nopriv_save_subdiagramattribute', 'save_subdiagramattribute_callback');
function save_subdiagramattribute_callback() {
    global $wpdb;

    $subattributeName = isset($_POST['subattributeName']) ? sanitize_text_field($_POST['subattributeName']) : '';
    $translationAttrValue = isset($_POST['translationAttrValue']) ? sanitize_text_field($_POST['translationAttrValue']) : '';
    $subdatasheetImage = isset($_POST['subdatasheetImage']) ? esc_url_raw($_POST['subdatasheetImage']) : '';
    $subproductPageImage = isset($_POST['subproductPageImage']) ? esc_url_raw($_POST['subproductPageImage']) : '';
    $parentattributeName = isset($_POST['parentattributeName']) ? sanitize_text_field($_POST['parentattributeName']) : '';
    $parentattributeId = isset($_POST['parentattributeId']) ? intval($_POST['parentattributeId']) : 0;
    $datasheetWidth = isset($_POST['datasheetWidth']) ? intval($_POST['datasheetWidth']) : 25;
    $datasheetheight = isset($_POST['datasheetheight']) ? intval($_POST['datasheetheight']) : 25;
    $productPageWidth = isset($_POST['productPageWidth']) ? intval($_POST['productPageWidth']) : 30;
    $productPageheight = isset($_POST['productPageheight']) ? intval($_POST['productPageheight']) : 50;


    // Validate the required fields
    if (empty($subattributeName) || empty($parentattributeId)) {
        wp_send_json_error([
            'message' => 'Subattribute name and parent attribute ID are required.',
        ]);
    }

    // Check if the same attribute_id and attr_value already exist
    $existing_entry = $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(*) 
         FROM taw_attribute_subheading 
         WHERE attribute_id = %d AND attr_value = %s",
        $parentattributeId, $subattributeName
    ));

    if ($existing_entry > 0) {   
        wp_send_json_error([
            'message' => sprintf(
                "'%s' attribute value is already exists.",
                $subattributeName
            )
        ]);
    }

    // Prepare the INSERT query
    $insert_subheading = $wpdb->prepare(
        "INSERT INTO taw_attribute_subheading (attribute_id, datasheet_imgurl, product_imgurl, attr_value, attr_value_translation, 
        product_width, product_height, datasheet_width, datasheet_height) 
         VALUES (%d, %s, %s, %s, %s, %d, %d, %d, %d)",
        $parentattributeId, $subdatasheetImage, $subproductPageImage, $subattributeName, $translationAttrValue,
        $productPageWidth, $productPageheight, $datasheetWidth, $datasheetheight
    );

    // Log the query (for debugging)
    error_log('SQL Query: ' . $insert_subheading);

    // Execute the INSERT query
    $result = $wpdb->query($insert_subheading);

    if ($result) {
        wp_send_json_success(['message' => 'Subattribute saved successfully!']);
    } else {
        wp_send_json_error([
            'message' => 'Failed to save subattribute.', 
            'query' => $wpdb->last_query
        ]);
    }

    wp_die();
}


add_action('wp_ajax_fetch_subattributes', 'fetch_subattributes_callback');
add_action('wp_ajax_nopriv_fetch_subattributes', 'fetch_subattributes_callback');
function fetch_subattributes_callback() {
    global $wpdb;

    $parentattributeId = isset($_POST['parentattributeId']) ? intval($_POST['parentattributeId']) : 0;

    if (empty($parentattributeId)) {
        wp_send_json_error(['message' => 'Parent attribute ID is required.']);
    }

    $results = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT id, attr_value, datasheet_imgurl, product_imgurl, product_width, product_height, datasheet_width, datasheet_height
             FROM taw_attribute_subheading 
             WHERE attribute_id = %d",
            $parentattributeId
        ),
        ARRAY_A // Ensure results are returned as an associative array
    );

    error_log(print_r($results, true)); // Logs to wp-content/debug.log for debugging

    if ($results) {
        wp_send_json_success(['data' => $results]); // Send correct data structure
    } else {
        wp_send_json_error(['message' => 'No subattributes found.']);
    }

    wp_die();
}


add_action('wp_ajax_delete_subattribute', 'delete_subattribute_callback');
add_action('wp_ajax_nopriv_delete_subattribute', 'delete_subattribute_callback');

function delete_subattribute_callback() {
    global $wpdb;

    // Get the subattribute ID from the request
    $subattrid = isset($_POST['subattrid']) ? intval($_POST['subattrid']) : 0;

   
    $query_deletesubattr="DELETE FROM `taw_attribute_subheading` WHERE `id` = '$subattrid';";
    $deleted=$wpdb->query($query_deletesubattr);
    
    if ($deleted) {
        wp_send_json_success(['message' => 'Subattribute deleted successfully.']);
    } else {
        wp_send_json_error(['message' => 'Failed to delete subattribute.']);
    }

    wp_die();
}

add_action('wp_ajax_delete_attributerow', 'delete_attributerow_callback');
add_action('wp_ajax_nopriv_delete_attributerow', 'delete_attributerow_callback');

function delete_attributerow_callback() {
    global $wpdb;

    // Retrieve the attribute ID from the POST request
    $attrid = isset($_POST['attrid']) ? intval($_POST['attrid']) : 0;

    if (empty($attrid)) {
        wp_send_json_error(['message' => 'Attribute ID is required.']);
    }

    // Start a transaction to ensure all queries execute successfully
    $wpdb->query('START TRANSACTION');

    try {

        $heading="DELETE FROM taw_attribute_heading WHERE id = '$attrid';";
        $deleted_heading=$wpdb->query($heading);

        $subheading="DELETE FROM taw_attribute_subheading WHERE attribute_id = '$attrid';";
        $deleted_subheading=$wpdb->query($subheading);

        $product_segment="DELETE FROM taw_attribute_product_segment WHERE attribute_id = '$attrid';";
        $deleted_product_segment=$wpdb->query($product_segment);

        $segment="DELETE FROM taw_attribute_segment WHERE attribute_id = '$attrid';";
        $deleted_segment=$wpdb->query($segment);

        // Check if all deletions were successful
        if ($deleted_heading === false || $deleted_subheading === false || $deleted_product_segment === false || $deleted_segment === false) {
            throw new Exception('Failed to delete some or all related rows.');
        }

        // Commit the transaction
        $wpdb->query('COMMIT');

        wp_send_json_success(['message' => 'Attribute and related data deleted successfully.']);
    } catch (Exception $e) {
        // Rollback the transaction in case of an error
        $wpdb->query('ROLLBACK');
        wp_send_json_error(['message' => $e->getMessage()]);
    }

    wp_die();
}


add_action('wp_ajax_save_subediting_attributes', 'save_subediting_attributes_callback');
add_action('wp_ajax_nopriv_save_subediting_attributes', 'save_subediting_attributes_callback');

function save_subediting_attributes_callback() {
    global $wpdb;
    $subeditattributeName = isset($_POST['subeditattributeName']) ? $_POST['subeditattributeName'] : [];
    $subeditdatasheetImage = isset($_POST['subeditdatasheetImage']) ? $_POST['subeditdatasheetImage'] : [];
    $subeditproductPageImage = isset($_POST['subeditproductPageImage']) ? $_POST['subeditproductPageImage'] : [];
    $datasheetWidth = isset($_POST['datasheetWidth']) ? intval($_POST['datasheetWidth']) : 25;
    $datasheetHeight = isset($_POST['datasheetHeight']) ? intval($_POST['datasheetHeight']) : 25;
    $productPageWidth = isset($_POST['productPageWidth']) ? intval($_POST['productPageWidth']) : 30;
    $productPageHeight = isset($_POST['productPageHeight']) ? intval($_POST['productPageHeight']) : 50;


    if (!empty($subeditattributeName)) {
        $update = $wpdb->update(
            'taw_attribute_subheading', // Table name
            [
                'datasheet_imgurl' => $subeditdatasheetImage,
                'product_imgurl' => $subeditproductPageImage,
                'datasheet_width' => $datasheetWidth,
                'datasheet_height' => $datasheetHeight,
                'product_width' => $productPageWidth,
                'product_height' => $productPageHeight
            ],
            [
                'attr_value' => $subeditattributeName // Where clause
            ],
            [
                '%s', // Data types for the updated values
                '%s',
                '%d',
                '%d',
                '%d',
                '%d'
            ],
            [
                '%s' // Data type for the where clause
            ]
        );

        if ($update) {
            wp_send_json_success($update);
        } else {
            wp_send_json_success([]);
        }
    } else {
        wp_send_json_error('Invalid heading');
    }
    wp_die();
}
add_action('wp_ajax_fetch_attributes', 'fetch_attributes_callback');
add_action('wp_ajax_nopriv_fetch_attributes', 'fetch_attributes_callback');

function fetch_attributes_callback() {
    global $wpdb;

    $query = "SELECT attribute_label, attribute_name FROM tsm_woocommerce_attribute_taxonomies";
    $results = $wpdb->get_results($query, ARRAY_A);

    if (!empty($results)) {
        wp_send_json_success($results);
    } else {
        wp_send_json_error(['message' => 'No attributes found.']);
    }

    wp_die();
}

add_action('wp_ajax_save_edit_attributes', 'save_edit_attributes_callback');
add_action('wp_ajax_nopriv_save_edit_attributes', 'save_edit_attributes_callback');

function save_edit_attributes_callback() {
    global $wpdb;

    $attributeName = $_POST['attributeName'] ?? '';
    $attributetranslation = $_POST['attributetranslation'] ?? '';
    $datasheetImage = $_POST['datasheetImage'] ?? '';
    $productPageImage = $_POST['productPageImage'] ?? '';
    $datasheetWidth = intval($_POST['datasheetWidth'] ?? 25);
    $datasheetHeight = intval($_POST['datasheetHeight'] ?? 25);
    $productPageWidth = intval($_POST['productPageWidth'] ?? 30);
    $productPageHeight = intval($_POST['productPageHeight'] ?? 50);

    if (empty($attributetranslation) || empty($attributeName)) {
        wp_send_json_error(['message' => 'Attribute Swedish Translation is missing.']);
    }

    $existingTranslationEntry = $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(*) FROM taw_attribute_heading 
         WHERE attribute_translation = %s AND attribute != %s",
        $attributetranslation, $attributeName
    ));

    if ($existingTranslationEntry > 0) {
        wp_send_json_error(['message' => "Translation '$attributetranslation' already exists."]);
    }

    $result = $wpdb->update(
        'taw_attribute_heading',
        [
            'attribute_translation' => $attributetranslation,
            'attr_imgurl' => $datasheetImage,
            'product_imgurl' => $productPageImage,
            'datasheet_width' => $datasheetWidth,
            'datasheet_height' => $datasheetHeight,
            'product_width' => $productPageWidth,
            'product_height' => $productPageHeight
        ],
        ['attribute' => $attributeName],
        ['%s', '%s', '%s', '%d', '%d', '%d', '%d'],
        ['%s']
    );

    if ($result === false) {
        wp_send_json_error(['message' => 'SQL Error: ' . $wpdb->last_error]);
    } elseif ($result === 0) {
        wp_send_json_error(['message' => 'No changes were made.']);
    } else {
        wp_send_json_success(['message' => 'Attribute updated successfully.']);
    }
    wp_die();
}

    add_action('wp_ajax_fetch_attribute_values', 'fetch_attribute_values_callback');
    add_action('wp_ajax_nopriv_fetch_attribute_values', 'fetch_attribute_values_callback');

    function fetch_attribute_values_callback() {
        global $wpdb;

        $heading = isset($_POST['heading']) ? sanitize_text_field($_POST['heading']) : '';

        if (!empty($heading)) {
            $results = $wpdb->get_results(
                $wpdb->prepare("SELECT tah.id as id, tah.attribute as attribute, tah.attribute_translation as attribute_translation, tah.attr_imgurl as attr_imgurl, 
                tah.product_imgurl as product_imgurl, tah.product_width as product_width, tah.product_height as product_height, 
                tah.datasheet_width as datasheet_width, tah.datasheet_height as datasheet_height, COUNT(tas.attribute_id) AS attribute_count
                FROM taw_attribute_heading AS tah LEFT JOIN taw_attribute_subheading AS tas ON tah.id = tas.attribute_id 
                WHERE heading = %s GROUP BY tah.id, tah.attribute, tah.attr_imgurl, tah.product_imgurl, tah.product_width, tah.product_height, 
                tah.datasheet_width, tah.datasheet_height ORDER BY tah.id", $heading),
                ARRAY_A
            );

            if ($results) {
                wp_send_json_success($results);
            } else {
                wp_send_json_success([]);
            }
        } else {
            wp_send_json_error('Invalid heading');
        }
        wp_die();
    }

    function enqueueattr_jquery() {
        $addicon = ATTR_SEGMENT_DIR . 'img/add.png';
        $editicon = ATTR_SEGMENT_DIR . 'img/edit.png';
        $deleteicon = ATTR_SEGMENT_DIR . 'img/delete.png';
        $emptyicon = ATTR_SEGMENT_DIR . 'img/empty.png';
    
        wp_enqueue_script('jquery');
        wp_enqueue_script('sortable-js', 'https://cdn.jsdelivr.net/npm/sortablejs@1.14.0/Sortable.min.js', [], null, true);
        wp_enqueue_script('attrscript-js', ATTR_SEGMENT_DIR . '/js/attrscript.js', ['jquery'], null, true); // Correcting version
        wp_enqueue_style('attrstyle-css', ATTR_SEGMENT_DIR . '/css/attrstyle.css');
    
        // Localize the script and pass the add icon URL
        wp_localize_script('attrscript-js', 'customSegmentData', array(
            'addIconUrl' => esc_url($addicon), // Pass the icon URL
            'editIconUrl' => esc_url($editicon),
            'deleteIconUrl' => esc_url($deleteicon),
            'emptyIconUrl' => esc_url($emptyicon),
        ));
    }
    add_action('admin_enqueue_scripts', 'enqueueattr_jquery');
?>