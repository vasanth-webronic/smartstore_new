<?php
/*
Plugin Name: TAW Filter Settings
Description: A plugin to filter products by category.
Version: 1.0
Author: Things at Web
*/

// Enqueue styles and scripts here if needed.
define('TAW_FILTER_DIR', plugin_dir_url(__FILE__));
define('TAW_FILTER_VERSION', "?v=1.187");
require_once plugin_dir_path(__FILE__) . 'includes/class-attribute-synchronization.php';

// 2. Initialize the synchronizer
$attribute_synchronizer = new Attribute_Synchronizer();

// 3. Add your submenu page
add_action('admin_menu', function() use ($attribute_synchronizer) {
    add_submenu_page(
        'custom-setting',                   // Parent menu slug
        'Missing Attributes Value',         // Submenu page title
        'Sync Missing Attributes Value',    // Submenu menu title
        'manage_options',                   // Capability required
        'missingvalue-slug',                // Submenu slug
        [$attribute_synchronizer, 'render_sync_page'] // Callback as class method
    );
});

// 4. Register activation hook for table creation
register_activation_hook(__FILE__, [$attribute_synchronizer, 'create_sync_logs_table']);


// 3. Register menus with correct timing
add_action('admin_menu', function() use ($attribute_synchronizer) {
    // Main menu must be created FIRST
    add_menu_page(
        'Custom Setting',
        'Custom Setting',
        'manage_options',
        'custom-setting',
        'render_category_product_page'
        // 'dashicons-screenoptions', 10 // Uncomment if needed
    );

    // Now add submenus
    add_submenu_page(
        'custom-setting',
        'Filter Setting',
        'Filter Setting',
        'manage_options',
        'filter-setting',
        'render_category_product_page'
    );

    add_submenu_page(
        'custom-setting',
        'Datasheet Setting',
        'Datasheet Setting',
        'manage_options',
        'datasheet-setting',
        'render_datasheetproduct_page'
    );

    add_submenu_page(
        'custom-setting',
        'Adding Category',
        'Add Category',
        'manage_options',
        'category-slug',
        'render_category_page'
    );

    add_submenu_page(
        'custom-setting',
        'Translate attributes',
        'Translate attributes',
        'manage_options',
        'translate-attributes',
        'untranslate_attribute_translations'
    ); // FIXED: Added closing );

    // Add synchronizer submenu AFTER parent exists
    add_submenu_page(
        'custom-setting',
        'Missing Attributes Value',
        'Sync Missing Attributes Value',
        'manage_options',
        'missingvalue-slug',
        [$attribute_synchronizer, 'render_sync_page']
    );
}, 10);
// Remove "Custom Setting" from the submenu
function remove_custom_setting_submenu() {
    global $submenu;
    unset($submenu['custom-setting'][0]); // Replace 'filter-setting' with the actual parent menu slug
}
add_action('admin_menu', 'remove_custom_setting_submenu');

function untranslate_attribute_translations() {
    global $wpdb;
    $currentlang='en';
    $englishflag = TAW_FILTER_DIR . '/img/en.png';
    $swedishflag = TAW_FILTER_DIR . '/img/sv.png';

    $langname='English';
    $flag=$englishflag;

    $translatelang='Swedish';
    $translateflag=$swedishflag;
    
   
    echo '<script>var langname = "' . $langname . '";</script>';
    echo '<script>var translatelang = "' . $translatelang . '";</script>';

   
    echo '<script>var imageflag = "' . $flag . '";</script>';
    echo '<script>var translateflag = "' . $translateflag . '";</script>';
    

        $cate_no_query =   "SELECT 
        tsm_icl_translations.language_code AS lang_code, 
        tsm_icl_translations.element_id AS term_id,  
        tsm_icl_translations.trid AS term_trid,  
        tsm_terms.name AS term_name, 
        tsm_terms.slug AS term_slug,
        tsm_term_taxonomy.description AS term_description,
        tsm_term_taxonomy.taxonomy AS term_taxonomy,
        tsm_attr_tax.attribute_label AS attribute_label
    FROM 
        tsm_icl_translations
    JOIN 
        tsm_terms ON tsm_icl_translations.element_id = tsm_terms.term_id
    JOIN
        tsm_term_taxonomy ON tsm_icl_translations.element_id = tsm_term_taxonomy.term_id
    JOIN
        tsm_woocommerce_attribute_taxonomies tsm_attr_tax ON REPLACE(tsm_term_taxonomy.taxonomy, 'pa_', '') = tsm_attr_tax.attribute_name
    WHERE 
        tsm_icl_translations.element_type LIKE 'tax_pa_%'
        AND tsm_icl_translations.trid IN (
            SELECT 
                trid
            FROM 
                tsm_icl_translations
            WHERE 
                element_type LIKE 'tax_pa_%'
            GROUP BY 
                trid
            HAVING 
                COUNT(DISTINCT CASE WHEN language_code IN ('en', 'sv') THEN language_code ELSE NULL END) = 1
        )
        AND tsm_icl_translations.language_code = '$currentlang'";

        // $cate_no_query = "SELECT DISTINCT cate_no FROM taw_filter_setting where lang='0'";
        $cate_no_results = $wpdb->get_results($cate_no_query, ARRAY_A);
        // Display term data
        echo '<h2 style="text-align:center;">Translate attributes</h2>'; 
        echo' <div class="table-container">';
            echo' <table id="customers">';
                echo '<tr>';
                    echo'<th>Attribute</th>';
                    echo'<th>Taxonomy</th>';
                    echo'<th>Terms</th>';
                    echo'<th> </th>';
                echo '</tr>';
                foreach ($cate_no_results as $term) {
                    if($term['term_taxonomy'] && $term['term_taxonomy'] == 'pa_articlenumber'){
                        continue;
                    }
                    echo '<tr>';
                    // echo '<td class="term-productid hidden ">' . $product_id . '</td>'; // Add class "term-slug" here
                    echo '<td class="term-attributelabel ">' . $term['attribute_label'] . '</td>'; // Add class "term-slug" here
                    echo '<td class="term-taxonomy ">' . $term['term_taxonomy'] . '</td>'; // Add class "term-slug" here
                    echo '<td class="term-name">' . esc_html($term['term_name']) . '</td>'; // Add class "term-name" here
                    echo '<td class="term-slug hidden">' . $term['term_slug'] . '</td>'; // Add class "term-slug" here
                    echo '<td class="term-description hidden">' . $term['term_description'] . '</td>'; // Add class "term-description" here
                    echo '<td class="term-trid hidden">' . $term['term_trid'] . '</td>'; // Add class "term-description" here
                    echo '<td class="term-langcode hidden">' . $term['lang_code'] . '</td>'; // Add class "term-description" here
                    echo '<td><button class="plus">+</button></td>';
                    echo '</tr>';
                    
                }
            echo '</table>';
        echo '</div>';

}

add_action('wp_ajax_save_term_data', 'save_term_data_callback');
function save_term_data_callback() {

    // Get the posted data
    $term_name = isset($_POST['termName']) ? sanitize_text_field($_POST['termName']) : '';
    $term_slug = isset($_POST['termSlug']) ? sanitize_text_field($_POST['termSlug']) : '';
    $term_description = isset($_POST['termDescription']) ? sanitize_textarea_field($_POST['termDescription']) : '';
    $term_taxonomy = isset($_POST['termtaxonomy']) ? sanitize_text_field($_POST['termtaxonomy']) : '';
    $term_trid = isset($_POST['termtrid']) ? sanitize_text_field($_POST['termtrid']) : '';
    $term_langcode = isset($_POST['termlangcode']) ? sanitize_text_field($_POST['termlangcode']) : '';
    $term_oldname = isset($_POST['termoldname']) ? sanitize_text_field($_POST['termoldname']) : '';

    global $wpdb;
    
    // Insert data into tsm_terms table
    $wpdb->insert(
        'tsm_terms',
        array(
            'name' => $term_name,
            'slug' => $term_slug,
            'term_group' => 0
        )
    );
    // Get the inserted term_id
    $term_id = $wpdb->insert_id;
   

    // Insert data into tsm_term_taxonomy table
    $wpdb->insert(
        'tsm_term_taxonomy',
        array(
            'term_taxonomy_id'=> $term_id,
            'term_id' => $term_id,
            'taxonomy' => $term_taxonomy,
            'description' => $term_description
        )
    );
    

    $product_idss = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT p.ID as post_id FROM tsm_posts AS p
             INNER JOIN tsm_term_relationships AS tr ON p.ID = tr.object_id
             INNER JOIN tsm_term_taxonomy AS tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
             INNER JOIN tsm_terms AS t ON tt.term_id = t.term_id
             WHERE t.name = %s
             AND tt.taxonomy = %s",
             $term_oldname,
             $term_taxonomy
        ),
        ARRAY_A // Specify the return format as an associative array
    );
    foreach ($product_idss as $post_id) {
        $post_id = $post_id['post_id']; 
        $swedish_id = apply_filters('wpml_object_id', $post_id, 'post', true, 'sv');
        $wpdb->insert(
            'tsm_term_relationships',
              array(
                    'object_id' => $swedish_id,
                    'term_taxonomy_id' => $term_id,
                    'term_order' => '0'
                    )
        );
    }
        // Add prefix "tax_" to the term_taxonomy value
    $element_type = 'tax_' . $term_taxonomy;

    $sourcelangcode='sv';

    $wpdb->insert(
        'tsm_icl_translations',
        array(
            'element_type'=> $element_type,
            'element_id' => $term_id,
            'trid' => $term_trid,
            'language_code' => $sourcelangcode,
            'source_language_code'=> $term_langcode
        )
    );
    
    // Always exit to avoid extra output
    wp_die();
}
function render_datasheetproduct_page()
{
    global $wpdb;
    $display_value = 0;
    $attribute_values = [];
    $att_val_ues = [];
$currentlang=getSiteCurrentLang();
    // Query to get distinct cate_no values from the taw_filter_setting table
    if( $currentlang==='en'){
        $cate_no_query = "SELECT DISTINCT cate_no FROM taw_filter_setting where lang='1'";
    $cate_no_results = $wpdb->get_results($cate_no_query, ARRAY_A);
}elseif( $currentlang==='sv'){
        $cate_no_query = "SELECT DISTINCT cate_no FROM taw_filter_setting where lang='0'";
        $cate_no_results = $wpdb->get_results($cate_no_query, ARRAY_A);
    }
    // Create a dropdown to select cate_no values
    echo '<div id="filtersdatasheet">';
    echo '<form method="post"  id="filtersdatasheetform">';
    echo '<label for="cate_no">Select category:</label>';
      echo "<div>";
    echo '<select name="cate_no" id="cate_no">';
    foreach ($cate_no_results as $row) {

        echo '<option value="' . htmlspecialchars($row['cate_no']) . '">' . htmlspecialchars($row['cate_no']) . '</option>';
    }
      
    echo '</select>';
    echo '<input type="submit" name="submit" value="Submit">';
    echo "</div>";
    echo '</form>';
        echo '<div id="loader" style="display: none;"><img src="' . esc_url(admin_url('images/spinner.gif')) . '" alt="Loading..."></div>';
        

echo '<div id="datasheet_results" class="d-none"></div>';
    echo '</div>';

    ?>
           <style>
        #filtersdatasheet {
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        #filtersdatasheetform {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }
        #filtersdatasheetform label {
            margin-bottom: 10px;
            font-size: 16px;
            font-weight: bold;
        }
        #filtersdatasheetform select, #filtersdatasheetform input[type="submit"] {
            
           
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        #filtersdatasheetform input[type="submit"] {
            background-color: #0073aa;
            color: white;
            padding: 5px 10px;
            cursor: pointer;
        }
        #filtersdatasheetform input[type="submit"]:hover {
            background-color: #005a87;
        }
        #loader {
            text-align: center;
            margin: 20px 0;
        }
        #datasheet_results {
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 8px;
            margin-top: 10px;
        }
        .attribute-list {
            list-style-type: none;
            padding: 0;
        }
        .attribute-list li {
            padding: 10px;
            
        }
        .attribute-list input[type="checkbox"] {
            margin-right: 10px;
        }
        .d-none{
            display: none;
        }
        .filter-setting-option-container{
            padding: 10px;
            border-radius: 20px;
        }
    </style>
<?php

   
}
add_action('wp_ajax_get_datasheet_option', 'get_datasheet_option');
add_action('wp_ajax_nopriv_get_datasheet_option', 'get_datasheet_option'); // For non-logged in users

function get_datasheet_option() {
    global $wpdb;

    $attribute_values = [];
    $att_values = [];


    // Check if a cate_no is selected
    if (isset($_POST['cate_no'])) {
        $selected_cate_no = sanitize_text_field($_POST['cate_no']);

        // Query to get distinct attribute values for the selected cate_no
        $attribute_query = $wpdb->prepare("SELECT DISTINCT(attribute), datasheet FROM taw_filter_setting WHERE cate_no = %s", $selected_cate_no);
        $attribute_results = $wpdb->get_results($attribute_query, ARRAY_A);

        // Start output buffering for AJAX response
        ob_start();

        echo '<center>';
        echo '<h2>' . $selected_cate_no . '</h2>';
        echo '</center>';
        echo '<ul style="background-color: powderblue;" class="filter-setting-option-container">';

        foreach ($attribute_results as $attribute_row) {
            $current_attribute = esc_html($attribute_row['attribute']);

            if (!in_array($current_attribute, $attribute_values)) {
                $attribute_values[] = $current_attribute;
            } else {
                continue;
            }

            $query = $wpdb->prepare("SELECT datasheetfilt_enable FROM taw_filter_setting WHERE cate_no = %s AND attribute = %s LIMIT 1", $selected_cate_no, $current_attribute);
            $count = $wpdb->get_var($query);
            // $filt_attr = esc_html($attribute_row['filt_attr']);

            echo '<li style="padding: 10px; font-size: 18px;">';
            $display_value = ($count > 0) ? 1 : 0;

            echo '<input type="checkbox" name="' . $current_attribute . '" class="att_check" value="'.$display_value.'" ' . ($count > 0 ? 'checked' : '') . '>';
            echo '<span style="margin-left: 5px; font-size: 20px;">' . $current_attribute . '</span>';
            echo '</li>';

            ?>
                            <input type="hidden" id="hiddenAttribute" value="<?php echo esc_attr($current_attribute); ?>">
                            <input type="hidden" id="hiddenSelectedCateNo" value="<?php echo htmlspecialchars($selected_cate_no); ?>">
            <?php

            // Query to get distinct att_value values for the current attribute
            $att_value_query = $wpdb->prepare("SELECT DISTINCT id, att_value FROM taw_filter_setting WHERE cate_no = %s AND attribute = %s", $selected_cate_no, $current_attribute);
            $att_value_results = $wpdb->get_results($att_value_query, ARRAY_A);
            echo '<input type="submit" style="padding: 5px 10px; border-color:#ccc; border-width:1px; border-radius:5px;"  name="'.$current_attribute.'" value="Select All" class="select">';
            echo '<input type="submit" style="padding: 5px 10px; border-color:#ccc; border-width:1px; border-radius:5px;" name="'.$current_attribute.'" value="Deselect All" class="deselect">';
            

            echo '<div style="display: flex; flex-wrap: wrap; margin-top: 5px;">';

            foreach ($att_value_results as $att_value_row) {
                $current_att_value = esc_html($att_value_row['att_value']);
                $check_id = esc_html($att_value_row['id']);
                $is_checked = (get_displayproduct_value($selected_cate_no, $current_attribute, $current_att_value) == 1) ? 'checked' : '';
            
                echo '<div style="width: 150px; border:1px solid #ccc; padding: 10px; font-size: 18px; border-radius: 5px; background-color: white; margin: 5px; box-sizing: border-box;">';
                echo '<input type="checkbox" data-attr="'. $current_att_value .'" class="' . $current_attribute . '" name="' . $check_id . '" value="' . $current_att_value . '" ' . $is_checked . '  '.($display_value == 0 ? 'disabled' : '') .'>';
                echo '<span style="margin-left: 5px;">' . $current_att_value . '</span>';
                echo '</div>';
            }
            
            echo '</div>';
            
        }

        echo '</ul>';

        // Output the HTML as AJAX response
        $html = ob_get_clean();
        echo $html;

        wp_die();
}
}

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
function render_category_page()
{
    global $wpdb;
    $lang = getSiteCurrentLang();
    $selectedLan = (strcasecmp($lang, 'en') === 0) ? '1' : '0';

    if (isset($_POST['add_cate'])) {
        echo "<script>console.log('in array id')</script>";
        // Fetch all distinct attributes
        $attval_query = "SELECT DISTINCT attr_id FROM taw_article_attributes";
        $attval_results = $wpdb->get_results($attval_query, ARRAY_A);

        // Fetch the existing filter settings
        $selectedCategoryId = $_POST['selectCategory']; // Assuming 'selectCategory' is the name attribute of the select box
        $selectedLanguage =  $selectedLan; 
     echo '<script>console.log("cate: ' . ($selectedCategoryId) . '")</script>';
      echo '<script>console.log("language: ' . ($selectedLanguage) . '")</script>';

        $que = "SELECT MIN(id) AS id, cate_no, attribute 
                FROM taw_filter_setting 
                WHERE cate_no = %s
                GROUP BY cate_no;";

        $re_que = $wpdb->get_results($wpdb->prepare($que, $selectedCategoryId), ARRAY_A);

        // Loop through each attribute
        foreach ($attval_results as $attval) {
            $attr_id = $attval['attr_id'];

            // Fetch term_ids associated with the attribute
            $term_ids_query = $wpdb->prepare("SELECT DISTINCT att_value FROM taw_filter_setting WHERE attribute = %s", $attr_id);
            
            $term_ids_results = $wpdb->get_results($term_ids_query, ARRAY_A);

            $termvalues = array(); // Initialize an array to store term values

            foreach ($term_ids_results as $term_ids_results_row) {
                $termvalues[] = $term_ids_results_row['att_value'];
            }

            $combined_data = implode(",", $termvalues);
            $values_array = explode(",", $combined_data);
            $unique_values = array_unique($values_array);

            // Fetch existing att_values for the attribute
           // $att_qu = $wpdb->prepare("SELECT DISTINCT att_value FROM taw_filter_setting WHERE attribute = %s", $attr_id);
            $att_qu =   $wpdb->prepare(
                "SELECT DISTINCT att_value FROM taw_filter_setting WHERE attribute = %s AND lang = %s",
                $attr_id,
                $selectedLanguage
            );
            $att_re = $wpdb->get_results($att_qu, ARRAY_A);
            $att_re_values = array_column($att_re, 'att_value');

            // Loop through each category
            //foreach ($re_que as $cate) {
                // Loop through term_ids
                foreach ($unique_values as $item) {
                    $term_id = $item;
$inserted = false;
                    $swedishterm_id = $wpdb->get_var("SELECT tsm_icl_translations.element_id
                                        FROM tsm_icl_translations
                                        WHERE tsm_icl_translations.trid = (
                                        SELECT tsm_icl_translations.trid
                                        FROM tsm_terms
                                        JOIN tsm_icl_translations ON tsm_terms.term_id = tsm_icl_translations.element_id
                                        WHERE tsm_terms.slug = '$term_id'
                                            AND tsm_icl_translations.element_type LIKE 'tax_pa_%'
                                            AND tsm_icl_translations.language_code = 'en'
                                        )
                                        AND tsm_icl_translations.language_code = 'sv'");

                    // $swedishterm_id= $term_id.'-sv';
                    $swed_termiid = "SELECT tsm_terms.name FROM tsm_terms WHERE tsm_terms.term_id = '$swedishterm_id'";
                    $sw_termiid = $wpdb->get_results($swed_termiid, ARRAY_A);
                    foreach ($sw_termiid as $swedattri)
                    {
                        if($selectedLanguage == 0){
                            $attributevalue=$swedattri['name'];
                        } else {   
                            $attributevalue=$term_id;
                        }
                    // If term_id is not in att_values, insert into taw_filter_setting
                    //if (!in_array($term_id, $att_re_values)) {  
                        // Set lang based on your condition
                        $lang = $selectedLanguage;
if (!$inserted) {
                        // Insert into taw_filter_setting
                        $wpdb->insert(
                            "taw_filter_setting",
                            array(
                                'cate_no' =>  $selectedCategoryId,
                                'attribute' => $attr_id,
                                'att_value' => $attributevalue,
                                'lang' => $lang,
                                'datasheetfilt_enable' => 1,
                            ),
                            array(
                                '%s',
                                '%s',
                                '%s',
                                '%s',
                                '%d',
                            )
                        ); 
$inserted = true; }
                    }
                }
        }
    }
    if($selectedLan=='1'){
        // Fetch product categories
        // $category_query = "SELECT * FROM tsm_terms
        // INNER JOIN tsm_term_taxonomy ON tsm_terms.term_id = tsm_term_taxonomy.term_id
        // WHERE tsm_term_taxonomy.taxonomy = 'product_cat'  and tsm_terms.slug not like '%-sv'";
        $category_query = "SELECT * from tsm_terms INNER JOIN tsm_term_taxonomy ON tsm_terms.term_id = tsm_term_taxonomy.term_id 
                           join tsm_icl_translations on tsm_icl_translations.element_id=tsm_term_taxonomy.term_id 
                           WHERE tsm_term_taxonomy.taxonomy = 'product_cat' and tsm_icl_translations.element_type='tax_product_cat' and tsm_icl_translations.language_code='en'";
        $category_results = $wpdb->get_results($category_query, ARRAY_A);
    
        // Fetch distinct cate_no values from taw_filter_setting
        $cate_no_query = "SELECT DISTINCT cate_no FROM taw_filter_setting WHERE lang = '1'";
        $cate_no_results = $wpdb->get_results($cate_no_query, ARRAY_A);

    }else{
    // Fetch product categories
        // $category_query = "SELECT * FROM tsm_terms
        // INNER JOIN tsm_term_taxonomy ON tsm_terms.term_id = tsm_term_taxonomy.term_id
        // WHERE tsm_term_taxonomy.taxonomy = 'product_cat'  and tsm_terms.slug  like '%-sv'";
        $category_query = "SELECT * from tsm_terms INNER JOIN tsm_term_taxonomy ON tsm_terms.term_id = tsm_term_taxonomy.term_id 
                           join tsm_icl_translations on tsm_icl_translations.element_id=tsm_term_taxonomy.term_id 
                           WHERE tsm_term_taxonomy.taxonomy = 'product_cat' and tsm_icl_translations.element_type='tax_product_cat' and tsm_icl_translations.language_code='sv'";
    $category_results = $wpdb->get_results($category_query, ARRAY_A);

        // Fetch distinct cate_no values from taw_filter_setting
        $cate_no_query = "SELECT DISTINCT cate_no FROM taw_filter_setting WHERE lang = '0'";
        $cate_no_results = $wpdb->get_results($cate_no_query, ARRAY_A);
    }

    foreach ($category_results as &$category) {
        $category = array_map('htmlspecialchars', $category);
    }

foreach ($cate_no_results as &$categoryno) {
        $categoryno = array_map('htmlspecialchars', $categoryno);
    }
  
    // Extract 'name' values from $cate_no_results
    $cate_no_names = array_column($cate_no_results, 'cate_no');
    // Find the categories that are present in $category_results but not in $cate_no_names
    $unmatched_categories = array_filter($category_results, function($category) use ($cate_no_names) {
        return !in_array($category['name'], $cate_no_names);
    });
    ?>
    <div class="wrap">
        <h2>Add Missing Category</h2>
        <form method="post" action="">
            <div style="text-align: center; margin: 20px; padding: 20px; border: 1px solid #ccc; background-color: #f9f9f9;">
                <div style="margin-bottom: 20px;">
                    <label for="selectCategory" style="margin-right: 10px;">Select Category:</label>
                    <select name="selectCategory" style="width:450px;">
                        <?php
                        foreach ($unmatched_categories as $uncategory) {
                            echo '<option value="' . $uncategory['name'] . '">' . $uncategory['name'] . '</option>';
                        }
                        ?>
                    </select>
                </div>

                <!-- <div style="margin-bottom: 20px;">
                    <label for="language" style="margin-right: 10px;">Language:</label>
                    <input type="radio" name="language" value="1" checked style="margin-right: 5px;"> English
                    <input type="radio" name="language" value="0" style="margin-left: 10px;"> Swedish
                </div> -->
                <?php if (!empty($unmatched_categories)): ?>
                    <button type="submit" name="add_cate" style="padding: 10px; background-color: #4CAF50; color: white; border: none; border-radius: 5px;">Add</button>
                <?php endif; ?>

            </div>
        </form>
    </div>
<?php
}
function render_sub_menu_page()
{
    global $wpdb;

?>
    <div class="wrap">
        <h2>Submenu Page</h2>
        <form method="post" action="">
            <?php
            // Check if the sync button is clicked
            if (isset($_POST['sync_button'])) {
                $row_count = $wpdb->get_var("SELECT COUNT(*) FROM taw_filter_setting");

                $attribute = array(); // Initialize an array to store attributes
                $values = array();    // Initialize an array to store values

                // Step 1: Fetch descriptions from tsm_term_taxonomy where taxonomy is 'product_cat'
                $term_taxonomy_query = "SELECT description FROM {$wpdb->prefix}term_taxonomy WHERE taxonomy = 'product_cat'";
                $term_taxonomy_results = $wpdb->get_results($term_taxonomy_query, ARRAY_A);


                if ($term_taxonomy_results) {
                    foreach ($term_taxonomy_results as $term_taxonomy_row) {

                        $attribute[] = $term_taxonomy_row['description'];
                    }
                }

                // Step 2: Fetch attr_id values from taw_article_attributes
                $article_attributes_query = "SELECT DISTINCT attr_id FROM taw_article_attributes";
                $article_attributes_results = $wpdb->get_results($article_attributes_query, ARRAY_A);

                if ($article_attributes_results) {
                    foreach ($article_attributes_results as $article_attributes_row) {
                        $values[] = $article_attributes_row['attr_id'];
                    }
                }

                // Step 3: Process data
               foreach ($attribute as $attribute_value) {
                    foreach ($values as $value) {
                        // Step 4: Fetch term_ids from taw_article_attributes for each value
                        $term_ids_query = $wpdb->prepare("SELECT distinct(term_ids) FROM taw_article_attributes WHERE attr_id = %s", $value);
                       // $term_ids_query = $wpdb->prepare("SELECT distinct(term_ids) FROM taw_article_attributes WHERE attr_id = 'pa_shelves'");
                    
                        $term_ids_results = $wpdb->get_results($term_ids_query, ARRAY_A);

                       

                        if ($term_ids_results) {
                            $termvalues = array(); // Initialize an array to store term values

                            foreach ($term_ids_results as $term_ids_results_row) {
                                $termvalues[] = $term_ids_results_row['term_ids'];
                            }
                           
                            $combined_data = implode(",", $termvalues);
                            $values_array = explode(",", $combined_data);
                            $unique_values = array_unique($values_array);
                            $unique_values_array = array_values($unique_values);
                            $output = '<br>';
                            $output .= ($value);
                            $output .= '<br>';
                            $output .= json_encode($unique_values_array);

                            $swedishlangcheck = $wpdb->get_results("SELECT tsm_terms.name FROM `tsm_term_taxonomy`
                            INNER JOIN `tsm_terms` ON tsm_term_taxonomy.term_id = tsm_terms.term_id
                            WHERE tsm_term_taxonomy.taxonomy = 'product_cat' AND tsm_terms.slug LIKE '%-sv'; ");
                            
                            $lang = '1';
                            foreach ($swedishlangcheck as $term) {
                                $attribute_value_normalized = str_replace('-', '', strtolower($attribute_value));
                                $term_name_normalized = str_replace('-', '', strtolower($term->name));
                                
                                if ($attribute_value_normalized === $term_name_normalized) {
                                    $lang = '0'; // Set lang to '0' if a match is found
                                }
                            
                            // Insert new records into taw_filter_setting for values that do not exist
                            foreach ($unique_values_array as $term_value) {

                               $existing_record_att = $wpdb->get_row("SELECT * FROM `taw_filter_setting` WHERE `cate_no` = '$attribute_value' and `attribute` = '$value' and `att_value` = '$term_value'");
                            // $existing_record_att = $wpdb->get_row("SELECT * FROM `taw_filter_setting` WHERE `cate_no` = '$attribute_value' and `attribute` = 'pa_shelves' and `att_value` = '$term_value'");
                                
                             if (!$existing_record_att) {
                                    $wpdb->insert(
                                        "taw_filter_setting",
                                        array(
                                            'cate_no' => $attribute_value,
                                            'attribute' => $value,
                                            'att_value' => $term_value,
                                            'lang' => $lang,
                                        ),
                                        array(
                                            '%s',
                                            '%s',
                                            '%s',
                                            '%s',
                                        )
                                    );
                                }
                            }
                        }

                            $termvalues = [];
                        }
                    }
                }

                echo '<div class="updated"><p>Data synchronized successfully!</p></div>';
            }
            ?>
            <p>Click the "Sync" button to perform synchronization:</p>
            <input type="submit" name="sync_button" class="button button-primary" value="Sync">
        </form>
    </div>
    <?php
}

function missing_attri_menu_page(){
    global $wpdb;   
   
    ?>
    <div class="wrap1">
        <h2>Submenu Page</h2>
        <form method="post" action="">
            <?php
           
            if (isset($_POST['miss_button'])) { 
                $att_query = "SELECT DISTINCT attr_id FROM taw_article_attributes";
                $att_results = $wpdb->get_results($att_query, ARRAY_A);
               // console.log('results'.json_encode($att_results));
                //echo "<script>console.log('results " . json_encode($att_results) . "');</script>";
                $que="SELECT MIN(id) AS id, cate_no
                FROM taw_filter_setting
                GROUP BY cate_no;";
                $re_que=$wpdb->get_results($que, ARRAY_A);
                
             //   echo "<script>console.log('results " . json_encode($re_que) . "');</script>";
                foreach ($re_que as $cate) {
                $att_qu = $wpdb->prepare( "SELECT DISTINCT attribute FROM taw_filter_setting where cate_no=%s",$cate['cate_no']);
                $att_re = $wpdb->get_results($att_qu, ARRAY_A);
                $att_re_values = [];
                    foreach ($att_re as $item) {
                        $att_re_values[] = $item['attribute'];
                    }
                  //  echo "<script>console.log('results " .$attr_id. json_encode($att_re) . "');</script>";
                    foreach ($att_results as $item) {
                        $attr_id = $item['attr_id'];
                        // print_r($att_re_values);
                        // exit;
                        if (!in_array($attr_id, $att_re_values)) {   
                            if (intval($cate['id']) > 20055) {
                                $lang = 0; // Set lang to 1
                            } else {
                                $lang = 1; // Set lang to 0
                            }
                         //   echo "<script>console.log('results " .$attr_id. json_encode($cate['cate_no']) . $lang.$cate['id']."');</script>";
                           // $term_ids_query = $wpdb->prepare("SELECT distinct(term_ids) FROM taw_article_attributes WHERE attr_id = %s",$attr_id);
                           $term_ids_query = $wpdb->prepare("SELECT distinct(att_value) FROM taw_filter_setting");
                         //  $catt=$cate['cate_no'];
                             $term_ids_results = $wpdb->get_results($term_ids_query, ARRAY_A);
                             echo "<script>console.log('Count of results: " . count($term_ids_results) . "');</script>";
     
                             if ($term_ids_results) {
                                // Initialize an array to store term values
     
                                 foreach ($term_ids_results as $term_ids_results_row) {
                                     $termvalues = $term_ids_results_row['att_value'];
                                 //    echo "<br>";
                                // echo "<script>console.log('attrterms " . $termvalues. "');</script>";
                               //  echo "<script>console.log('category " . $cate['cate_no']. "');</script>";
                               //  echo "<script>console.log('category " . $attr_id. "');</script>";
                               //  echo "<script>console.log('category " . $lang. "');</script>";


                                   $wpdb->insert(
                                        "taw_filter_setting",
                                        array(
                                            'cate_no' => $cate['cate_no'],
                                            'attribute' => $attr_id,
                                            'att_value' => $termvalues,
                                            'lang' => $lang,
                                        ),
                                        array(
                                            '%s',
                                            '%s',
                                            '%s',
                                            '%s',
                                        )
                                    ); 
                                 }

                                 //echo "<br>";
                                 //echo "<script>console.log('attrterms " . json_encode($termvalues) . "');</script>";
                                }
                              /*  */
                            
                        }
                    }

                }
             


            }?>

    <p>Click the "Sync" button to perform synchronization:</p>
            <input type="submit" name="miss_button" class="button button-primary" value="Sync Attributes"></form>
            </div>
<?php
}

function missing_attrivalue_menu_page() {
    global $wpdb;
    
    // Increase time limits for large operations
    set_time_limit(300);
    ini_set('max_execution_time', 300);
    
    ?>
    <div class="wrap1">
        <h2>Submenu Page</h2>
        <form method="post" action="">
            <?php
            if (isset($_POST['missvalue_button'])) {
                // Helper function to normalize values
                function normalize_value($value) {
                    return str_replace([' ', '-', '%c2%bd'], ['', '', '½'], $value);
                }

                // Get all attributes and their terms
                $attribute_terms = $wpdb->get_results(
                    "SELECT attr_id, GROUP_CONCAT(term_ids) AS all_terms 
                     FROM taw_article_attributes 
                     GROUP BY attr_id",
                    ARRAY_A
                );

                // Get all categories
                $categories = $wpdb->get_col("SELECT DISTINCT cate_no FROM taw_filter_setting");
                
                // Get language information for categories
                $product_categories = $wpdb->get_results(
                    "SELECT t.name, t.slug 
                     FROM tsm_terms AS t 
                     JOIN tsm_term_taxonomy AS tax ON t.term_id = tax.term_id 
                     WHERE tax.taxonomy = 'product_cat'", 
                    ARRAY_A
                );
                
                // Classify categories by language
                $svTerms = [];
                $nonSvTerms = [];
                foreach ($product_categories as $term) {
                    $swedish_term_id = $wpdb->get_var($wpdb->prepare(
                        "SELECT element_id FROM tsm_icl_translations
                         WHERE trid = (
                            SELECT trid FROM tsm_icl_translations
                            WHERE element_id = (SELECT term_id FROM tsm_terms WHERE slug = %s)
                            AND element_type LIKE 'tax_product_cat'
                         ) AND language_code = 'sv'",
                        $term['slug']
                    ));
                    if (empty($swedish_term_id)) {
                        $svTerms[] = htmlspecialchars($term['name']);
                    } else {
                        $nonSvTerms[] = htmlspecialchars($term['name']);
                    }
                }

                // Process each attribute
                foreach ($attribute_terms as $attr_data) {
                    $attribute = $attr_data['attr_id'];
                    $terms = !empty($attr_data['all_terms']) ? 
                        array_unique(explode(',', $attr_data['all_terms'])) : 
                        [];
                    
                    // Process each category
                    foreach ($categories as $category_name) {
                        $is_swedish = in_array(htmlspecialchars($category_name), $svTerms);
                        
                        // Get existing values for this attribute+category
                        $existing_values = $wpdb->get_col($wpdb->prepare(
                            "SELECT att_value FROM taw_filter_setting
                             WHERE attribute = %s AND cate_no = %s",
                            $attribute,
                            $category_name
                        ));
                        $normalized_existing = array_map('normalize_value', $existing_values);
                        
                        // Add missing terms
                        foreach ($terms as $term) {
                            $normalized_term = normalize_value($term);
                            
                            if (!in_array($normalized_term, $normalized_existing)) {
                                $value_to_insert = $term;
                                
                                // Handle Swedish translation
                                if ($is_swedish) {
                                    $swedish_term = $wpdb->get_var($wpdb->prepare(
                                        "SELECT t.name FROM tsm_terms t
                                         JOIN tsm_term_taxonomy tax ON t.term_id = tax.term_id
                                         JOIN tsm_icl_translations icl ON t.term_id = icl.element_id
                                         WHERE icl.trid = (
                                            SELECT trid FROM tsm_icl_translations
                                            WHERE element_id = (
                                                SELECT term_id FROM tsm_terms WHERE slug = %s
                                            ) AND element_type LIKE 'tax_pa_%%'
                                         ) AND icl.language_code = 'sv'
                                         AND tax.taxonomy = %s",
                                        $term,
                                        $attribute
                                    ));
                                    if ($swedish_term) {
                                        $value_to_insert = $swedish_term;
                                    }
                                }
                                
                                $wpdb->insert(
                                    "taw_filter_setting",
                                    [
                                        'cate_no' => $category_name,
                                        'attribute' => $attribute,
                                        'att_value' => $value_to_insert,
                                        'lang' => $is_swedish ? 0 : 1,
                                        'datasheetfilt_enable' => 1
                                    ],
                                    ['%s', '%s', '%s', '%d', '%d']
                                );
                            }
                        }
                        
                        // Ensure at least one record exists (even if no terms)
                        if (empty($existing_values) && empty($terms)) {
                            $wpdb->insert(
                                "taw_filter_setting",
                                [
                                    'cate_no' => $category_name,
                                    'attribute' => $attribute,
                                    'att_value' => '',
                                    'lang' => $is_swedish ? 0 : 1,
                                    'datasheetfilt_enable' => 1
                                ],
                                ['%s', '%s', '%s', '%d', '%d']
                            );
                        }
                    }
                }
                
                echo '<div class="notice notice-success"><p>Synchronization completed successfully!</p></div>';
            }
            ?>
            <p>Click the "Sync" button to perform synchronization:</p>
            <input type="submit" name="missvalue_button" class="button button-primary" value="Sync Attributes">
        </form>
    </div>
    <?php
}


function deselect_display_value_callback(){
    global $wpdb;
    $select_cat=($_POST['cate_no']);
    $attr=($_POST['attribute']);
    
   
  $result = $wpdb->update(
     'taw_filter_setting',
    array('display' => 0,
           'filt_attr' => 0),
    array(
        'cate_no' => $select_cat,
        'attribute' => $attr
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




function change_display_value_callback(){
    global $wpdb;
    $select_cat=($_POST['cate_no']);
    $attr=($_POST['attribute']);
    
   
    echo "<script>console.log('select_cat'.$select_cat);</script>";
    echo "<script>console.log('attr'.$attr);</script>";
    // Update the display column in the taw_filter_setting table
   /* $result = $wpdb->update(
     'taw_filter_setting',
    array('display' => $isChecked),
    array(
        'cate_no' => $select_cat,
        'attribute' => $attr,
        'att_value' => $attValue,
        
    )
);
*/

$result = $wpdb->update(
     'taw_filter_setting',
    array('display' => 1,
           'filt_attr' => 1),
    array(
        'cate_no' => $select_cat,
        'attribute' => $attr
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


// $categories = get_terms(['taxonomy' => 'category', 'hide_empty' => true]);
// Create a function to render the HTML for the select box and product list


function render_category_product_page() {
    global $wpdb;
    $attribute_values = [];
    $att_val_ues = [];
    $currentlang = getSiteCurrentLang();
    // $cache_key = 'cate_no_results_' . $currentlang;

    // // Try to get cached results
    // $cate_no_results = get_transient($cache_key);
    // if ($cate_no_results === false) {
    // Query to get distinct cate_no values from the taw_filter_setting table
        $lang_value = ($currentlang === 'en') ? '1' : '0';
        $cate_no_query = $wpdb->prepare("SELECT DISTINCT cate_no FROM taw_filter_setting WHERE lang = %s", $lang_value);
    $cate_no_results = $wpdb->get_results($cate_no_query, ARRAY_A);
        // Cache results for 1 hour
        // set_transient($cache_key, $cate_no_results, HOUR_IN_SECONDS);
    // }

    // Create a dropdown to select cate_no values
    echo '<div id="filters">';
    echo '<form id="category_form" method="post">';
    echo '<label for="cate_no">Select category:</label>';
    echo '<div>';
    echo '<select name="cate_no" id="cate_no">';
    foreach ($cate_no_results as $row) {
        $selected = (isset($_POST['cate_no']) && $_POST['cate_no'] == $row['cate_no']) ? 'selected' : '';
        echo '<option value="' . htmlspecialchars($row['cate_no']) . '" ' . $selected . '>' . htmlspecialchars($row['cate_no']) . '</option>';
    }
    echo '</select>';
    echo '<input type="submit" name="submit" value="Submit">';
    // echo '<button type="button" id="sync_button">Sync</button>';
    echo '</div>';
    echo '</form>';
    echo '<div id="loader" style="display: none;"><img src="' . esc_url(admin_url('images/spinner.gif')) . '" alt="Loading..."></div>';

echo '<div id="category_results" class="d-none"></div>';
    echo '</div>';

    ?>
        <style>
        #filters {
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        #category_form {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }
        #category_form label {
            margin-bottom: 10px;
            font-size: 16px;
            font-weight: bold;
        }
        #category_form select, #category_form input[type="submit"], #sync_button {
            
           
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        #category_form input[type="submit"], #sync_button {
            background-color: #0073aa;
            color: white;
            padding: 5px 10px;
            cursor: pointer;
        }
        #category_form input[type="submit"]:hover, #sync_button:hover {
            background-color: #005a87;
        }
        #loader {
            text-align: center;
            margin: 20px 0;
        }
        #category_results {
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 8px;
            margin-top: 10px;
        }
        .attribute-list {
            list-style-type: none;
            padding: 0;
        }
        .attribute-list li {
            padding: 10px;
            
        }
        .attribute-list input[type="checkbox"] {
            margin-right: 10px;
        }
        .d-none{
            display: none;
        }
        .filter-setting-option-container{
            padding: 10px;
            border-radius: 20px;
        }
        
    </style>

    <?php
}

// AJAX handler for sync action
add_action('wp_ajax_sync_category', 'sync_category');
add_action('wp_ajax_nopriv_sync_category', 'sync_category'); // For non-logged in users

function sync_category() {
    global $wpdb;
    if (!isset($_POST['cate_no'])) {
        wp_send_json_error(['message' => 'Invalid category number.']);
    }

    $cate_no = sanitize_text_field($_POST['cate_no']);

    $delete_cate = $wpdb->prepare("DELETE FROM `taw_categotyfilter_setting` WHERE `cate_no` = %s", $cate_no);
    $wpdb->query($delete_cate);

    // Query to get cate_no, attribute, att_value, lang from taw_filter_setting
    $sync_query = $wpdb->prepare("SELECT cate_no, attribute, att_value, lang FROM taw_filter_setting WHERE cate_no = %s AND filt_enable = '1' AND filt_attr = '1'", $cate_no);
    $sync_results = $wpdb->get_results($sync_query, ARRAY_A);

    if (empty($sync_results)) {
        wp_send_json_error(['message' => 'No data found for the selected category.']);
    }

    foreach ($sync_results as $row) {
        $wpdb->insert('taw_categotyfilter_setting', [
            'cate_no' => $row['cate_no'],
            'attribute' => $row['attribute'],
            'att_value' => $row['att_value'],
            'lang' => $row['lang']
        ]);
    }

    wp_send_json_success(['message' => 'Category synced successfully.']);
}

//add_action('wp_ajax_get_category_data', 'get_category_data');
//add_action('wp_ajax_nopriv_get_category_data', 'get_category_data'); // For non-logged in users

add_action('wp_ajax_get_category_data', 'get_category_data');
add_action('wp_ajax_nopriv_get_category_data', 'get_category_data'); // For non-logged in users

function get_category_data() {
    global $wpdb;
    $display_value = 0;
    $attribute_values = [];
    $att_val_ues = [];
    $currentlang = getSiteCurrentLang();

 

    if (isset($_POST['cate_no'])) {
        $selected_cate_no = sanitize_text_field($_POST['cate_no']);


        // Start output buffering
        ob_start();

        // Query to get distinct attribute values for the selected cate_no
        $attribute_query = $wpdb->prepare(
            "SELECT DISTINCT(attribute), filt_attr FROM taw_filter_setting WHERE cate_no = %s",
            $selected_cate_no
        );
        $attribute_results = $wpdb->get_results($attribute_query, ARRAY_A);

        echo '<center><h2>' . esc_html($selected_cate_no) . '</h2></center>';
        echo '<ul style="background-color: powderblue; border-radius:20px; padding:10px;">';
foreach ($attribute_results as $attribute_row) {
    $current_attribute = esc_html($attribute_row['attribute']);

    if (!in_array($current_attribute, $attribute_values)) {
                $attribute_values[] = $current_attribute;

                // Check if the attribute should be displayed
    $query = $wpdb->prepare(
                    "SELECT filt_enable FROM taw_filter_setting WHERE cate_no = %s AND attribute = %s LIMIT 1",
        $selected_cate_no,
        $current_attribute
    );
    $count = $wpdb->get_var($query);

                $display_value = ($count > 0) ? 1 : 0;
                $is_checked = ($display_value == 1) ? 'checked' : '';

                echo '<li style="padding: 10px; font-size: 18px;">';
                // echo '<input type="checkbox" name="' . esc_attr($current_attribute) . '" class="att_check" value="' . esc_attr($display_value) . '"  ' . esc_attr($is_checked) . '>';
   echo '<input type="checkbox" name="'. $current_attribute .'" class="att_check" value="' . $display_value . '" ' . ($display_value == 1 ? 'checked' : '') . '>';

                echo '<span style="margin-left: 5px; font-size: 20px;">' . esc_html($current_attribute) . '</span>';
                echo '</li>';

                echo '<input type="submit" style="padding: 5px 10px; border-color:#ccc; border-width:1px; border-radius:5px;" name="' . esc_attr($current_attribute) . '" value="Select All" class="select">';
                echo '<input type="submit" style="padding: 5px 10px; border-color:#ccc; border-width:1px; border-radius:5px;" name="' . esc_attr($current_attribute) . '" value="Deselect All" class="deselect">';
                
                // Query to get distinct att_value values for the current attribute
                $att_value_query = $wpdb->prepare(
                    "SELECT DISTINCT id, att_value, display FROM taw_filter_setting WHERE cate_no = %s AND attribute = %s",
    $selected_cate_no,
    $current_attribute
);
                $att_value_results = $wpdb->get_results($att_value_query, ARRAY_A);

                echo '<div style="display: flex; flex-wrap: wrap; gap: 10px; margin-top: 5px;">';
               

          

    foreach ($att_value_results as $att_value_row) {
        $current_att_value = esc_html($att_value_row['att_value']);
                    $check_id = esc_html($att_value_row['id']);

        if (!in_array($current_att_value, $att_val_ues)) {
                        $att_val_ues[] = $current_att_value;

                        // $is_checked = (get_display_value($selected_cate_no, $current_attribute, $current_att_value) == 1) ? 'checked' : '';
                        $is_checked = ( esc_html($att_value_row['display']) == 1) ? 'checked' : '';
  

                        echo '<div style="border-radius:5px; border:1px solid #ccc; width: 150px; padding: 10px; font-size: 18px; background-color: white;">';
                        echo '<input type="checkbox" class="' . esc_attr($current_attribute) . '" name="' . esc_attr($check_id) . '"  value="' . esc_attr($current_att_value) . '" ' . esc_attr($is_checked) . ' '.($display_value == 0 ? 'disabled' : '') .'>';
                        echo '<span style="margin-left: 5px;">' . esc_html($current_att_value) . '</span>';
                        echo '</div>';
                    }
                }

                $att_val_ues = [];
                echo '</div>';
            }
            
        }

        echo '</ul>';
        ?>
        <input type="hidden" id="hiddenAttribute" value="<?php echo esc_attr($current_attribute); ?>">
        <input type="hidden" id="hiddenSelectedCateNo" value="<?php echo htmlspecialchars($selected_cate_no); ?>">
        <?php
        // Get the output buffer content
        $html = ob_get_clean();


        // Return the output buffer content
        echo $html;
        }

    wp_die(); // Always use wp_die() at the end to terminate AJAX handling
}




function get_display_value($cate_no, $attribute, $att_value) {
    global $wpdb;
    $query = $wpdb->prepare("SELECT display FROM taw_filter_setting WHERE cate_no = %s AND attribute = %s AND att_value = %s", $cate_no, $attribute, $att_value);
    return $wpdb->get_var($query);
}

// Add an AJAX action for updating the display value
add_action('wp_ajax_update_display_value', 'update_display_value_callback');
add_action('wp_ajax_nopriv_update_display_value', 'update_display_value_callback');
add_action('wp_ajax_block_display_value', 'block_display_value_callback');
add_action('wp_ajax_nopriv_block_display_value', 'block_display_value_callback');
add_action('wp_ajax_change_display_value', 'change_display_value_callback');
add_action('wp_ajax_nopriv_change_display_value', 'change_display_value_callback');
add_action('wp_ajax_deselect_display_value', 'deselect_display_value_callback');
add_action('wp_ajax_nopriv_deselect_display_value', 'deselect_display_value_callback');


function update_display_value_callback() {
    global $wpdb;

    // Get attValue and isChecked from the AJAX request
    //$attValue = sanitize_text_field($_POST['attValue']);
    $attValue = ($_POST['attValue']);
    $isChecked = $_POST['isChecked'] === 'true' ? 1 : 0; // Convert to 1 (checked) or 0 (unchecked)
    $select_cat=($_POST['cate_no']);
    $attr=($_POST['attribute']);
    $check_id=($_POST['check']);
    $filt=($_POST['attr']);
    // echo "<script>console.log('updated'.$isChecked);</script>";
    // echo "<script>console.log('attValue'.$attValue);</script>";
    // echo "<script>console.log('select_cat'.$select_cat);</script>";
    // echo "<script>console.log('attr'.$attr);</script>";
    // Update the display column in the taw_filter_setting table
   /* $result = $wpdb->update(
     'taw_filter_setting',
    array('display' => $isChecked),
    array(
        'cate_no' => $select_cat,
        'attribute' => $attr,
        'att_value' => $attValue,
        
    )
);
*/

$result = $wpdb->update(
     'taw_filter_setting',
    array('display' => $isChecked,
           'filt_attr' => $isChecked),
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

function block_display_value_callback(){

 global $wpdb;

    // Get attValue and isChecked from the AJAX request
    //$attValue = sanitize_text_field($_POST['attValue']);
    $attValue = ($_POST['attValue']);
    $isChecked = $_POST['attr'] === 'true' ? 1 : 0; // Convert to 1 (checked) or 0 (unchecked)
    $select_cat=($_POST['cate_no']);
    $attr=($_POST['attribute']);
    $check_id=($_POST['check']);
    $filt=($_POST['attr']);
    // echo "<script>console.log('updated'.$isChecked);</script>";
    // echo "<script>console.log('attValue'.$attValue);</script>";
    // echo "<script>console.log('select_cat'.$select_cat);</script>";
    // echo "<script>console.log('attr'.$attr);</script>";

    $result = $wpdb->update(
        'taw_filter_setting',
       array('filt_enable' => $isChecked
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



function enqueue_jquery() {
    wp_enqueue_script('script-js', TAW_FILTER_DIR . '/js/script.js' . TAW_FILTER_VERSION, ['jquery'], null, true);
wp_enqueue_style('style-css', TAW_FILTER_DIR . '/css/style.css' . TAW_FILTER_VERSION);
    
}

add_action('admin_enqueue_scripts', 'enqueue_jquery');
//add_action('wp_enqueue_scripts', 'enqueue_jquery');

?>
