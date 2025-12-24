<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

if (!class_exists('Product_Stack_Pricing_Import_Export')) {
    require_once PRODUCT_STACK_PRICING_PATH . 'includes/class-product-stack-pricing-import-export.php';
}

class Product_Stack_Pricing
{
    
    // Constructor
    public function __construct()
    {
        // Add hooks and actions here
        add_action('admin_menu', array($this, 'add_submenu_page'));
        register_activation_hook(__FILE__, array($this, 'plugin_activation_check'));

        add_action('wp_ajax_psp_save_product_data', array($this, 'psp_save_product_data'));
        add_action('wp_ajax_nopriv_psp_save_product_data', array($this, 'psp_save_product_data'));
        add_action('wp_ajax_psp_save_product_data', array($this, 'psp_save_product_data'));
        add_action('wp_ajax_psp_refresh_product_list', array($this, 'psp_refresh_product_list'));
        add_action('wp_ajax_psp_save_product_data', array($this, 'psp_save_product_data'));
        add_action('wp_ajax_nopriv_psp_refresh_product_list', array($this, 'psp_refresh_product_list'));
        add_action('wp_ajax_psp_refresh_rule_list', array($this, 'psp_refresh_rule_list'));
        add_action('wp_ajax_psp_save_product_data', array($this, 'psp_save_product_data'));
        add_action('wp_ajax_nopriv_psp_refresh_rule_list', array($this, 'psp_refresh_rule_list'));
        add_action('wp_ajax_psp_fetch_product_data', array($this, 'psp_fetch_product_data'));
        add_action('wp_ajax_nopriv_psp_fetch_product_data', array($this, 'psp_fetch_product_data'));

        add_action('wp_ajax_psp_delete_product', array($this, 'psp_delete_product'));
        add_action('wp_ajax_nopriv_psp_delete_product', array($this, 'psp_delete_product'));

        add_action('wp_ajax_psp_get_user_matches', array($this, 'psp_get_user_matches'));
        add_action('wp_ajax_nopriv_psp_get_user_matches', array($this, 'psp_get_user_matches'));

        add_action('wp_ajax_psp_delete_stacking_rule', array($this, 'psp_delete_stacking_rule'));
        add_action('wp_ajax_nopriv_delete_stacking_rule', array($this, 'psp_delete_stacking_rule'));

        add_action('wp_ajax_psp_refresh_EditAddRulePopup', array($this, 'psp_refresh_EditAddRulePopup'));
        add_action('wp_ajax_nopriv_psp_refresh_EditAddRulePopup', array($this, 'psp_refresh_EditAddRulePopup'));

        add_action('wp_ajax_psp_save_stacking_rule', array($this, 'psp_save_stacking_rule'));
        add_action('wp_ajax_nopriv_psp_save_stacking_rule', array($this, 'psp_save_stacking_rule'));


        add_action('wp_ajax_psp_get_users_by_role', array($this, 'psp_get_users_by_role'));
        add_action('wp_ajax_nopriv_psp_get_users_by_role', array($this, 'psp_get_users_by_role'));
        add_action('wp_ajax_psp_process_batch',array($this, 'psp_process_batch_handler') );
        add_action('wp_ajax_psp_start_import', array($this, 'psp_start_import_handler'));
        // css and js links
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_scripts'));
        // AJAX action handler for logged in users
        add_action('wp_ajax_psp_get_product_matches_skus', array($this, 'psp_get_product_matches_skus'));

        // AJAX action handler for non-logged in users
        add_action('wp_ajax_nopriv_psp_get_product_matches_skus', array($this, 'psp_get_product_matches_skus'));
        add_filter('woocommerce_get_variation_prices_hash', array($this, 'add_price_multiplier_to_variation_prices_hash'), 99, 4);


        // infront end showing price for users hook 
        add_action('woocommerce_product_get_price', array($this, 'apply_special_price_to_product'), 99, 4);
        add_action( 'woocommerce_single_product_summary', array($this, 'include_custom_psp_template_after_description'), 21,4 );
     
      
    }



    public function custom_quantity_display_values($product,$quantity) {
        $attributes = $product->get_attributes();
        $output = array();
        $box_slug = 'pa_quantity-in-the-package';
        $pallet_slug = 'pa_quantity-on-pallet';

     
        $box_values = isset($attributes[$box_slug]) ? $this->get_attribute_values($box_slug, $product) : [];
        $pallet_values = isset($attributes[$pallet_slug]) ? $this->get_attribute_values($pallet_slug, $product) : [];
        $units_per_box = !empty($box_values) ? $this->get_pcs_in_numbers($box_values) : 0;
        $units_per_pallet =!empty($pallet_values) ? $this->get_pcs_in_numbers($pallet_values) : 0;

        $whole_packages = 0;
        $remaining_units = $quantity;
 
 if(!empty($pallet_values)) {
             $output['isPallet'] = true;
             $output['units_for_whole_packages'] = $units_per_pallet;
            $whole_packages = (int) ceil( $quantity / $units_per_pallet );
            $remaining_units = $quantity % $units_per_pallet;
        }
        elseif (!empty($box_values)) {
           

             $output['isBox'] = true;
             $output['units_for_whole_packages'] = $units_per_box;
            $whole_packages = intdiv($quantity, $units_per_box);
            $remaining_units = $quantity % $units_per_box;
        } 

     

        if ($whole_packages > 0) {
            $output['whole_packages'] = $whole_packages;
       
        }

        if ($remaining_units > 0) {
            $output['remaining_units'] = $remaining_units;
 
        }

        return $output;
    }

    // Helper function you can include somewhere globally
    public function get_pcs_in_numbers($box_values) {
        if (!empty($box_values)) {
            if (preg_match('/\d+/', $box_values[0], $matches)) {
                $first_number = $matches[0];
                return $first_number;
            }
        }
        return 0;
    }

    public function get_attribute_values($attribute_slug, $product)
    {
        // Get terms related to the product's attribute
        $terms = wp_get_post_terms($product->get_id(), $attribute_slug);

        // If the attribute terms exist
        if (!empty($terms)) {
            $term_names = [];
            foreach ($terms as $term) {
            $term_names[] = $term->name;
            }
            return $term_names; // return array instead of imploded string
        }
        return [];
    }


    public function apply_special_price_to_product($price, $product)
    {
        $price = getCustomPrice($product,$price);
        if (is_user_logged_in() && (is_cart() || is_checkout() || is_order_received_page() || is_wc_endpoint_url('order-received'))) {

            $user_id = get_current_user_id();
            $quantity = $this->get_cart_item_quantity($product->get_id());
            $customer_no = get_user_meta($user_id, 'subcustomer_no', true);
   
            $mainUser_ID=$user_id;
            $desired_roles = ['custom_uam_b2b', 'custom_uam_reseller_sek', 'custom_uam_reseller_eur'];
            $user_roles=$this->useRole($user_id);

            if ($customer_no) {
                $args = array(
                    'meta_key'     => 'customer_no',
                    'meta_value'   => $customer_no, // Replace with $customer_no if needed
                    'meta_compare' => '=',
                    'fields'       => 'ID' // Use 'ID' if you only want user_id
                );
        
                $user_query = new WP_User_Query($args);
                $users_with_same_customer_no = $user_query->get_results();
                $mainUser_ID=$users_with_same_customer_no[0];
                $user_roles=$this->useRole($mainUser_ID);
            }
                $psp_price = $this->calculate_special_price_subtotal($product, $quantity, $mainUser_ID);

            if ($psp_price) {
                return $psp_price;
            }
            else{
                $price=$this->productActualPrice($product->get_id(),$user_roles);
                return $price;
            }
        }
        return $price;
    }

    public function removerPrefix($userRole){
        $prefix = 'custom_uam_';
        $result = substr($userRole, strlen($prefix));
        return $result;
    }
    public function productActualPrice($productId,$userRole){
      
        global $wpdb;
        $CustomePriceQuery = $wpdb->prepare(
            "SELECT meta_value FROM `tsm_postmeta` WHERE post_id = %d AND meta_key = 'taw_prod_opt'", 
            $productId
        );
        $logged_in_user_id = get_current_user_id();  // WordPress function to get the logged-in user ID
        $logged_in_user_role = wp_get_current_user()->roles[0];  // Assuming the first role is sufficient

        $custom_price = $wpdb->get_var($CustomePriceQuery);
        $data = unserialize($custom_price);
        $article_price = $data['article_price'];
        $userRole=$this->removerPrefix($userRole);
        $RolePrice = $article_price[$userRole];
       // New logic: Check if product's categories match restricted categories
        $categories = wp_get_post_terms($productId, 'product_cat');  // Get the product's categories

        // Prepare an array of category slugs
        $category_slugs = [];
        foreach ($categories as $category) {
            $category_slugs[] = $category->slug;  // Store the category slugs in the array
        }

    
        // Check if any of the product categories are restricted
        $category_check = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM taw_restrict_category 
            WHERE  (Type = 'role' OR Type = 'user') AND (roleid = %s OR roleid = %d) 
            AND art_no IN ('" . implode("','", $category_slugs) . "')",
            $logged_in_user_role, 
            $logged_in_user_id
        ));

 
    

         // Check if any of the product categories are restricted
         $check_rule_set = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM taw_restrict_category 
            WHERE Type = 'role' AND (roleid = %s OR roleid = %d) ",
            $logged_in_user_role, 
            $logged_in_user_id
        ));
    
    
        // If there is a match, set the price to the restricted price or apply any other logic you need
        if (!$category_check && $check_rule_set) {
            return '-'; // If restricted, return price as 0
        }

        if($RolePrice===''){
           
            $RegulerPrice = $wpdb->prepare(
                "SELECT meta_value FROM `tsm_postmeta` WHERE post_id = %d AND meta_key = '_price'", 
                $productId // Assuming $productId is an object with a method get_id()
            );

            $RolePrice = $wpdb->get_var($RegulerPrice);
            if($RolePrice===''){
                return '-';
            }
          
        }else{
            return $RolePrice;
        }

        return $RolePrice;
    }

    public function useRole($user_id){
        $user = new WP_User($user_id);
        $user_roles = $user->roles;
        $user_roles=$user_roles[0];
        return $user_roles;
    }
   
    private function calculate_special_price_subtotal($product, $quantity, $user_id)
    {
        $user_role = $this->get_user_role_by_id($user_id);
        $special_price_data = $this->get_special_price_by_role($product->get_sku(), $user_id, $quantity, $user_role[0]);
        return $special_price_data;
    }

    public function get_user_role_by_id($user_id)
    {
        $user_info = get_userdata($user_id);
        if ($user_info && !empty($user_info->roles)) {
            return $user_info->roles; // This returns an array of roles
        }
        return false; // Return false if user has no roles or does not exist
    }


    public function add_price_multiplier_to_variation_prices_hash($price_hash, $product, $for_display)
    {
        $price_hash[] = getCustomPrice($product, 0);
        return $price_hash;
    }
    private function get_cart_item_quantity($product_id)
    {
        $quantity = 0;

        foreach (WC()->cart->get_cart() as $cart_item) {
            if ($cart_item['product_id'] == $product_id || $cart_item['variation_id'] == $product_id) {
                // Get the quantity of the product in the cart
                $quantity += $cart_item['quantity'];
            }
        }

        return $quantity;
    }

    public function get_special_price($product_id, $user_id, $qty, $role)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'product_stack_pricing';
        $user_id = intval($user_id);

        $query = $wpdb->prepare("
            SELECT rule_price, role, qty,enable_all_users,users
            FROM $table_name
            WHERE art_no = %s
            AND FIND_IN_SET(%d, users) > 0
            ORDER BY qty ASC
        ", $product_id, $user_id);

        $result = $wpdb->get_results($query);

        if ($result) {
            return $result; // Return the single row with the greatest qty
        }
        return false;
    }

    public function get_special_price_by_role($product_id, $user_id, $qty, $role)
    {
     
        global $wpdb;
        $table_name = $wpdb->prefix . 'product_stack_pricing';
        $user_id = intval($user_id);
        $Rulearr=[];

       /* $query = $wpdb->prepare("
        SELECT rule_price, role, qty, enable_all_users,users
        FROM $table_name
        WHERE art_no = %s
        AND role = %s
        ORDER BY qty ASC
    ", $product_id, $role);*/

    $query = $wpdb->prepare("
    SELECT rule_price, role, qty, enable_all_users, users
    FROM $table_name
    WHERE art_no = %s
    AND role = %s
    AND status = '1'
    ORDER BY qty ASC
    ", $product_id, $role);

        $result = $wpdb->get_results($query);
        ob_start();
        $output = ob_get_clean();
        error_log($output);

        if ($result) {
    
            foreach($result as $rule){
                $DB_qty=(int)$rule->qty;

                if($qty >= $DB_qty){
                    $priceqty=$this->PriceandQty($rule,$user_id);
                   if((count($priceqty)!==0)){
                    array_push($Rulearr,$priceqty);
                   }
                }
            }
          
            $finalPrice=$this->finalPrice($Rulearr);
            if($finalPrice!==-1){
                return $finalPrice;
            }

        }
   
        return false;
    }

    public function finalPrice($Rulearr){
            $maxQty = 0;
            $maxPrice = null;
            foreach ($Rulearr as $item) {
                // Convert qty to an integer for comparison
                $qty = (int)$item['qty'];
                
                // Check if the current qty is greater than the maxQty found so far
                if ($qty > $maxQty) {
                    $maxQty = $qty; // Update max quantity
                    $maxPrice = $item['price']; // Update max price
                }
            }

            if ($maxPrice !== null) {
                return $maxPrice;
            }
            return -1;
    }
    public function PriceandQty($special_price_data, $userID) {
        $values = [];
        $PriceandQty=[];
        
        foreach ($special_price_data as $data) {
            array_push($values, $data);
        }
    
        $psp_price_per_product = $values[0];
        $role = $values[1];
        $special_qty = $values[2];
        $enableAll = $values[3];
        $users = $values[4];
    
        $psp_price = null; // Initialize psp_price
    
        if ($enableAll === "1") {
            $psp_price = $psp_price_per_product;
          
            $PriceandQty = [
                "qty" => $special_qty, 
                "price" => $psp_price_per_product
            ];

        } else {
            $arrayString = explode(',', $users);
            $intValue = (int)$userID;
            $arrayInt = array_map('intval', $arrayString);
    
            foreach ($arrayInt as $value) {
                if ($value === $intValue) {
                    $PriceandQty = [
                        "qty" => $special_qty, 
                        "price" => $psp_price_per_product
                    ];
                }
            }
        }
        return $PriceandQty;
    }
    

    function psp_get_users_by_role()
    {
        if (isset($_POST['role'])&& isset($_POST['artNo'])){
            $role = sanitize_text_field($_POST['role']);
            $artno=sanitize_text_field($_POST['artNo']);

            $args = array(
                'role' => $role,
                'orderby' => 'user_nicename',
                'order' => 'ASC',
            );

            global $wpdb;
            $table_name = 'taw_restrict_product';
            $query = $wpdb->prepare("SELECT Type, roleid FROM $table_name WHERE art_no = %s", $artno);
            $RestrictedRules = $wpdb->get_results($query);
            
            $rolesroleids=[];
            $Restrictedusers=[];
    
            for($i=0;$i<count($RestrictedRules);$i++){
                if($RestrictedRules[$i]->Type==='user'){
                    array_push($Restrictedusers,$RestrictedRules[$i]->roleid);
                }else{
                    array_push($rolesroleids,$RestrictedRules[$i]->roleid);
                } 
            } 

            $users = get_users($args);
            $user_list = array();
            $user_Role=false;

            foreach ($users as $user) {

                $customer_no = get_user_meta($user->ID, 'customer_no', true);

                if (empty($customer_no)) {
                    $customer_no = get_user_meta($user->ID, 'subcustomer_no', true);
                }

                if (empty($customer_no)) {
                    $customer_no = "No Customer Number"; // Fallback to user ID if no customer number
                }

                $user_list[] = array(
                    'ID' => $user->ID,
                    'customer_no' => $customer_no,
                    'display_name' => $user->display_name,
                );
            }
            if(count($Restrictedusers)>0){
                for($i=0;$i<count($Restrictedusers);$i++){
                  
                    $temp=$this->useRole($Restrictedusers[$i]);
                    if($temp===$role){
                        $user_Role=true;
                        break;
                    }
                }
             
            }
            $response_data = array(
                'users' => $user_list,
                'restricted_rules' => $RestrictedRules,
                'roles_role_ids' => $rolesroleids,
                'restricted_users' => $Restrictedusers,
                'Role'=>$user_Role
            );

            
            wp_send_json_success($response_data);
        } else {
            wp_send_json_error('Role not set');
        }
    }

    // Method to create the database table
    public static function create_table()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'product_stack_pricing';

        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
                id mediumint(9) NOT NULL AUTO_INCREMENT,
                art_no varchar(255) NOT NULL,
                qty int NOT NULL,
                rule_price float NOT NULL,
                `role` varchar(255) NOT NULL,
                users text NOT NULL,
                modified_by varchar(255) NOT NULL,
                enable_all_users varchar(255) NOT NULL,
                modified_date datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
                PRIMARY KEY  (id)
            ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
    // Handle the AJAX request
    public function psp_get_user_matches()
    {
        check_ajax_referer('psp_search_nonce', 'nonce');

        $search_query = sanitize_text_field($_POST['search_query']);
        if (empty($search_query)) {
            wp_send_json_error('No search query provided');
        }

        $users = get_users(array(
            'search' => '*' . esc_attr($search_query) . '*',
            'search_columns' => array('user_login', 'user_nicename', 'display_name'),
            'number' => 10 // Limit the number of results
        ));

        $results = array();
        foreach ($users as $user) {
            // Fetch user meta values for customer number and subcustomer number
            $customer_no = get_user_meta($user->ID, 'customer_no', true);
            $subcustomer_no = get_user_meta($user->ID, 'subcustomer_no', true);

            // Prepare result data
            $results[] = array(
                'userid' => $user->ID,
                'userName' => $user->display_name,
                'customer_no' => !empty($customer_no) ? $customer_no : '999',
                'subcustomer_no' => !empty($subcustomer_no) ? $subcustomer_no : ''
            );
        }

        wp_send_json_success($results);
    }
    public function psp_delete_product()
    {
        // Check for nonce security
        check_ajax_referer('psp_search_nonce', 'nonce');

        // Get the array of article numbers from the AJAX request
        if (isset($_POST['artnos']) && is_array($_POST['artnos'])) {
            global $wpdb;

            // Sanitize the input array
            $artnos = array_map('sanitize_text_field', $_POST['artnos']);

            // Prepare the placeholders for the query
            $placeholders = implode(',', array_fill(0, count($artnos), '%s'));

            // Construct the SQL query
            $table_name = $wpdb->prefix . 'product_stack_pricing'; // Change this to your actual table name
            $query = "DELETE FROM $table_name WHERE art_no IN ($placeholders)";

            // Execute the query
            $wpdb->query($wpdb->prepare($query, $artnos));

            // Return a success response
            wp_send_json_success('Products deleted successfully.');
        } else {
            // Return an error response if no article numbers are provided
            wp_send_json_error('No article numbers provided.');
        }

        // Always die in functions hooked to AJAX actions
        wp_die();
    }


    // Add this in your plugin file or theme's functions.php

    public function psp_save_product_data()
    {
        global $wpdb;

        // Nonce verification for security
        check_ajax_referer('psp_search_nonce', 'nonce');

        $productArray = $_POST['productArray'];

        foreach ($productArray as $product) {
            $artNo = sanitize_text_field($product['artNo']);
            $prodName = sanitize_text_field($product['prodName']);

            // Check if art number already exists
            $existing_product = $wpdb->get_row($wpdb->prepare(
                "SELECT * FROM {$wpdb->prefix}product_stack_pricing WHERE art_no = %s",
                $artNo
            ));

            if (!$existing_product) {
                // Insert new record if art number does not exist
                $inserted = $wpdb->insert(
                    $wpdb->prefix . 'product_stack_pricing',
                    array(
                        'art_no' => $artNo,
                        'modified_by' => get_current_user_id(), // Example: Set modified by current user
                        'modified_date' => date('Y-m-d H:i:s'),
                    ),
                    array('%s', '%d', '%s')
                );
                if ($inserted === false) {
                    // If the insert failed, send a failure response
                    wp_send_json_error('Failed to save product data');
                    return;
                }
            }
        }

        // Output response (you can customize this as needed)
        wp_send_json_success('Data saved successfully');
    }


    // Method to add submenu page
    public function add_submenu_page()
    {
        add_submenu_page(
            'edit.php?post_type=product', // Parent slug (WooCommerce products)
            'Product Stack Pricing', // Page title
            'Stack Pricing', // Menu title
            'manage_options', // Capability required
            'product-stack-pricing', // Menu slug
            array($this, 'submenu_page_callback') // Callback function
        );
    }

    // Callback function for submenu page
    public function submenu_page_callback()
    {
?>
        <div class="wrap">
            <h1>Product Stack Pricing Import & Export</h1>

            <!-- Export Form -->
            <form method="post" style="display: inline-block; margin-right: 15px;">
                <input type="submit" name="export_file" value="Export Data" class="button button-primary">
            </form>

            <!-- Import Form -->
            <form id="psp-import-form" enctype="multipart/form-data" style="display: inline-block;">
                <button type="button" id="toggle-import-btn" class="button button-primary">Import Data</button>

                <div id="import-file-container" class="hidden" style="margin-top: 10px;">
                    <input type="file" id="import-file-input" accept=".xlsx,.xls" required>
                    <button type="submit" class="button">Upload & Start Import</button>
                </div>
            </form>

            <!-- Progress Bar -->
            <div id="import-progress-container" style="margin-top: 20px; width: 100%; background: #f3f3f3; border-radius: 3px; overflow: hidden; height: 24px; display: none;">
                <div id="import-progress-bar" style="width:0%; height:100%; background-color:#007cba; text-align:center; color:#fff; line-height:24px;">
                    0%
                </div>
            </div>

            <div id="import-log" style="margin-top: 20px;"></div>
        </div>

            <script type="text/javascript">
            jQuery(document).ready(function($) {
                $('#toggle-import-btn').click(function() {
                    $('#import-file-container').toggleClass('hidden');
                });

                $('#psp-import-form').submit(function(e) {
                    e.preventDefault();

                    let file = $('#import-file-input').prop('files')[0];
                    if (!file) {
                        alert('Please select a file to import.');
                        return;
                    }

                    let formData = new FormData();
                    formData.append('file', file);
                    formData.append('action', 'psp_start_import');

                    $('#import-progress-container').show();
                    $('#import-progress-bar').css('width', '0%').text('0%');
                    $('#import-log').html('');

                    $.ajax({
                        url: ajaxurl,
                        type: 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            if (response.success) {
                                processBatch(response.data.batch_id, 1, response.data.total_batches);
                    } else {
                                $('#import-log').html('<div style="color:red;">' + response.data + '</div>');
                            }
                        },
                        error: function() {
                            $('#import-log').html('<div style="color:red;">AJAX request failed.</div>');
                        }
                    });
                });

                function processBatch(batch_id, batch_number, total_batches) {
                    $.post(ajaxurl, {
                        action: 'psp_process_batch',
                        batch_id: batch_id,
                        batch_number: batch_number
                    }, function(response) {
                        if (response.success) {
                            let percent = Math.round((batch_number / total_batches) * 100);
                            $('#import-progress-bar').css('width', percent + '%').text(percent + '%');

                            $('#import-log').append('<div>' + response.data + '</div>');

                            if (batch_number < total_batches) {
                                processBatch(batch_id, batch_number + 1, total_batches);
                            } else {
                                $('#import-log').append('<div style="color:green;font-weight:bold;">Import Completed Successfully!</div>');
                            }
                        } else {
                            $('#import-log').append('<div style="color:red;">' + response.data + '</div>');
                        }
                    });
                    }
                });
            </script>

        <style>
            .hidden {
                display: none;
            }
        </style>

<?php


        if (isset($_POST['export_file'])) {
            Product_Stack_Pricing_Import_Export::export_data();
        }

        include PRODUCT_STACK_PRICING_PATH . 'template/stack-pricing-admin.php';
    }

    public function psp_process_batch_handler() {
        $batch_id = sanitize_text_field($_POST['batch_id']);
        $batch_number = intval($_POST['batch_number']);
        $r = [];
        $all_data = get_transient($batch_id);
        if (!$all_data) wp_send_json_error('Batch data expired.');
    
        $batch_size = 50;
        $offset = ($batch_number - 1) * $batch_size;
        $batch_data = array_slice($all_data, $offset, $batch_size);
    
        foreach ($batch_data as $row) {
            $res = Product_Stack_Pricing_Import_Export::import_single_row($row);
            if($res){
                $r[] = $res;
            }
        }
    
        wp_send_json_success($r);
    }

    public function psp_start_import_handler() {
        if (empty($_FILES['file'])) {
            wp_send_json_error('No file uploaded.');
        }
    
        $uploaded = $_FILES['file'];
        $upload_overrides = ['test_form' => false];
        $movefile = wp_handle_upload($uploaded, $upload_overrides);
    
        if (isset($movefile['error'])) {
            wp_send_json_error($movefile['error']);
        }
    
        require_once PRODUCT_STACK_PRICING_PATH . 'vendor/autoload.php';
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($movefile['file']);
        $data = $spreadsheet->getActiveSheet()->toArray();
    
        $filtered_data = array_filter($data, fn($row) => array_filter($row));
        array_shift($filtered_data); // Remove header
    
        $batch_size = 50; // Customize as needed
        $total_batches = ceil(count($filtered_data) / $batch_size);
    
        $batch_id = 'psp_import_' . time();
        set_transient($batch_id, $filtered_data, HOUR_IN_SECONDS);
    
        global $wpdb;
        $wpdb->query("TRUNCATE TABLE {$wpdb->prefix}product_stack_pricing");
    
        wp_send_json_success([
            'batch_id' => $batch_id,
            'total_batches' => $total_batches
        ]);
    }


    public function include_custom_psp_template_after_description() {
        // Adjust the path if needed
        $is_logined=is_user_logged_in() || current_user_can('c_uam_cap_price') || current_user_can('c_uam_cap_reseller_price');
        if($is_logined){
            include PRODUCT_STACK_PRICING_PATH . 'template/psp-front-single-page.php';
        }
    }
    public function psp_refresh_EditAddRulePopup()
    {
        check_ajax_referer('psp_search_nonce', 'nonce');

        $id = isset($_POST['id']) ? sanitize_text_field($_POST['id']) : '';
        $artNo = isset($_POST['artno']) ? sanitize_text_field($_POST['artno']) : '';

        // Fetch data if id is provided
        $rule = null;
        if (!empty($id)) {
            global $wpdb;
            $table_name = $wpdb->prefix . 'product_stack_pricing';
            $rule = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $id), ARRAY_A);
        }

        ob_start();
        include PRODUCT_STACK_PRICING_PATH . 'template/psp-add-edit-rule-popup.php';
        $content = ob_get_clean();

        // Inject fetched data into the popup content
        $data = array(
            'content' => $content,
            'rule' => $rule
        );

        wp_send_json_success($data);
    }


    public function psp_refresh_product_list()
    {
        check_ajax_referer('psp_search_nonce', 'nonce');

        ob_start();
        include PRODUCT_STACK_PRICING_PATH . 'template/psp-added-product-container.php';
        $content = ob_get_clean();

        wp_send_json_success($content);
    }


    public function psp_refresh_rule_list()
    {
        check_ajax_referer('psp_search_nonce', 'nonce');

        $artNo = isset($_POST['artNo']) ? sanitize_text_field($_POST['artNo']) : '';
        $status = isset($_POST['status']) ? sanitize_text_field($_POST['status']) : '';
        ob_start();
        include PRODUCT_STACK_PRICING_PATH . 'template/psp-added-rule-container.php';
        $content = ob_get_clean();

        wp_send_json_success($content);
    }


    // Method to check if WooCommerce is active
    public function plugin_activation_check()
    {
        if (!is_plugin_active('woocommerce/woocommerce.php')) {
            // Deactivate the plugin if WooCommerce is not active
            deactivate_plugins(plugin_basename(__FILE__));
            wp_die('Product Stack Pricing requires WooCommerce to be activated. Please activate WooCommerce first.');
        }
    }
    // Function to fetch product data by artNo
    function psp_fetch_product_data()
    {
        check_ajax_referer('psp_search_nonce', 'nonce');

        global $wpdb;
        $table_name = $wpdb->prefix . 'product_stack_pricing';

        $artNo = sanitize_text_field($_POST['artNo']);

        $product_data = $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM $table_name WHERE art_no = %s",
            $artNo
        ), ARRAY_A);

        wp_send_json_success($product_data);
    }

    // Inside your class or in functions.php
    public function psp_delete_stacking_rule()
    {
        check_ajax_referer('psp_search_nonce', 'nonce');

        global $wpdb;
        $table_name = $wpdb->prefix . 'product_stack_pricing';
        $artno = isset($_POST['artno']) ? sanitize_text_field($_POST['artno']) : '';
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;

        if ($id <= 0) {
            wp_send_json_error('Invalid rule ID.');
        }

        $current_user_id = get_current_user_id();
        $current_date = current_time('mysql');

        // Check how many rows exist for the given artno
        $row_count = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT COUNT(*) FROM $table_name WHERE art_no = %s",
                $artno
            )
        );

        // If only one row exists for the given artno, update the fields instead of deleting
        if ($row_count == 1) {
            $updated = $wpdb->update(
                $table_name,
                array(
                    'qty' => 0,
                    'rule_price' => 0,
                    'users' => '',
                    'modified_by' => $current_user_id,
                    'modified_date' => $current_date
                ),
                array('id' => $id),
                array('%d', '%f', '%s', '%d', '%s'),
                array('%d')
            );

            if ($updated !== false) {
                wp_send_json_success('Rule updated successfully.');
            } else {
                wp_send_json_error('Failed to update the rule.');
            }
        } else {
            // Proceed to delete the row if there are more than one rows for the given artno
            $deleted = $wpdb->delete(
                $table_name,
                array('id' => $id),
                array('%d')
            );

            if ($deleted !== false) {
                $wpdb->update(
                    $table_name,
                    array(
                        'modified_by' => $current_user_id,
                        'modified_date' => $current_date
                    ),
                    array('id' => $id),
                    array('%d', '%s'),
                    array('%d')
                );
                wp_send_json_success('Rule deleted successfully.');
            } else {
                wp_send_json_error('Failed to delete the rule.');
            }
        }

        wp_die();
    }

    // public function psp_get_restict_table_data($artNo,$role,$users){
    //     global $wpdb;
    //     $table_name='';

    //     $restrict_Table_Rules = $wpdb->get_results(
    //         $wpdb->prepare("SELECT * FROM $table_name WHERE art_no = %s", $artNo),
    //         ARRAY_A
    //     );

    //     if($restrict_Table_Rules){

    //     }else{
    //         return true;
    //     }
    // }

    public function psp_save_stacking_rule()
    {
        check_ajax_referer('psp_search_nonce', 'nonce');

        global $wpdb;
        $table_name = $wpdb->prefix . 'product_stack_pricing';

        // Retrieve POST data
        $artNo = isset($_POST['artNo']) ? sanitize_text_field($_POST['artNo']) : '';
        $qty = isset($_POST['qty']) ? intval($_POST['qty']) : 0;
        $price = isset($_POST['price']) ? floatval($_POST['price']) : 0.0;
        $users = isset($_POST['users']) ? array_map('strval', $_POST['users']) : array();
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        $role = isset($_POST['role']) ? sanitize_text_field($_POST['role']) : '';
        $enable_all_users = isset($_POST['selectAllCheckboxval']) ? sanitize_text_field($_POST['selectAllCheckboxval']) : 0;
        $status = isset($_POST['status']) && $_POST['status'] === 'true' ? 1 : 0;



        // Validate input
        if (empty($artNo) || empty($qty) || empty($price) || empty($role) || (empty($users) && $enable_all_users == 0)) {
            wp_send_json_error('Please provide all required fields.');
        }

        // if($status){

        // }


        foreach ($users as $customer_no) {
            // Check if customer_no exists as user meta key 'customer_no'
            $user = get_users(array(
                'meta_key' => 'customer_no',
                'meta_value' => $customer_no,
                'number' => 1,
                'count_total' => false,
                'fields' => 'ID',
            ));

            if (empty($user)) {
                // If not found, check as 'subcustomer_no'
                $user = get_users(array(
                    'meta_key' => 'subcustomer_no',
                    'meta_value' => $customer_no,
                    'number' => 1,
                    'count_total' => false,
                    'fields' => 'ID',
                ));
            }

            if (empty($user)) {
                // If still not found, assume $customer_no is actually the user ID
                $user_id = $customer_no;
            } else {
                $user_id = $user[0]; // Use the first user ID found
            }

            $users[] = $user_id;
        }

        $users = array_unique($users);
        // Check if the rule already exists
        $existing_rule = $wpdb->get_row(
            $wpdb->prepare("SELECT * FROM $table_name WHERE art_no = %s AND qty = %d AND rule_price = %f AND role = %s AND users = %s", $artNo, $qty, $price, $role, implode(',', $users)),
            ARRAY_A
        );


        $existing_rule_id = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT id FROM $table_name WHERE art_no = %s AND qty = 0 AND rule_price = 0",
                $artNo
            ),
            ARRAY_A
        );

        if ($existing_rule_id && !$id) {
            $id = $existing_rule_id['id'];
        }


        // Check for existing rules with the same artNo, qty, and role, but not necessarily the same price
        if ($id) {
            $rules_with_same_qty = $wpdb->get_results(
                $wpdb->prepare("SELECT * FROM $table_name WHERE art_no = %s AND qty = %d AND role = %s AND id != %d", $artNo, $qty, $role, $id),
                ARRAY_A
            );
        } else {
            $rules_with_same_qty = $wpdb->get_results(
                $wpdb->prepare("SELECT * FROM $table_name WHERE art_no = %s AND qty = %d AND role = %s", $artNo, $qty, $role),
                ARRAY_A
            );
        }

        // Check for overlapping users
        $overlapping_users = false;
        foreach ($rules_with_same_qty as $rule) {
            $rule_users = explode(',', $rule['users']);
            if (array_intersect($users, $rule_users)) {
                $overlapping_users = true;
                break;
            }
        }

        // Determine whether to update or insert

        if ($existing_rule && $id == 0) {
            wp_send_json_error('Rule already exists with the same details.');
        } elseif ($overlapping_users) {
            wp_send_json_error('A rule with the same quantity already exists for one or more users.');
        } else {
            // Check if a rule exists with the same artNo, qty, and role but different price

            if ($id) {
                $rule_with_same_qty_and_role = $wpdb->get_row(
                    $wpdb->prepare(
                        "SELECT * FROM $table_name WHERE art_no = %s AND qty = %d AND role = %s AND rule_price = %f AND id != %d",
                        $artNo,
                        $qty,
                        $role,
                        $price,
                        $id
                    ),
                    ARRAY_A
                );
            } else {
                $rule_with_same_qty_and_role = $wpdb->get_row(
                    $wpdb->prepare(
                        "SELECT * FROM $table_name WHERE art_no = %s AND qty = %d AND role = %s AND rule_price = %f",
                        $artNo,
                        $qty,
                        $role,
                        $price
                    ),
                    ARRAY_A
                );
            }

            if ($rule_with_same_qty_and_role) {
                wp_send_json_error('A rule with the same artNo, quantity, and role but different price already exists.');
            }
        }

        $data = array(
            'art_no' => $artNo,
            'qty' => $qty,
            'rule_price' => $price,
            'role' => $role,
            'enable_all_users' => $enable_all_users,
            'users' => implode(',', $users),
            'modified_by' => get_current_user_id(),
            'modified_date' => date('Y-m-d H:i:s'),
            'status'=>$status
        );

        if ($id > 0) {
            // Update existing rule
            $updated = $wpdb->update(
                $table_name,
                $data,
                array('id' => $id),
                array(
                    '%s', // art_no
                    '%d', // qty
                    '%f', // rule_price
                    '%s', // role
                    '%s', // enable_all_user
                    '%s', // users
                    '%d', // modified_by
                    '%s',  // modified_date
                    '%d'
                ),
                array('%d') // Where clause format
            );

            if ($updated !== false) {
                wp_send_json_success('Rule updated successfully.');
            } else {
                $error_message = $wpdb->last_error;
                wp_send_json_error($error_message);
            }
        } else {
            // Insert new rule
            $inserted = $wpdb->insert(
                $table_name,
                $data,
                array(
                    '%s', // art_no
                    '%d', // qty
                    '%f', // rule_price
                    '%s', // role
                    '%s', // enable_all_user
                    '%s', // users
                    '%d', // modified_by
                    '%s'  // modified_date
                )
            );

            if ($inserted !== false) {
                wp_send_json_success('Rule saved successfully.');
            } else {
                $error_message = $wpdb->last_error;
                wp_send_json_error('Failed to save the rule.' . $error_message);
            }
        }

        wp_die();
    }



    public function get_existing_skus()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'product_stack_pricing';
        $existing_skus = $wpdb->get_col("SELECT DISTINCT art_no FROM $table_name");
        return $existing_skus;
    }



    public function psp_get_product_matches_skus()
    {
        // Check nonce for security
        check_ajax_referer('psp_search_nonce', 'nonce');

        if (isset($_POST['search_query'])) {
            $search_query = sanitize_text_field($_POST['search_query']);

            // Get existing SKUs from product_stack_pricing table
            $existing_skus = $this->get_existing_skus();

            // Query WooCommerce for products with SKU containing the search query
            $args = array(
                'post_type' => 'product',
                'posts_per_page' => 5,
                'meta_query' => array(
                    array(
                        'key' => '_sku',
                        'value' => $search_query,
                        'compare' => 'LIKE'
                    )
                )
            );

            $query = new WP_Query($args);

            $results = array();

            if ($query->have_posts()) {
                while ($query->have_posts()) {
                    $query->the_post();
                    global $product;
                    $sku = $product->get_sku();

                    // Only add to results if SKU is not in the existing SKUs array
                    if (!in_array($sku, $existing_skus)) {
                        $results[] = array(
                            'artno' => $sku,
                            'prodname' => $product->get_name()
                        );
                    }
                }
            }

            wp_reset_postdata();

            // Return results as JSON
            wp_send_json($results);
        }

        wp_die();
    }


    public function enqueue_frontend_scripts() {
        // Only load the script on the product page (you can adjust this condition as needed)
        if (is_product()) {
            
            wp_enqueue_script('product-stack-pricing-front-js', PRODUCT_STACK_PRICING_URL . 'js/psp-front-script.js', array('jquery'), '1.0', true);
    
            // Localize script for AJAX URL
            wp_localize_script('product-stack-pricing-front-js', 'psp_ajax_obj', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'psp_search_nonce' => wp_create_nonce('psp_search_nonce'),
                'plugin_url' => PRODUCT_STACK_PRICING_URL
            ));
        }
    }
    
    // Enqueue admin scripts and styles
    public function enqueue_admin_scripts($hook)
    {
        
        // Check if we are on the correct admin page
        if ($hook != 'product_page_product-stack-pricing') {
            return;
        }
        wp_enqueue_script('jquery-ui-tabs');

        wp_enqueue_style('product-stack-pricing-css', PRODUCT_STACK_PRICING_URL . 'css/psp-style.css');

        wp_enqueue_script('product-stack-pricing-js', PRODUCT_STACK_PRICING_URL . 'js/psp-script.js', array('jquery'), '1.0', true);
        

        // Localize script for AJAX URL
        wp_localize_script('product-stack-pricing-js', 'psp_ajax_obj', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'psp_search_nonce' => wp_create_nonce('psp_search_nonce'),
            'plugin_url' => PRODUCT_STACK_PRICING_URL
        ));
    }
}

// Initialize the plugin
new Product_Stack_Pricing();
