<?php
/*
Plugin Name: Datasheet Settings
Description: A plugin to Datasheet products by category.
Version: 1.0
Author: Things at Web
*/

// // Enqueue styles and scripts here if needed.
// define('THINGSATWEB_BASE', plugin_dir_url(__FILE__));
// define('THINGSATWEB_DIR', __DIR__);
define('PRODUCTDATASHEET_DIR', plugin_dir_url(__FILE__));
define('PRODUCTDATASHEET_VERSION', "?v=1.0");

function datasheet_setting_menu()
{
    add_menu_page(
        'DATASHEET Setting',
        'Datasheet Setting',
        'manage_options',
        'datasheet-setting',
        'render_datasheetproduct_page'
    );
}
add_action('admin_menu', 'datasheet_setting_menu');

add_action('wp_ajax_datasheetchange_display_value', 'datasheetchange_display_value_callback');
add_action('wp_ajax_nopriv_datasheetchange_display_value', 'datasheetchange_display_value_callback');
add_action('wp_ajax_datasheetdeselect_display_value', 'datasheetdeselect_display_value_callback');
add_action('wp_ajax_nopriv_datasheetdeselect_display_value', 'datasheetdeselect_display_value_callback');
function datasheetdeselect_display_value_callback(){
    global $wpdb;
    $select_cat=($_POST['cate_no']);
    $attr=($_POST['attribute']);
    
  $result = $wpdb->update(
     'taw_filter_setting',
    array('datasheet' => 0
           ),
    array(
        'cate_no' => $select_cat,
        'attribute' => $attr
    )
);

    if ($result !== false) {
        echo 'Display value updated successfully';
    } else {

      $wpdb_error = $wpdb->last_error;
    error_log('Database error: ' . $wpdb_error);
    echo 'Error updating display value: ' . $wpdb_error;
    }

    wp_die();

}

function datasheetchange_display_value_callback(){
    global $wpdb;
    $select_cat=($_POST['cate_no']);
    $attr=($_POST['attribute']);
    echo "<script>console.log('select_cat'.$select_cat);</script>";
    echo "<script>console.log('attr'.$attr);</script>";

$result = $wpdb->update(
     'taw_filter_setting',
    array('datasheet' => 1
           ),
    array(
        'cate_no' => $select_cat,
        'attribute' => $attr
    )
);
    if ($result !== false) {
        echo 'Display value updated successfully';
    } else {

      $wpdb_error = $wpdb->last_error;
    error_log('Database error: ' . $wpdb_error);
    echo 'Error updating display value: ' . $wpdb_error;
    }
    wp_die();
}

function render_datasheetproduct_page()
{
    global $wpdb;
    $display_value = 0;
    $attribute_values = [];
    $att_val_ues = [];
    // Query to get distinct cate_no values from the taw_filter_setting table
    $cate_no_query = "SELECT DISTINCT cate_no FROM taw_filter_setting";
    $cate_no_results = $wpdb->get_results($cate_no_query, ARRAY_A);
    // Create a dropdown to select cate_no values
    echo '<div id="filtersdatasheet">';
    echo '<form method="post" >';
    echo '<label for="cate_no">Select category:</label>';
    echo '<select name="cate_no" id="cate_no">';
    foreach ($cate_no_results as $row) {

        echo '<option value="' . esc_attr($row['cate_no']) . '">' . esc_html($row['cate_no']) . '</option>';
    }
    echo '</select>';
    echo '<input type="submit" name="submit" value="Submit">';
    echo '</form>';

    // Check if a cate_no is selected
    if (isset($_POST['cate_no'])) {
        $selected_cate_no = sanitize_text_field($_POST['cate_no']);

        // Query to get distinct attribute values for the selected cate_no
        $attribute_query = $wpdb->prepare("SELECT DISTINCT(attribute),datasheet FROM taw_filter_setting WHERE cate_no = %s", $selected_cate_no);
        $attribute_results = $wpdb->get_results($attribute_query, ARRAY_A);

        // Loop through and display attribute values
        echo '<center>';
        echo '<h2>' . $selected_cate_no . '</h2>';
        echo '</center>';
        echo '<ul style="background-color: powderblue;">';
        foreach ($attribute_results as $attribute_row) {
            $current_attribute = esc_html($attribute_row['attribute']);
            echo "<script>console.log('current id'.$current_attribute)</script>";

            if (!in_array($current_attribute, $attribute_values)) {
                $attribute_values[] = $current_attribute; // Add $current_attribute to the array
                echo "<script>console.log('in array id'.$current_attribute)</script>";
            } else
                continue;

            $query = $wpdb->prepare(
                "SELECT datasheetfilt_enable FROM taw_filter_setting WHERE cate_no = %s AND attribute = %s LIMIT 1",
                $selected_cate_no,
                $current_attribute
            );
           
            $count = $wpdb->get_var($query);
            // $filt_attr = esc_html($attribute_row['filt_attr']);

            echo '<li style="padding: 10px; font-size: 18px;">';

            // Check if the corresponding 'display' column value is '1'
            if ($count > 0)
                $display_value = 1;
            else
                $display_value = 0;
            // Assuming 1 is the value that indicates 'checked'
            $is_checked = (get_displayproduct_value($selected_cate_no, $current_attribute, '') == $display_value) ? 'checked' : '';

            // Place a checkbox next to the attribute name
            // echo '<input type="checkbox" name="att_check" value="' . $current_attribute . '" ' . $is_checked . '>';
            echo '<input type="checkbox" name="' . $current_attribute . '" class="att_check" value="' . $display_value . '" ' . ($display_value == 1 ? 'checked' : '') . '>';

            echo '<span style="margin-left: 5px;font-size: 20px; ">' . $current_attribute . '</span>';

            echo '</li>';
            echo '<input type="submit" name="'.$current_attribute.'" value="Select All" class="datasheetselect">';
            echo '<input type="submit" name="'.$current_attribute.'" value="Deselect All" class="datasheetdeselect">';
            // Query to get distinct att_value values for the current attribute
            $att_value_query = $wpdb->prepare("SELECT DISTINCT id,att_value FROM taw_filter_setting WHERE cate_no = %s AND attribute = %s", $selected_cate_no, $current_attribute);
            $att_value_results = $wpdb->get_results($att_value_query, ARRAY_A);

            // Display checkboxes for att_value values in a table with 6 columns and style each row
            echo '<table style="border-collapse: separate; border-spacing: 10px;" border="1" ><tr >';
            $column_count = 0;
            foreach ($att_value_results as $att_value_row) {
                $current_att_value = esc_html($att_value_row['att_value']);
                if (!in_array($current_att_value, $att_val_ues)) {
                    $att_val_ues[] = $current_att_value; // Add $current_attribute to the array
                    //  echo "<script>console.log('in array id'.$current_attribute)</script>";
                } else
                    continue;

                $check_id = esc_html($att_value_row['id']);

                // Start a new row after every 6 columns
                if ($column_count % 8 === 0 && $column_count !== 0) {
                    echo '</tr><tr  >';
                }

                // Check if the corresponding 'display' column value is '1'
                $display_value = 1; // Assuming 1 is the value that indicates 'checked'
                $is_checked = (get_displayproduct_value($selected_cate_no, $current_attribute, $current_att_value) == $display_value) ? 'checked' : '';
?>
                <input type="hidden" id="hiddenAttribute" value="<?php echo esc_attr($current_attribute); ?>">
                <input type="hidden" id="hiddenSelectedCateNo" value="<?php echo esc_attr($selected_cate_no); ?>">
<?php
                // Apply styles to each cell (td) and add a checkbox
                echo '<td style="padding: 10px; font-size: 18px;" bgcolor="white">';

                if ($count > 0) {

                    echo '<input type="checkbox" class="' . $current_attribute . '" name="' . $check_id . '" value="' . $current_att_value . '" ' . $is_checked . ' >' . '<span style="margin-left: 5px;">' . $current_att_value . '</span>';
                } else {
                    echo '<input type="checkbox" class="' . $current_attribute . '" name="' . $check_id . '" value="' . $current_att_value . '" ' . $is_checked . ' disabled>' . '<span style="margin-left: 5px;">' . $current_att_value . '</span>';
                }
                echo '</td>';
                $column_count++;
            }
            $att_val_ues = [];
            echo '</tr></table>';
        }
        echo '</ul>';
    }
    echo '</div>';
}

function get_displayproduct_value($cate_no, $attribute, $att_value)
{
    global $wpdb;
    $query = $wpdb->prepare("SELECT datasheet FROM taw_filter_setting WHERE cate_no = %s AND attribute = %s AND att_value = %s and datasheet='1' ", $cate_no, $attribute, $att_value);
    return $wpdb->get_var($query);
}

add_action('wp_ajax_update_datasheet_value', 'update_datasheet_value_callback');
add_action('wp_ajax_nopriv_update_datasheet_value', 'update_datasheet_value_callback');
add_action('wp_ajax_block_displayproduct_value', 'block_displayproduct_value_callback');
add_action('wp_ajax_nopriv_block_displayproduct_value', 'block_displayproduct_value_callback');
function update_datasheet_value_callback() {
    global $wpdb;

    // Get attValue and isChecked from the AJAX request
    //$attValue = sanitize_text_field($_POST['attValue']);
    $attValue = ($_POST['attValue']);
    $isChecked = $_POST['isChecked'] === 'true' ? 1 : 0; // Convert to 1 (checked) or 0 (unchecked)
    $select_cat=($_POST['cate_no']);
    $attr=($_POST['attribute']);
    $check_id=($_POST['check']);
    $filt=($_POST['attr']);
    echo "<script>console.log('updated'.$isChecked);</script>";
    echo "<script>console.log('attValue'.$attValue);</script>";
    echo "<script>console.log('select_cat'.$select_cat);</script>";
    echo "<script>console.log('attr'.$attr);</script>";

$result = $wpdb->update(
     'taw_filter_setting',
    array(
           'datasheet' => $isChecked),
    array(
        'id' => $check_id
    )
);

    if ($result !== false) {
        echo 'Display value updated successfully';
    } else {
        // Log errors
       // error_log('Error updating display value');
      //  echo 'Error updating display value';
      $wpdb_error = $wpdb->last_error;
    error_log('Database error: ' . $wpdb_error);
    echo 'Error updating display value: ' . $wpdb_error;
    }

    // Always exit to avoid extra output
    wp_die();
}

function block_displayproduct_value_callback(){

    global $wpdb;
   
       $attValue = ($_POST['attValue']);
       $isChecked = $_POST['attr'] === 'true' ? 1 : 0; // Convert to 1 (checked) or 0 (unchecked)
       $select_cat=($_POST['cate_no']);
       $attr=($_POST['attribute']);
       $check_id=($_POST['check']);
       $filt=($_POST['attr']);
       echo "<script>console.log('updated'.$isChecked);</script>";
       echo "<script>console.log('attValue'.$attValue);</script>";
       echo "<script>console.log('select_cat'.$select_cat);</script>";
       echo "<script>console.log('attr'.$attr);</script>";
   
       $result = $wpdb->update(
           'taw_filter_setting',
          array('datasheetfilt_enable' => $isChecked
                 ),
          array(
           'cate_no' => $select_cat,
           'attribute' => $attr
           
          )
      );
      
          if ($result !== false) {
              echo 'Display value updated successfully';
          } else {
              
            $wpdb_error = $wpdb->last_error;
          error_log('Database error: ' . $wpdb_error);
          echo 'Error updating display value: ' . $wpdb_error;
          }
      
          // Always exit to avoid extra output
          wp_die();
   }
   function enqueuedatasheet_jquery() {
    wp_enqueue_script('datasheet-js', PRODUCTDATASHEET_DIR . '/js/datasheet.js' . PRODUCTDATASHEET_VERSION, ['jquery'], null, true);
    
}

 add_action('admin_enqueue_scripts', 'enqueuedatasheet_jquery');
?>