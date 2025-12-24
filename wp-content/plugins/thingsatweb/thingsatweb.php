<?php
/*
Plugin Name: Thingsatweb
Plugin URI: https://thingsatweb.com
Description: Customized prodcuts.
Version: 1.0
Author: thingsatweb
Author URI: https://thingsatweb.com
Text Domain: thingsatweb
Domain Path: /lang
*/

define('THINGSATWEB_BASE', plugin_dir_url(__FILE__));
define('THINGSATWEB_DIR', __DIR__);
define('TAW_API_CREDENTIALS', "taw_api_crendials");
define('TAW_TEXT_DOMAIN', "thingsatweb");
define('TAW_FILE_VERSION', "?v=8.1");
define('TAW_ROLE_B2B', "custom_uam_b2b");
define('TAW_ROLE_RESELLER_SEK', "custom_uam_reseller_sek");
define('TAW_ROLE_RESELLER_EUR', "custom_uam_reseller_eur");



function taw_loadMyscript()
{

    // if (is_page(array('product','shop'))) {       
    wp_enqueue_style('tailwind-css', THINGSATWEB_BASE . 'css/tailwind.css' . TAW_FILE_VERSION);
    wp_enqueue_style('product-css', THINGSATWEB_BASE . 'css/product.css' . TAW_FILE_VERSION);
    wp_enqueue_style('slider-css', THINGSATWEB_BASE . 'css/slider.css' . TAW_FILE_VERSION);
    wp_enqueue_script('product-js', THINGSATWEB_BASE . 'js/product.js' . TAW_FILE_VERSION, ['jquery', 'jquery-migrate'], null, true);
    wp_enqueue_script('slider-js', THINGSATWEB_BASE . 'js/slider.js' . TAW_FILE_VERSION, ['jquery', 'jquery-migrate'], null, true);
    // }
}

function taw_loadMyscriptAdmin()
{
    wp_enqueue_media();
    wp_enqueue_style('backend-taw-css', THINGSATWEB_BASE . 'css/backend.css' . TAW_FILE_VERSION);
    wp_enqueue_script('taw-backend-js', THINGSATWEB_BASE . 'js/backend.js' . TAW_FILE_VERSION, ['jquery', 'jquery-migrate'], null, true);
    wp_enqueue_script('taw-autocomplete-js', THINGSATWEB_BASE . 'js/jquery.autocomplete.min.js', ['jquery', 'jquery-migrate'], null, true);
}

add_action('wp_enqueue_scripts', 'taw_loadMyscript');
add_action('admin_enqueue_scripts', 'taw_loadMyscriptAdmin');


add_action('admin_menu', 'my_plugin_menu');
function my_plugin_menu()
{
    add_menu_page("Product Customise", "Product Config", "manage_options", "product_customise", "managePriceList", "dashicons-screenoptions", 10);
    add_submenu_page("product_customise", 'Article Price', 'Article Price', 'manage_options', 'manage_price_list', 'managePriceList');
    add_submenu_page("product_customise", "Article Category", "Article Category", "manage_options", "getArticleCategoryList", "getArticleCategoryList");
    add_submenu_page("product_customise", "Article Attributes", "Article Attributes", "manage_options", "getArticleAttributeList", "getArticleAttributeList");
    add_submenu_page("product_customise", 'Customer Unique Price', 'Customer Unique Price', 'manage_options', 'getCustomerUniquePriceList', 'getCustomerUniquePriceList');
    add_submenu_page("product_customise", 'Article Picture', 'Article Picture', 'manage_options', 'getArticlePictureList', 'getArticlePictureList');
    add_submenu_page("product_customise", 'Title Description', 'Title Description', 'manage_options', 'manage_title_desc_list', 'manageTitleDescList');
    add_submenu_page("product_customise", 'Article Accessories', 'Article Accessories', 'manage_options', 'getAccessoriesList', 'getAccessoriesList');
    // add_submenu_page("product_customise", 'Article SparePicture', 'Article SparePicture', 'manage_options', 'getSparePictureList', 'getSparePictureList');
    add_submenu_page("product_customise", 'Article Diagram', 'Article Diagram', 'manage_options', 'getDiagramList', 'getDiagramList');
    add_submenu_page("product_customise", 'Article SpareParts', 'Article SpareParts', 'manage_options', 'getsparepartsList', 'getsparepartsList');


    // add_submenu_page("product_customise", 'Shipping', 'Shipping', 'manage_options', 'thingsatweb_shipping');
    add_submenu_page("product_customise", "Imports", "Imports", "manage_options", "product_imports", "productImportPage");
    add_submenu_page("product_customise", "Exports", "Exports", "manage_options", "product_exports", "productExportPage");
    add_submenu_page("product_customise", "Sync To Product", "Sync To Product", "manage_options", "syncToProduct", "syncToProduct");
    add_submenu_page("product_customise", 'Restrict Products', 'Restrict Products', 'manage_options', 'restrictproducts', 'restrictproducts');
    add_submenu_page("product_customise", 'Visibility Setting', 'Visibility Setting', 'manage_options', 'visibilitySetting', 'visibilitySetting');

    // add_submenu_page("product_customise", "Sync To Woocommerce", "Sync To Woocommerce", "manage_options", "syncToWoocommerce", "syncToWoocommerce");
}
function custom_rewrite_rule()
{

    // add_rewrite_tag('%id%', '([^&]+)');
    // add_rewrite_tag('%type%', '([^&]+)');
    // //add_rewrite_rule('^downloads/([^/]*)/?', 'index.php?pagename=downloads&id=$matches[1]&type=$matches[2]', 'top');
    // add_rewrite_rule('^downloads/([^/]+)/?$', 'index.php?pagename=downloads&id=$matches[1]&type=$matches[2]', 'top');
    add_rewrite_tag("%id%", "([a-z0-9\-_]+)");
    add_rewrite_tag("%type%", "([a-z0-9\-_]+)");
    add_rewrite_rule('^downloads/([a-z0-9\-_]+)/?$', 'index.php?pagename=downloads&id=$matches[1]&type=$matches[2]', 'top');
   
    add_rewrite_tag('%id%', '([^&]+)');
    add_rewrite_tag('%type%', '([^&]+)');
    add_rewrite_rule('^nedladdningar/([^/]*)/?', 'index.php?pagename=nedladdningar&id=$matches[1]&type=$matches[2]', 'top');
    
}

add_action('init', 'custom_rewrite_rule', 10, 0);

function syncToProduct()
{

    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }

    include_once(__DIR__ . '/template/page-sync-product.php');
}

function restrictproducts()
{

    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }

    include_once(__DIR__ . '/template/restrict-products.php');
}
function visibilitySetting()
{

    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }

    include_once(__DIR__ . '/template/restrict-category.php');
}
function syncToWoocommerce()
{

    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }

    include_once(__DIR__ . '/template/page-sync-woocommerce.php');
}
function productImportPage()
{
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }

    include_once(__DIR__ . '/template/page-import.php');
}

function productExportPage()
{
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }

    include_once(__DIR__ . '/template/page-export.php');
}

function getArticlePictureList()
{
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }

    include_once(__DIR__ . '/template/page-article-picture-list.php');
}

function getAccessoriesList()
{
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }

    include_once(__DIR__ . '/template/page-article-accessories-list.php');
}

function getSparePictureList()
{
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }

    include_once(__DIR__ . '/template/page-article-sparepicture-list.php');
}

function getDiagramList()
{
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }

    include_once(__DIR__ . '/template/page-article-diagram-list.php');
}

function getsparepartsList()
{
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }

    include_once(__DIR__ . '/template/page-article-sparepicture-list.php');
}



function getCustomerUniquePriceList()
{
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }

    include_once(__DIR__ . '/template/page-customer-unique-price-list.php');
}

function getArticleAttributeList()
{
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }

    include_once(__DIR__ . '/template/page-article-attribute-list.php');
}


function getArticleCategoryList()
{

    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }

    include_once(__DIR__ . '/template/page-article-category-list.php');
}

function managePriceList()
{

    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }

    include_once(__DIR__ . '/template/page-article-price-list.php');
}

function manageTitleDescList()
{

    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }

    include_once(__DIR__ . '/template/page-article-title-list.php');
    // include_once(__DIR__ . '/template/test.php');
}


function getBaseIdByFamilyOption($wpdb, $family_id, $option_id)
{
    $query = "SELECT id FROM `taw_product_base` where family_id='$family_id' and material_id='$option_id' limit 1";
    $id = intval($wpdb->get_var($query));
    if ($id > 0) {
        return $id;
    }
    return 0;
}

function checkMediaFile($wpdb, $filename)
{
    $filename = strtolower(pathinfo($filename)['filename']);
    
    $query = $wpdb->prepare(
        "SELECT p.ID FROM {$wpdb->posts} p
          JOIN {$wpdb->postmeta} pm ON p.ID = pm.post_id
          WHERE p.post_type='attachment' AND pm.meta_key='_wp_attached_file' AND pm.meta_value LIKE %s
        LIMIT 1",
        '%' . $filename . '%'
    );  

    $id = intval($wpdb->get_var($query));

    if ($id > 0) {
        return $id;
    }
    return 0;
}

function uploadImgToMedia($wpdb, $filename, $image_data)
{

    $id = checkMediaFile($wpdb, $filename);

    if ($id > 0) {
        return $id;
    }

    $upload_dir = wp_upload_dir();

    if (wp_mkdir_p($upload_dir['path'])) {
        $file = $upload_dir['path'] . '/' . $filename;
    } else {
        $file = $upload_dir['basedir'] . '/' . $filename;
    }

    file_put_contents($file, $image_data);

    $wp_filetype = wp_check_filetype($filename, null);

    $file_title = pathinfo($filename)['filename'];
    $attachment = array(
        'post_mime_type' => $wp_filetype['type'],
        'post_title' => $file_title,
        'post_content' => '',
        'post_status' => 'inherit'
    );

    $attach_id = wp_insert_attachment($attachment, $file);
    require_once(ABSPATH . 'wp-admin/includes/image.php');
    $attach_data = wp_generate_attachment_metadata($attach_id, $file);
    wp_update_attachment_metadata($attach_id, $attach_data);

    return $attach_id;
}




function getCredentials()
{
    $credential = get_option(TAW_API_CREDENTIALS);

    if (empty($credential)) {
        $credential = "jdlkfjdlijfdfd987687df9dg=-35lkj";
        add_option(TAW_API_CREDENTIALS, $credential);
    }
    return $credential;
}


function taw_delete_article_price()
{
    $data = array_merge((array) $_GET, (array) $_POST);
    $id = isset($data['id']) ? $data['id'] : "";

    global $wpdb;

    $query = "";
    if (!empty($id)) {
        $query = "DELETE FROM `taw_article_price` WHERE `taw_article_price`.`id` = $id";
    }
    $wpdb->get_results($query);
}
add_action("wp_ajax_taw_delete_article_price", 'taw_delete_article_price');

function taw_save_article_price()
{
    $id = '';
    if (isset($_POST['id'])) {
        $id = sanitize_text_field($_POST['id']);
    }
    $art_no = '';
    if (isset($_POST['art_no'])) {
        $art_no = sanitize_text_field($_POST['art_no']);
    }
    $price_b2b = '';
    if (isset($_POST['price_b2b'])) {
        $price_b2b = sanitize_text_field($_POST['price_b2b']);
    }
    $price_reseller_sek = '';
    if (isset($_POST['price_reseller_sek'])) {
        $price_reseller_sek = sanitize_text_field($_POST['price_reseller_sek']);
    }
    $price_reseller_eur = '';
    if (isset($_POST['price_reseller_eur'])) {
        $price_reseller_eur = sanitize_text_field($_POST['price_reseller_eur']);
    }
    global $wpdb;
    $exRec = $wpdb->get_var("SELECT id FROM `taw_article_price` where art_no='$art_no'");

    $query = "";
    if (!empty($id)) {
        if (!empty($exRec) && $exRec != $id) {
            wp_send_json_success(['status' => 'Article no already exist']);
            exit;
        }
        $query = "UPDATE `taw_article_price` SET `price_b2b` = '$price_b2b',`price_reseller_eur` = '$price_reseller_eur',
        `price_reseller_sek` = '$price_reseller_sek' WHERE `taw_article_price`.`id` = $id;";
    } else {
        if (!empty($exRec)) {
            wp_send_json_success(['status' => 'Article no already exist']);
            exit;
        }

        $query = "INSERT INTO `taw_article_price` (`art_no`,`price_b2b`,`price_reseller_eur`,`price_reseller_sek`) 
        VALUES ('$art_no','$price_b2b','$price_reseller_eur','$price_reseller_sek');";
    }

    $result = $wpdb->query($query);

    if ($result) {
        wp_send_json_success(['status' => 'Data inserted successfully.']);
    } else {
        wp_send_json_error(['status' => 'Error inserting data.']);
    }
    wp_send_json_success(['status' => '1']);
}
add_action("wp_ajax_taw_save_article_price", 'taw_save_article_price');

function taw_delete_article_attribute()
{
    $data = array_merge((array) $_GET, (array) $_POST);
    $id = isset($data['id']) ? $data['id'] : "";

    global $wpdb;

    $query = "";
    if (!empty($id)) {
        $query = "DELETE FROM `taw_article_attributes` WHERE `taw_article_attributes`.`id` = $id";
    }
    $wpdb->get_results($query);
}
add_action("wp_ajax_taw_delete_article_attribute", 'taw_delete_article_attribute');

function taw_save_article_attribute()
{
    $id = '';
    if (isset($_POST['id'])) {
        $id = sanitize_text_field($_POST['id']);
    }
    $art_no = '';
    if (isset($_POST['art_no'])) {
        $art_no = sanitize_text_field($_POST['art_no']);
    }
    $attr_id = '';
    if (isset($_POST['attr_id'])) {
        $attr_id = sanitize_text_field($_POST['attr_id']);
    }
    $term_ids = '';
    if (isset($_POST['term_ids'])) {
        $term_ids = sanitize_text_field($_POST['term_ids']);
    }
    global $wpdb;
    $exRec = $wpdb->get_var("SELECT id FROM `taw_article_attributes` where art_no='$art_no' and attr_id='$attr_id'");

    $query = "";
    if (!empty($id)) {
        if (!empty($exRec) && $exRec != $id) {
            wp_send_json_success(['status' => 'Article no already exist']);
            exit;
        }
        $query = "UPDATE `taw_article_attributes` SET `attr_id` = '$attr_id',`term_ids` = '$term_ids'
        WHERE `taw_article_attributes`.`id` = $id;";
    } else {
        if (!empty($exRec)) {
            wp_send_json_success(['status' => 'Article no already exist']);
            exit;
        }

        $query = "INSERT INTO `taw_article_attributes` (`art_no`,`attr_id`,`term_ids`) 
        VALUES ('$art_no','$attr_id','$term_ids');";
    }

    $result = $wpdb->query($query);

    if ($result) {
        wp_send_json_success(['status' => 'Data inserted successfully.']);
    } else {
        wp_send_json_error(['status' => 'Error inserting data.']);
    }
    wp_send_json_success(['status' => '1']);
}
add_action("wp_ajax_taw_save_article_attribute", 'taw_save_article_attribute');

function taw_delete_article_accessories()
{
    $data = array_merge((array) $_GET, (array) $_POST);
    $id = isset($data['id']) ? $data['id'] : "";

    global $wpdb;

    $query = "";
    if (!empty($id)) {
        $query = "DELETE FROM `taw_product_accessories` WHERE `taw_product_accessories`.`id` = $id";
    }
    $wpdb->get_results($query);
}
add_action("wp_ajax_taw_delete_article_accessories", 'taw_delete_article_accessories');
function taw_save_article_accessories()
{
    $id = '';
    if (isset($_POST['id'])) {
        $id = sanitize_text_field($_POST['id']);
    }
    $parent_article = '';
    if (isset($_POST['parent_article'])) {
        $parent_article = sanitize_text_field($_POST['parent_article']);
    }
    $acs_article = '';
    if (isset($_POST['acs_article'])) {
        $acs_article = sanitize_text_field($_POST['acs_article']);
    }
    $no_plates = '';
    if (isset($_POST['no_plates'])) {
        $no_plates = sanitize_text_field($_POST['no_plates']);
    }

    global $wpdb;
    $exRec = $wpdb->get_var("SELECT id FROM `taw_product_accessories` where id='$id'");

    $query = "";
    if (!empty($id)) {
        if (!empty($exRec) && $exRec != $id) {
            wp_send_json_success(['status' => 'Article no already exist']);
            exit;
        }
        $query = "UPDATE `taw_product_accessories` SET `parent_article` = '$parent_article',`acs_article` = '$acs_article',`no_plates` = '$no_plates'
        WHERE `taw_product_accessories`.`id` = $id;";
    } else {
        if (!empty($exRec)) {
            wp_send_json_success(['status' => 'Article no already exist']);
            exit;
        }

        $query = "INSERT INTO `taw_product_accessories` (`parent_article`,`acs_article`,`no_plates`) 
        VALUES ('$parent_article','$acs_article','$no_plates');";
    }

    $result = $wpdb->query($query);

    if ($result) {
        wp_send_json_success(['status' => 'Data inserted successfully.']);
    } else {
        wp_send_json_error(['status' => 'Error inserting data.']);
    }
    wp_send_json_success(['status' => '1']);
}
add_action("wp_ajax_taw_save_article_accessories", 'taw_save_article_accessories');

function taw_delete_article_spareparts()
{
    $data = array_merge((array) $_GET, (array) $_POST);
    $id = isset($data['id']) ? $data['id'] : "";

    global $wpdb;

    $query = "";
    if (!empty($id)) {
        $query = "DELETE FROM `taw_product_spareparts` WHERE `taw_product_spareparts`.`id` = $id";
    }
    $wpdb->get_results($query);
}
add_action("wp_ajax_taw_delete_article_spareparts", 'taw_delete_article_spareparts');
function taw_save_article_spareparts()
{
    $id = '';
    if (isset($_POST['id'])) {
        $id = sanitize_text_field($_POST['id']);
    }
    $parent_article = '';
    if (isset($_POST['parent_article'])) {
        $parent_article = sanitize_text_field($_POST['parent_article']);
    }
    $spare_article = '';
    if (isset($_POST['spare_article'])) {
        $spare_article = sanitize_text_field($_POST['spare_article']);
    }
    $min_qty = '';
    if (isset($_POST['min_qty'])) {
        $min_qty = sanitize_text_field($_POST['min_qty']);
    }

    global $wpdb;
    $exRec = $wpdb->get_var("SELECT id FROM `taw_product_spareparts` where id='$id'");

    $query = "";
    if (!empty($id)) {
        if (!empty($exRec) && $exRec != $id) {
            wp_send_json_success(['status' => 'Article no already exist']);
            exit;
        }
        $query = "UPDATE `taw_product_spareparts` SET `parent_article` = '$parent_article',`spare_article` = '$spare_article',`min_qty` = '$min_qty'
        WHERE `taw_product_spareparts`.`id` = $id;";
    } else {
        if (!empty($exRec)) {
            wp_send_json_success(['status' => 'Article no already exist']);
            exit;
        }

        $query = "INSERT INTO `taw_product_spareparts` (`parent_article`,`spare_article`,`min_qty`) 
        VALUES ('$parent_article','$spare_article','$min_qty');";
    }

    $result = $wpdb->query($query);

    if ($result) {
        wp_send_json_success(['status' => 'Data inserted successfully.']);
    } else {
        wp_send_json_error(['status' => 'Error inserting data.']);
    }
    wp_send_json_success(['status' => '1']);
}
add_action("wp_ajax_taw_save_article_spareparts", 'taw_save_article_spareparts');

function taw_delete_article_customerprice()
{
    $data = array_merge((array) $_GET, (array) $_POST);
    $id = isset($data['id']) ? $data['id'] : "";

    global $wpdb;

    $query = "";
    if (!empty($id)) {
        $query = "DELETE FROM `taw_customer_unique_price` WHERE `taw_customer_unique_price`.`id` = $id";
    }
    $wpdb->get_results($query);
}
add_action("wp_ajax_taw_delete_article_customerprice", 'taw_delete_article_customerprice');

function taw_save_article_customerprice()
{
    $id = '';
    if (isset($_POST['id'])) {
        $id = sanitize_text_field($_POST['id']);
    }
    $art_no = '';
    if (isset($_POST['art_no'])) {
        $art_no = sanitize_text_field($_POST['art_no']);
    }
    $customer_no = '';
    if (isset($_POST['customer_no'])) {
        $customer_no = sanitize_text_field($_POST['customer_no']);
    }
    $price = '';
    if (isset($_POST['price'])) {
        $price = sanitize_text_field($_POST['price']);
    }
    $currency = '';
    if (isset($_POST['currency'])) {
        $currency = sanitize_text_field($_POST['currency']);
    }
    global $wpdb;
    $exRec = $wpdb->get_var("SELECT id FROM `taw_customer_unique_price` where art_no='$art_no'");

    $query = "";
    if (!empty($id)) {
        if (!empty($exRec) && $exRec != $id) {
            wp_send_json_success(['status' => 'Article no already exist']);
            exit;
        }
        $query = "UPDATE `taw_customer_unique_price` SET `customer_no` = '$customer_no',`price` = '$price',
        `currency` = '$currency',  `uuid`=CONCAT('$art_no', '::', '$currency', '::', '$customer_no') WHERE `taw_customer_unique_price`.`id` = $id;";
    } else {
        if (!empty($exRec)) {
            wp_send_json_success(['status' => 'Article no already exist']);
            exit;
        }

        $query = "INSERT INTO `taw_customer_unique_price` (`art_no`,`customer_no`,`price`,`currency`,`uuid`) 
        VALUES ('$art_no','$customer_no','$price','$currency',CONCAT('$art_no', '::', '$currency', '::', '$customer_no'));";
    }

    $result = $wpdb->query($query);
    if ($result) {
        wp_send_json_success(['status' => 'Data inserted successfully.']);
    } else {
        wp_send_json_error(['status' => 'Error inserting data.']);
    }
    wp_send_json_success(['status' => '1']);
}

// if($result) {
//     wp_send_json_success(['status' => 'Data inserted successfully.']);
// } else {
//     error_log("Error in query: " . $wpdb->last_error);
// }
// wp_send_json_error(['status' => 'Error inserting data: ' . $wpdb->last_error]);
// }
add_action("wp_ajax_taw_save_article_customerprice", 'taw_save_article_customerprice');

function taw_delete_article_title()
{
    $data = array_merge((array) $_GET, (array) $_POST);
    $id = isset($data['id']) ? $data['id'] : "";

    global $wpdb;

    $query = "";
    if (!empty($id)) {
        $query = "DELETE FROM `taw_article_title` WHERE `taw_article_title`.`id` = $id";
    }
    $wpdb->get_results($query);
}
add_action("wp_ajax_taw_delete_article_title", 'taw_delete_article_title');

function taw_save_article_title()
{
    $id = '';
    if (isset($_POST['id'])) {
        $id = sanitize_text_field($_POST['id']);
    }
    $art_no = '';
    if (isset($_POST['art_no'])) {
        $art_no = sanitize_text_field($_POST['art_no']);
    }
    $title = '';
    if (isset($_POST['title'])) {
        $title = sanitize_text_field($_POST['title']);
    }
    $desc = '';
    if (isset($_POST['desc'])) {
        $desc = sanitize_text_field($_POST['desc']);
    }
    $shortdesc = '';
    if (isset($_POST['shortdesc'])) {
        $shortdesc = sanitize_text_field($_POST['shortdesc']);
    }
    $lang = getSiteCurrentLang();
    global $wpdb;
    $exRec = $wpdb->get_var("SELECT id FROM `taw_article_title` where art_no='$art_no'");

    $query = "";
    if (!empty($id)) {
        if (!empty($exRec) && $exRec != $id) {
            wp_send_json_success(['status' => 'Article no already exist']);
            exit;
        }
        $query = "UPDATE `taw_article_title` SET `title` = '$title',`desc` = '$desc',
        `lang` = '$lang',  `uuid`=CONCAT('$lang', '::', '$art_no'),`shortdesc` = '$shortdesc'
         WHERE `taw_article_title`.`id` = $id;";
    } else {
        if (!empty($exRec)) {
            wp_send_json_success(['status' => 'Article no already exist']);
            exit;
        }

        $query = "INSERT INTO `taw_article_title` (`art_no`,`title`,`desc`,`lang`,`uuid`,`shortdesc`,) 
        VALUES ('$art_no','$title','$desc','$lang',CONCAT('$lang', '::', '$art_no'),'$shortdesc',);";
    }

    $result = $wpdb->query($query);

    if ($result) {
        wp_send_json_success(['status' => 'Data inserted successfully.']);
    } else {
        wp_send_json_error(['status' => 'Error inserting data.']);
    }
    wp_send_json_success(['status' => '1']);
}
add_action("wp_ajax_taw_save_article_title", 'taw_save_article_title');

function taw_delete_article_category()
{
    $data = array_merge((array) $_GET, (array) $_POST);
    $id = isset($data['id']) ? $data['id'] : "";

    global $wpdb;

    $query = "";
    if (!empty($id)) {
        $query = "DELETE FROM `taw_article_category` WHERE `taw_article_category`.`id` = $id";
    }
    $wpdb->get_results($query);
}
add_action("wp_ajax_taw_delete_article_category", 'taw_delete_article_category');

function taw_save_article_category()
{
    $id = '';
    if (isset($_POST['id'])) {
        $id = sanitize_text_field($_POST['id']);
    }
    $art_no = '';
    if (isset($_POST['art_no'])) {
        $art_no = sanitize_text_field($_POST['art_no']);
    }
    $term_id = '';
    if (isset($_POST['term_id'])) {
        $term_id = sanitize_text_field($_POST['term_id']);
    }
    $parent_cate = '';
    if (isset($_POST['parent_cate'])) {
        $parent_cate = sanitize_text_field($_POST['parent_cate']);
    }

    global $wpdb;
    $exRec = $wpdb->get_var("SELECT id FROM `taw_article_category` where art_no='$art_no'");

    $query = "";

    if (!empty($id)) {
        if (!empty($exRec) && $exRec != $id) {
            wp_send_json_success(['status' => 'Article no already exist']);
            exit;
        }
        $lang = getSiteCurrentLang();
        $query = "UPDATE `taw_article_category` SET `term_id` = (SELECT term_id FROM `tsm_terms` WHERE `slug` = '$term_id' LIMIT 1),
        `parent_cate`=(SELECT term_id FROM `tsm_terms` WHERE `name` = '$parent_cate' LIMIT 1),`lang`='$lang',
        `unique_code`=CONCAT('$art_no','::','$lang','::','$parent_cate','>>','$term_id') 
        WHERE `taw_article_category`.`id` = $id;";
    } else {
        if (!empty($exRec)) {
            wp_send_json_success(['status' => 'Article no already exist']);
            exit;
        }
        $lang = getSiteCurrentLang();
        $query = "INSERT INTO `taw_article_category` (`art_no`,`term_id`,`lang`,`unique_code`,`parent_cate`) 
        VALUES ('$art_no',(SELECT term_id FROM `tsm_terms` WHERE `slug` = '$term_id'),'$lang',
        CONCAT('$art_no','::','$lang','::','$parent_cate','>>','$term_id'),
        (SELECT term_id FROM `tsm_terms` WHERE `name` = '$parent_cate'));";
    }

    $result = $wpdb->query($query);
    if ($result) {
        wp_send_json_success(['status' => 'Data inserted successfully.']);
    } else {
        error_log("Error in query: " . $wpdb->last_error);
    }
    wp_send_json_error(['status' => 'Error inserting data: ' . $wpdb->last_error]);
}
// if($result) {
//     wp_send_json_success(['status' => 'Data inserted successfully.']);
// } else {
//     wp_send_json_error(['status' => 'Error inserting data.']);
// }
//     wp_send_json_success(['status' => '1']);
// }
add_action("wp_ajax_taw_save_article_category", 'taw_save_article_category');

function taw_delete_article_diagram()
{
    $data = array_merge((array) $_GET, (array) $_POST);
    $id = isset($data['id']) ? $data['id'] : "";

    global $wpdb;

    $query = "";
    if (!empty($id)) {
        $query = "DELETE FROM `taw_diagram` WHERE `taw_diagram`.`id` = $id";
    }
    $wpdb->get_results($query);
}
add_action("wp_ajax_taw_delete_article_diagram", 'taw_delete_article_diagram');

function taw_save_article_diagram()
{
    $id = '';
    if (isset($_POST['id'])) {
        $id = sanitize_text_field($_POST['id']);
    }
    $art_no = '';
    if (isset($_POST['art_no'])) {
        $art_no = sanitize_text_field($_POST['art_no']);
    }

    $diagram = '';
    if (isset($_POST['diagram'])) {
        $diagram = sanitize_text_field($_POST['diagram']);
        $diagram_id = $diagram;
        $diagram_url = wp_get_attachment_url($diagram_id);
        $diagram_name = basename($diagram_url);
    }

    $diagram2 = '';
    if (isset($_POST['diagram2'])) {
        $diagram2 = sanitize_text_field($_POST['diagram2']);
        $diagram2_id = $diagram2;
        $diagram2_url = wp_get_attachment_url($diagram2_id);
        $diagram2_name = basename($diagram2_url);
    }

    $diagram3 = '';
    if (isset($_POST['diagram3'])) {
        $diagram3 = sanitize_text_field($_POST['diagram3']);
        $diagram3_id = $diagram3;
        $diagram3_url = wp_get_attachment_url($diagram3_id);
        $diagram3_name = basename($diagram3_url);
    }

    global $wpdb;
    $exRec = $wpdb->get_var("SELECT id FROM `taw_diagram` where art_no='$art_no'");

    $query = "";
    $lang = getSiteCurrentLang();

    if (!empty($id)) {
        if (!empty($exRec) && $exRec != $id) {
            wp_send_json_success(['status' => 'Article no already exist']);
            exit;
        }

        $query = "UPDATE `taw_diagram` SET 
    `diagram` = '$diagram_name', 
    `diagram2` = '$diagram2_name', 
    `diagram3` = '$diagram3_name', 
    `diagram_id` = " . (!empty($diagram_id) ? "'$diagram_id'" : "NULL") . ",
    `diagram2_id` = " . (!empty($diagram2_id) ? "'$diagram2_id'" : "NULL") . ",
    `diagram3_id` = " . (!empty($diagram3_id) ? "'$diagram3_id'" : "NULL") . "
    WHERE `taw_diagram`.`id` = $id;";
        
        $result = $wpdb->query($query);

        wp_send_json_success(['status' => 'Data inserted successfully.']);
    } else {
        if (!empty($exRec)) {
            wp_send_json_success(['status' => 'Article no already exist']);
            exit;
        }

        $query = "INSERT INTO `taw_diagram` (`art_no`, `diagram`, `diagram2`, `diagram3`, `unique_code`, `diagram_id`, `diagram2_id`, `diagram3_id`) 
VALUES ('$art_no', '$diagram_name', '$diagram2_name', '$diagram3_name', CONCAT('$lang','::','$art_no'),";

if (!empty($diagram_id)) {
    $query .= "'$diagram_id'";
} else {
    // If empty, set it to NULL
    $query .= "NULL";
}
$query .= ", "; // Add a comma here


if (!empty($diagram2_id)) {
    $query .= "'$diagram2_id'";
} else {
    // If empty, set it to NULL
    $query .= "NULL";
}
$query .= ", "; // Add a comma here

// Check if diagram3_id is empty before appending it to the query
if (!empty($diagram3_id)) {
    $query .= "'$diagram3_id'";
} else {
    // If empty, set it to NULL
    $query .= "NULL";
}

$query .= ");";
        $result = $wpdb->query($query);
        
        if ($result) {
            wp_send_json_success(['status' => 'Data inserted successfully.']);
        } else {
            error_log("Error in query: " . $wpdb->last_error);
        }
        wp_send_json_error(['status' => 'Error inserting data: ' . $wpdb->last_error]);
    }
}

add_action("wp_ajax_taw_save_article_diagram", 'taw_save_article_diagram');

function taw_delete_article_picture()
{
    $data = array_merge((array) $_GET, (array) $_POST);
    $id = isset($data['id']) ? $data['id'] : "";

    global $wpdb;

    $query = "";
    if (!empty($id)) {
        $query = "DELETE FROM `taw_article_picture` WHERE `taw_article_picture`.`id` = $id";
    }
    $wpdb->get_results($query);
}
add_action("wp_ajax_taw_delete_article_picture", 'taw_delete_article_picture');

function taw_save_article_picture()
{
    $id = '';
    if (isset($_POST['id'])) {
        $id = sanitize_text_field($_POST['id']);
    }
    $art_no = '';
    if (isset($_POST['art_no'])) {
        $art_no = sanitize_text_field($_POST['art_no']);
    }

    $picture = '';
    if (isset($_POST['picture'])) {

        $picture = sanitize_text_field($_POST['picture']);
        $attachm_id = $picture;
        $image_url = wp_get_attachment_url($attachm_id);
        $image_name = basename($image_url);
    }

    $gallery = '';
    if (isset($_POST['gallery'])) {
        $gallery = sanitize_text_field($_POST['gallery']);
    }
    $colour = '';
    if (isset($_POST['colour'])) {
        $colour = sanitize_text_field($_POST['colour']);
    }
    $alt_text = '';
    if (isset($_POST['alt_text'])) {
        $alt_text = sanitize_text_field($_POST['alt_text']);
    }
    global $wpdb;
    $exRec = $wpdb->get_var("SELECT id FROM `taw_article_picture` where art_no='$art_no'");

    $query = "";
    $lang = getSiteCurrentLang();

    if (!empty($id)) {
        if (!empty($exRec) && $exRec != $id) {
            wp_send_json_success(['status' => 'Article no already exist']);
            exit;
        }
        $query = "UPDATE `taw_article_picture` SET `pic_name` = '$image_name', `attach_id` = '$attachm_id',`colour` = '$colour',
        `alt_text` = '$alt_text', `gallery_pics` = '$gallery'
        WHERE `taw_article_picture`.`id` = $id;";
        $result = $wpdb->query($query);
        wp_send_json_success(['status' => 'Data inserted successfully.']);
    } else {
        if (!empty($exRec)) {
            wp_send_json_success(['status' => 'Article no already exist']);
            exit;
        }

        $query = "INSERT INTO `taw_article_picture` (`art_no`,`pic_name`,`colour`,`alt_text`,`attach_id`,`gallery_pics`,`unique_code`) 
        VALUES ('$art_no','$image_name','$colour','$alt_text','$attachm_id','$gallery',CONCAT('$lang','::','$art_no'));";
        $result = $wpdb->query($query);
        if ($result) {
            wp_send_json_success(['status' => 'Data inserted successfully.']);
        } else {
            error_log("Error in query: " . $wpdb->last_error);
        }
        wp_send_json_error(['status' => 'Error inserting data: ' . $wpdb->last_error]);
    }
}

add_action("wp_ajax_taw_save_article_picture", 'taw_save_article_picture');
function uploadCustomImage($data, $ex)
{
    global $wpdb;

    $ex = array_map(function ($array_item) {
        return sanitizeText($array_item);
    }, $ex);
    $filename = implode("_", $ex);

    if (preg_match('/^data:image\/(\w+);base64,/', $data, $type)) {
        $data = substr($data, strpos($data, ',') + 1);
        $type = strtolower($type[1]); // jpg, png, gif

        if (!in_array($type, ['jpg', 'jpeg', 'gif', 'png'])) {
            throw new \Exception('invalid image type');
        }
        $data = str_replace(' ', '+', $data);
        $data = base64_decode($data);

        if ($data === false) {
            throw new \Exception('base64_decode failed');
        }
    } else {
        throw new \Exception('did not match data URI with image data');
    }

    return uploadImgToMedia($wpdb, $filename . ".{$type}", $data);
}

function getSiteCurrentLang()
{
    $cur_lang = apply_filters('wpml_current_language', null);
    return empty($cur_lang) ? 'en' : $cur_lang;
}
function sync_woocommerce_data()
{
   
    $data = array_merge((array) $_GET, (array) $_POST);
    $page_num = isset($data['page_num']) ? $data['page_num'] : 0;
    $type = isset($data['type']) ? $data['type'] : '';
   //echo "<script>console.log('results".json_encode($type)."');</script>";
   $cur_lang = getSiteCurrentLang();

    if ($type == "woocomm_data") {
        
        $data = sync_woocommercedata($page_num, $cur_lang);
       

    } 
    else {
        $data = ['end' => 1];
    }

    wp_send_json_success($data);
   
}
function sync_woocommercedata($page_num, $cur_lang)
{
   
    global $wpdb;
    $page_item = 30;
    $page_start = $page_num * $page_item;
    $count = $wpdb->get_var("SELECT COUNT(*) as count FROM `taw_article_title`");

    $q = "SELECT ID from tsm_posts where post_status = 'publish' and post_type = 'product' LIMIT $page_start,$page_item;";

    $res = $wpdb->get_results($q);


    foreach ($res as $r) {
        $id = $r->ID;
        $product = wc_get_product($id);
        $sku = $product->get_sku();
        //$sku='updatesyn-1';
        $title = $product->get_name();
        $desc = $product->get_description();
        $shortdesc = $product->get_short_description();
        

        $attach_id = $product->get_image_id();
        // $pic_name = get_the_title($attach_id);
        $gallery_pics = implode(',', $product->get_gallery_image_ids());
        $alt_text = get_post_meta($attach_id, '_wp_attachment_image_alt', true);
        $product_attributes = $product->get_attributes();

        $product_language = apply_filters('wpml_post_language_details', null, $id);
        $lang_code = isset($product_language['language_code']) ? $product_language['language_code'] : '';
        $uuid = "$lang_code::$sku";
        // Escape values to prevent SQL injection

        if(!empty($attach_id)){
            $pic_file_path = get_post_meta($attach_id, '_wp_attached_file', true);
            if (!empty($pic_file_path)) {
                $pic_name = basename($pic_file_path);
            }
        }

        $title = esc_sql($title);
        $desc = esc_sql($desc);
        $shortdesc = esc_sql($shortdesc);
        $attach_id = esc_sql($attach_id);
        $pic_name = esc_sql($pic_name);
        $gallery_pics = esc_sql($gallery_pics);
        $alt_text = esc_sql($alt_text);
        
        if (empty($sku)) {
            continue;
        }
        if ($lang_code !== $cur_lang) {
            continue; // Skip this product if it's not in the desired language
        }

        // Check if SKU exists in taw_article_title table and retrieve its data
        // $existing_title = $wpdb->get_row($wpdb->prepare("SELECT * FROM `taw_article_title` WHERE `art_no` = %s AND `lang` = %s", $sku, $cur_lang));

        // if (empty($existing_title)) {

        //     $insert_title = $wpdb->prepare("INSERT INTO `taw_article_title` (`art_no`, `title`, `desc`, `lang`, `uuid`, `shortdesc`) 
        //     VALUES (%s, %s, %s, %s, %s, %s)", $sku, $title, $desc, $lang_code, $uuid, $shortdesc);
        //      //print_r($insert_title);
        //     $wpdb->query($insert_title);
        // }else{
        //     $update_titlequery = "UPDATE `taw_article_title`
        //     SET `title` = '$title', `desc` = '$desc',  `shortdesc` = '$shortdesc'
        //     WHERE `art_no` = '$sku' and `lang` = '$lang_code'";
        //     //print_r($update_titlequery);
        //     $wpdb->query($update_titlequery);
        //     }
           
        

        // //Picture

        $existing_picture = $wpdb->get_row(
        $wpdb->prepare("SELECT * FROM `taw_article_picture` WHERE `art_no` = %s AND `lang` = %s", $sku, $cur_lang)
        );
        if (empty($existing_picture)) {
        if(!empty( $pic_name) || !empty( $gallery_pics) || !empty($attach_id)){
        $color = '';
        $insert_picture = $wpdb->prepare("INSERT INTO `taw_article_picture` (`art_no`, `pic_name`, `colour`,`alt_text`, `attach_id`, `lang`, `unique_code`, `gallery_pics`) 
        VALUES (%s, %s, %s, %s, %s, %s, %s, %s)", $sku, $pic_name, $color, $alt_text, $attach_id, $lang_code, $uuid, $gallery_pics);
        $wpdb->query($insert_picture);
        }
           // print_r($insert_picture);
        }else{
        if(!empty( $pic_name) || !empty( $gallery_pics) || !empty($attach_id)){
        $color = '';
        $update_picquery = "UPDATE `taw_article_picture`
        SET `pic_name` = '$pic_name', `colour` = '$color',  `alt_text` = '$alt_text', `attach_id` = '$attach_id',  `unique_code` = '$uuid',  `gallery_pics` = '$gallery_pics'
        WHERE `art_no` = '$sku' and `lang` = '$lang_code'";
        // print_r($update_picquery);
        $wpdb->query($update_picquery);
        }

        }

        // //Attribute 

        // $existing_attribute = $wpdb->get_row("SELECT * FROM `taw_article_attributes` WHERE `art_no` = %s", $sku);

        // if (empty($existing_attribute)) {
        //     if (!empty($product_attributes)) {

        //         foreach ($product_attributes as $attribute_name => $attribute) {
        //             $attr_id = $attribute->get_name();
        //             $term_ids = $attribute->get_terms();

        //             if ($term_ids) {
        //                 // $term_ids_str = implode(',', wp_list_pluck($term_ids, 'slug'));
        //                 $term_ids_str = implode(',', array_map(function ($term) {
        //                     return str_replace('-sv', '', $term->slug);
        //                 }, $term_ids));

        //                 $insert_attribute = $wpdb->prepare("INSERT INTO `taw_article_attributes` (`art_no`, `attr_id`, `term_ids`) 
        //             VALUES (%s, %s, %s)", $sku, $attr_id, $term_ids_str);
        //                 $wpdb->query($insert_attribute);
        //             }
        //         }
        //     }
        // }else{
            
        //     $query_deleteattr="DELETE FROM `taw_article_attributes` WHERE `art_no` = '$sku';";
        //     $wpdb->query($query_deleteattr);

        //     foreach ($product_attributes as $attribute_name => $attribute) {
        //         $attr_id = $attribute->get_name();
        //         $term_ids = $attribute->get_terms();

        //         if ($term_ids) {
        //             // $term_ids_str = implode(',', wp_list_pluck($term_ids, 'slug'));
        //             $term_ids_str = implode(',', array_map(function ($term) {
        //                 return str_replace('-sv', '', $term->slug);
        //             }, $term_ids));

        //             $update_attribute = "UPDATE `taw_article_attributes`
        //     SET `attr_id` = '$attr_id', `term_ids` = '$term_ids_str'
        //     WHERE `art_no` = '$sku' ";
        //     //print_r($update_titlequery);
        //             $wpdb->query($update_attribute);

        // }}}


        
        // // Insert Spareparts

        // $imgpointer_meta = $wpdb->get_row("SELECT * FROM `tsm_postmeta` WHERE `post_id` = '$id' and `meta_key`='_wpii_prod_img_pointer_info'");
        // $meta_value = json_decode($imgpointer_meta->meta_value, true);
        // if(!empty($meta_value))
        // {
        //     if((isset($meta_value['data'])) && (!empty($meta_value['data'])))
        //     {
        //         $query_deletespareparts="DELETE FROM `taw_product_spareparts` WHERE `parent_article` = '$sku';";
        //         $wpdb->query($query_deletespareparts);

        //         foreach ($meta_value['data'] as $item) 
        //         {
        //             $txt = isset($item['txt']) ? $item['txt'] : '';
        //             $qty = isset($item['qty']) ? $item['qty'] : '';
                    
        //                 //print_r('1');
        //             $insert_spareparts = $wpdb->prepare("INSERT INTO `taw_product_spareparts` (`parent_article`, `spare_article`, `min_qty`) 
        //             VALUES (%s, %s, %d)", $sku, $txt, $qty);                    
        //             $wpdb->query($insert_spareparts);
        //         }
        //     }                    
        // }
 

        // // Insert Price


         $post_meta = $wpdb->get_row("SELECT * FROM `tsm_postmeta` WHERE `post_id` = '$id' and `meta_key`='taw_prod_opt'");
         $meta_value = unserialize($post_meta->meta_value);
        // if ($post_meta !== null) {
        //     $meta_value = unserialize($post_meta->meta_value);

        //     if (isset($meta_value['article_price']['b2b'])) {
        //         $price_b2b = $meta_value['article_price']['b2b'];
        //     } else {
        //         $price_b2b = null;
        //     }

        //     if (isset($meta_value['article_price']['reseller_sek'])) {
        //         $price_reseller_sek = $meta_value['article_price']['reseller_sek'];
        //     } else {
        //         $price_reseller_sek = null;
        //     }

        //     if (isset($meta_value['article_price']['reseller_eur'])) {
        //         $price_reseller_eur = $meta_value['article_price']['reseller_eur'];
        //     } else {
        //         $price_reseller_eur = null;
        //     }

        //     // Check if any of the prices are not 0 and not empty
        //     if ($price_b2b != 0 || $price_reseller_sek != 0 || $price_reseller_eur != 0) {
        //         if ($price_b2b !== '' || $price_reseller_sek !== '' || $price_reseller_eur !== '') {
        //             $existing_price = $wpdb->get_row("SELECT * FROM `taw_article_price` WHERE `art_no` = %s", $sku);

        //             if (empty($existing_price)) {
        //                 $insert_price = $wpdb->prepare("INSERT INTO `taw_article_price` (`art_no`, `price_b2b`, `price_reseller_eur`, `price_reseller_sek`) 
        //                 VALUES (%s, %d, %d, %d)", $sku, $price_b2b, $price_reseller_eur, $price_reseller_sek);

        //                 // Ensure the prepared statement is correct
        //                 // print_r($wpdb->prepare("INSERT INTO `taw_article_price` (`art_no`, `price_b2b`, `price_reseller_eur`, `price_reseller_sek`) 
        //                 // VALUES (%s, %d, %d, %d)", $sku, $price_b2b, $price_reseller_eur, $price_reseller_sek));

        //                 // Execute the insert query
        //                 $wpdb->query($insert_price);
        //             } else {

        //                 if(!empty($price_b2b) || !empty($price_reseller_sek) || !empty($price_reseller_eur) ){
        //                     $query_deleteprice="DELETE FROM `taw_article_price` WHERE `art_no` = '$sku';";
        //                     $wpdb->query($query_deleteprice);
                            
        //                     $update_price = $wpdb->prepare("INSERT INTO `taw_article_price` (`art_no`, `price_b2b`, `price_reseller_eur`, `price_reseller_sek`) 
        //                     VALUES (%s, %d, %d, %d)", $sku, $price_b2b, $price_reseller_eur, $price_reseller_sek);
                        
        //                 $wpdb->query($update_price);
        //                 }
            
        //             }
        //         }
        //     }
        // }
       
        //Insert Diagram

        $diagram1 = '';
        $diagram2 = '';
        $diagram3 = '';
        if (isset($meta_value['article_price']['product_diagram_file'])) {
            $diagram1 = $meta_value['article_price']['product_diagram_file'];

        } 

        if (isset($meta_value['article_price']['product_diagram_file2'])) {
            $diagram2 = $meta_value['article_price']['product_diagram_file2'];
           
        } 

        if (isset($meta_value['article_price']['product_diagram_file3'])) {
            $diagram3 = $meta_value['article_price']['product_diagram_file3'];
           
}
        if(!empty($diagram1['title']))
        {
            $diagram1title = basename($meta_value['article_price']['product_diagram_file']['url']);

        }

        if(!empty($diagram2['title']))
        {
            $diagram2title = basename($meta_value['article_price']['product_diagram_file2']['url']);

        }

        if(!empty($diagram3['title']))
        {
            $diagram3title = basename($meta_value['article_price']['product_diagram_file3']['url']);

        }

        // $query_deletediagram = "DELETE FROM `taw_diagram` WHERE `art_no` = '$art_no';";
        // $wpdb->query($query_deletediagram);
        if(!empty($diagram1['title']) || !empty($diagram2['title']) || !empty($diagram3['title']))
        {

        $insert_diagram = $wpdb->prepare(
            "INSERT INTO `taw_diagram` (`art_no`, `lang`, `diagram`, `diagram2`, `diagram3`, `unique_code`, `diagram_id`, `diagram2_id`, `diagram3_id`)
            VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
            $sku,
            $lang_code,
            !empty($diagram1['title']) ? $diagram1title :'',
            !empty($diagram2['title']) ? $diagram2title : '',
            !empty($diagram3['title']) ? $diagram3title : '',
            "$lang_code::$sku",
            !empty($diagram1['id']) ? $diagram1['id'] :'0',
            !empty($diagram2['id']) ? $diagram2['id'] : '0',
            !empty($diagram3['id']) ? $diagram3['id'] : '0'
        );


       $wpdb->query($insert_diagram);
        }
             

        //Insert Customer Uniqueprice

        // if (isset($meta_value['article_price']['customer_price'])) {
        //     $customer = $meta_value['article_price']['customer_price'];
        // } else {
        //     $customer = null;
        // }
        // $existing_uniqueprice = $wpdb->get_row("SELECT * FROM `taw_customer_unique_price` WHERE `art_no` = %s", $sku);
        // if (empty($existing_uniqueprice)) {
        //     if (is_array($customer)) {
        //     foreach ($customer as $c) {
        //         $customerid = explode('::', $c['customer'])[0];
        //         $customerprice = $c['price'];
        //         if (isset($c['currency'])) {
        //         $customercurrency = $c['currency'];
        //     }
        //         if ($customerid != 0 && $customerid != '') {
        //             $insert_uniqueprice = $wpdb->prepare("INSERT INTO `taw_customer_unique_price` (`customer_no`, `price`, `currency`, `art_no`) 
        //     VALUES (%s, %s, %s, %s)", $customerid, $customerprice, $customercurrency, $sku);
        //             $wpdb->query($insert_uniqueprice);
        //         }
        //     }
        // }
        // }else{
        //     $query_deleteuniqueprice="DELETE FROM `taw_customer_unique_price` WHERE `art_no` = '$sku';";
        //     $wpdb->query($query_deleteuniqueprice);
        //     if (is_array($customer)) {
        //         foreach ($customer as $c) {
        //             $customerid = explode('::', $c['customer'])[0];
        //             $customerprice = $c['price'];
        //             if (isset($c['currency'])) {
        //             $customercurrency = $c['currency'];
        //         }
        //             if ($customerid != 0 && $customerid != '') {
        //                 $insert_uniqueprice = $wpdb->prepare("INSERT INTO `taw_customer_unique_price` (`customer_no`, `price`, `currency`, `art_no`) 
        //         VALUES (%s, %s, %s, %s)", $customerid, $customerprice, $customercurrency, $sku);
        //                 $wpdb->query($insert_uniqueprice);
        //             }
        //         }
        //     }

            

        // }

        // Insert Category

        // $categories = $product->get_category_ids();
        
        // if (!empty($categories)) 
        // {
        //     $parentCategories = [];
        //     $childCategories = [];

        //     $parentidvalues = [];
        //     $childidvalues = [];

        //     foreach ($categories as $categoryId) {
        //         $parentquery = "SELECT term.slug FROM tsm_terms as term 
        //                         INNER JOIN tsm_term_taxonomy as taxonomy ON term.term_id = taxonomy.term_id
        //                         WHERE term.term_id = $categoryId AND taxonomy.parent = 0";

        //         $childquery = "SELECT term.slug FROM tsm_terms as term 
        //                         INNER JOIN tsm_term_taxonomy as taxonomy ON term.term_id = taxonomy.term_id
        //                         WHERE term.term_id = $categoryId AND taxonomy.parent != 0";

        //         $parentSlug = $wpdb->get_var($parentquery);
        //         $childSlug = $wpdb->get_var($childquery);

        //         if ($parentSlug) {
        //             $parentCategories[] = $parentSlug;
        //         }

        //         if ($childSlug) {
        //             $childCategories[] = $childSlug;
        //         }


        //         $parentidquery = "SELECT term.term_id FROM tsm_terms as term 
        //         INNER JOIN tsm_term_taxonomy as taxonomy ON term.term_id = taxonomy.term_id
        //         WHERE term.term_id = $categoryId AND taxonomy.parent = 0";

        //         $childidquery = "SELECT term.term_id FROM tsm_terms as term 
        //         INNER JOIN tsm_term_taxonomy as taxonomy ON term.term_id = taxonomy.term_id
        //         WHERE term.term_id = $categoryId AND taxonomy.parent != 0";

        //         $parentidvalue = $wpdb->get_var($parentidquery);
        //         $childidvalue = $wpdb->get_var($childidquery);

        //         if ($parentidvalue) {
        //             $parentidvalues[] = $parentidvalue;
        //         }

        //         if ($childidvalue) {
        //             $childidvalues[] = $childidvalue;
        //         }
        //     }

        //     $child = str_replace('-sv', '', $childCategories);
        //     $parent = str_replace('-sv', '', $parentCategories);

        //     $parentslug = implode('|', $parent);
        //     $childslug = implode('|', $child);

        //     $parentid = implode(',', $parentidvalues);
        //     $childid = implode(',', $childidvalues);

        //     //$uniqucode = "$sku::$lang_code::$childslug>>$parentslug";
        //     $uniqucode = "$lang_code::$sku";
           
        //     // print_r(  $uniqucode);
        //     // exit;

        //     $existing_category = $wpdb->get_row("SELECT * FROM `taw_article_category` WHERE `art_no` = '$sku' and `lang` = '$cur_lang'");
        //     if (empty($existing_category)) {
        //         $insert_cate = "INSERT INTO `taw_article_category` (`art_no`, `term_id`, `lang`, `parent_cate`, `unique_code`)
        //                     VALUES ('$sku', '$childid', '$lang_code', '$parentid', '$uniqucode')";

        //     $wpdb->query($insert_cate);
        //     }else{
        //     $query_deletecategory = "DELETE FROM taw_article_category WHERE `art_no` = '$sku' and `lang` = '$lang_code';";
        //     $wpdb->query($query_deletecategory);

        //     $insert_cate = "INSERT INTO `taw_article_category` (`art_no`, `term_id`, `lang`, `parent_cate`, `unique_code`)
        //                     VALUES ('$sku', '$childid', '$lang_code', '$parentid', '$uniqucode')";

        //     $wpdb->query($insert_cate);
        //     }
            
        // }
    }
    $res_count = count($res);

    $end = 1;
    $percentage = "0";
    if ($res_count > 0) {
        $end = 0;
        if ($page_start > 0) {
            $percentage = floor($page_start / ($count / 100));
            if ($percentage >= 100) {
                $end = 1;
            }
        }
    }

    $page_num = $page_num + 1;

    return ["page_num" => $page_num, "end" => $end, "percentage" => $percentage];
}


add_action("wp_ajax_sync_woocommerce_data", 'sync_woocommerce_data');
function sync_woocommerce_product_data()
{
    $data = array_merge((array) $_GET, (array) $_POST);
    $page_num = isset($data['page_num']) ? $data['page_num'] : 0;
    $type = isset($data['type']) ? $data['type'] : '';

    $cur_lang = getSiteCurrentLang();

    if ($type == "price") {
        $data = sync_product_price($page_num, $cur_lang);
    } else if ($type == "title_desc") {
        $data = sync_title_description($page_num, $cur_lang);
    } else if ($type == "attributes") {
        $data = sync_attributes($page_num);
    } else if ($type == "category") {
        $data = sync_product_category($page_num, $cur_lang);
    } else if ($type == "image") {
        $data = sync_product_image($page_num, $cur_lang);
    }else if ($type == "accessories") {
        $data = sync_product_accessories($page_num, $cur_lang);
    }else if ($type == "customeruniqueprice") {
        $data = sync_product_customeruniqueprice($page_num, $cur_lang);
    }else if ($type == "diagram") {
        $data = sync_diagram($page_num, $cur_lang);
    }else if ($type == "spareparts") {
        $data = sync_spareparts($page_num, $cur_lang);
    }else {
        $data = ['end' => 1];
    }

    wp_send_json_success($data);
}

function sync_attributes($page_num)
{
    global $wpdb;

    $page_item = 20;
    $page_start = $page_num * $page_item;
    $count = $wpdb->get_var("SELECT count(DISTINCT art_no) as count FROM `taw_article_attributes`");

    $q = "SELECT art_no,GROUP_CONCAT(attr_id,'::',term_ids,':::') as v FROM `taw_article_attributes` GROUP by art_no limit $page_start,$page_item;";

    $res = $wpdb->get_results($q);
    //update product attributes   

    foreach ($res as $a) {
        $sku = $a->art_no;
        if (empty($sku)) {
            continue;
        }
        $id = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1", $sku ) );

        //get main lang product id
        // $id = wc_get_product_id_by_sku($sku);
        if (empty($id)) {
            continue;
        }

        //get product id by lang
        $id = getLanguageProductId($id, $cur_lang);
        if (empty($id)) {
            continue;
        }

        $productAttributes = array();
        $pos = 1;
        $v_ar = explode(":::", $a->v);
        foreach ($v_ar as $v) {
            $attr_ar = explode("::", $v);

            if (count($attr_ar) != 2) {
                continue;
            }

            $values = array_map('trim', array_filter(explode(",", $attr_ar[1])));
            $attr_id = trim(str_replace(",", "", $attr_ar[0]));

            $productAttributes[$attr_id] = array(
                'name' => $attr_id,
                'value' => $values,
                'position' => $pos++,
                'is_visible' => 1,
                'is_variation' => 0,
                'is_taxonomy' => '1'
            );

            wp_set_object_terms($id, $values, $attr_id, false);
        }

        if (!empty($productAttributes)) {
            update_post_meta($id, '_product_attributes', $productAttributes);
        }
    }

    $res_count = count($res);

    $end = 1;
    $percentage = "0";
    if ($res_count > 0) {
        $end = 0;
        if ($page_start > 0) {
            $percentage = floor($page_start / ($count / 100));
            if ($percentage >= 100) {
                $end = 1;
            }
        }
    }

    $page_num = $page_num + 1;

    return ["page_num" => $page_num, "end" => $end, "percentage" => $percentage];
}

$sync_title_description_in_progress = false; 

function sync_title_description($page_num, $cur_lang)
{
    global $wpdb, $sync_title_description_in_progress;
    $sync_title_description_in_progress = true;
    $page_item = 30;
    $page_start = $page_num * $page_item;
    $count = $wpdb->get_var("SELECT COUNT(*) as count FROM `taw_article_title`;");

    $q = "SELECT taf.id,taf.art_no,taf.title,taf.`desc`,taf.`shortdesc`,tap.price_b2b FROM `taw_article_title` as taf 
    left join taw_article_price as tap on tap.art_no=taf.art_no where taf.lang='$cur_lang' ORDER BY `id` limit $page_start,$page_item;";

    $res = $wpdb->get_results($q);


    foreach ($res as $r) {
        $sku = $r->art_no;
        if (empty($sku)) {
            continue;
        }

        // $id = wc_get_product_id_by_sku($sku);
        $id = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1", $sku ) );
        $add_new_lang = false;
        $trid = 0; //for wpml
        if ($cur_lang == "en") {
            if (empty($id)) {
                $product = new WC_Product_Simple();
                $product->set_sku($r->art_no);

                $meta['article_price']['b2b'] = 0;
                $meta['article_price']['reseller_eur'] = 0;
                $meta['article_price']['reseller_sek'] = 0;
                $product->save();
                // Add meta to the product
                add_post_meta($product->get_id(), 'taw_prod_opt', $meta);
                
            } else {
                $product = wc_get_product($id);
            }
        } else {

            $id = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1", $sku ) );
            // $id = wc_get_product_id_by_sku($sku);
      
            //for other language the primarty id is mandatory
            if (empty($id)) {
                continue;
            }
            $cur_id = getLanguageProductId($id, $cur_lang);
            if (empty($cur_id)) {
                $product = new WC_Product_Simple();
                $trid = getLanguageProductTridId($id);
                $add_new_lang = true;
$meta['article_price']['b2b'] = 0;
                $meta['article_price']['reseller_eur'] = 0;
                $meta['article_price']['reseller_sek'] = 0;
                $product->save();
                // Add meta to the product
                add_post_meta($product->get_id(), 'taw_prod_opt', $meta);
            } else {
                $product = wc_get_product($cur_id);
            }
        }

        if (empty($product)) {
            continue;
        }

        $product->set_name($r->title);
        // Set the slug based on the title
        // $slug = sanitize_title($r->title);
        $slug = sanitize_title(str_replace('%', '', $r->title));
        $product->set_slug($slug);
        
        $product->set_regular_price($r->price_b2b);

        $sentances = explode(".", $r->desc);
        $short_desc = $r->desc;
        if (count($sentances) > 2) {
            $short_desc = $sentances[0] . ". " . $sentances[1] . ".";
        }
        $product->set_short_description($r->shortdesc);
        $product->set_description($r->desc);

        $product->save();

//     $meta = get_post_meta($product->get_id(), 'taw_prod_opt', true);
       //    // Check if $meta is an array, otherwise convert it to an array
        //     if (!is_array($meta)) {
            //         $meta = array();
        //     }

        //     if (!isset($meta['article_price']) || empty($meta['article_price'])) {
            //         $meta['article_price']['b2b'] = 0;
            //         $meta['article_price']['reseller_eur'] = 0;
            //         $meta['article_price']['reseller_sek'] = 0;
//         update_post_meta($product->get_id(), 'taw_prod_opt', $meta);
        //     }
        
        if ($add_new_lang) {
            addNewLangDataToWPML($product->get_id(), $sku, $cur_lang, $trid);
        }
    }
   
    $res_count = count($res);
    $end = 1;
    $percentage = "0";
    if ($res_count > 0) {
        $end = 0;
        if ($page_start > 0) {
            $percentage = floor($page_start / ($count / 100));
            if ($percentage >= 100) {
                $end = 1;
            }
        }
    }

    $page_num = $page_num + 1;

    $sync_title_description_in_progress = false;

    return ["page_num" => $page_num, "end" => $end, "percentage" => $percentage];
   
}

add_action("wp_ajax_sync_woocommerce_product_data", 'sync_woocommerce_product_data');

    function set_product($id, $post)
    {
        global $wpdb, $sync_title_description_in_progress;
        $lang = getSiteCurrentLang();
        $product = wc_get_product($id);

        if ($sync_title_description_in_progress) {
            return;
        }
        if ($post->post_status === 'publish' && $post->post_type === 'product') {
            
            $art_no = $product->get_sku();
            $title = $product->get_name();
            $desc = $product->get_description();
            $shortdesc = $product->get_short_description();
            $attach_id = $product->get_image_id();
            //$pic_name = get_the_title($attach_id);
            $gallery_pics = implode(',', $product->get_gallery_image_ids());
            $alt_text = get_post_meta($attach_id, '_wp_attachment_image_alt', true);
            $product_attributes = $product->get_attributes();

            // Escape values to prevent SQL injection
            $art_no = esc_sql($art_no);
            $title = esc_sql($title);
            $desc = esc_sql($desc);
            $shortdesc = esc_sql($shortdesc);
            $lang = esc_sql($lang);
            $attach_id = esc_sql($attach_id);
            $gallery_pics = esc_sql($gallery_pics);
            $alt_text = esc_sql($alt_text);

            if(!empty($attach_id)){
                $pic_file_path = get_post_meta($attach_id, '_wp_attached_file', true);
                if (!empty($pic_file_path)) {
                    $pic_name = basename($pic_file_path);
}
            }

            // Insert Title and description
if(!empty($art_no))
            {
            $existing_record_title = $wpdb->get_row("SELECT * FROM `taw_article_title` WHERE `art_no` = '$art_no' and `lang` = '$lang'");

            if ($existing_record_title) {
                $query = "UPDATE `taw_article_title`
                        SET `title` = '$title', `desc` = '$desc', `lang` = '$lang', `uuid` = '$lang::$art_no', `shortdesc` = '$shortdesc'
                        WHERE `art_no` = '$art_no' and `lang` = '$lang'";
            } else {
                $query = "INSERT INTO `taw_article_title` (`art_no`, `title`, `desc`, `lang`, `uuid`, `shortdesc`)
                        VALUES ('$art_no', '$title', '$desc', '$lang', '$lang::$art_no', '$shortdesc')";
            }
            $wpdb->query($query);

            // Insert Picture

            // $existing_record_picture = $wpdb->get_row("SELECT * FROM `taw_article_picture` WHERE `art_no` = '$art_no' and `lang` = '$lang'");

                if(!empty( $pic_name) || !empty( $gallery_pics) || !empty($attach_id))
                {
                    $query_deletepic = "DELETE FROM taw_article_picture WHERE `art_no` = '$art_no' and `lang` = '$lang';";
                    $wpdb->query($query_deletepic);

                    $query_pic = "INSERT INTO `taw_article_picture` (`art_no`, `pic_name`, `colour`, `alt_text`, `attach_id`, `lang`, `unique_code`, `gallery_pics`)
                        VALUES ('$art_no', '$pic_name', '', '$alt_text', '$attach_id', '$lang', '$lang::$art_no', '$gallery_pics')";
                   
                    $wpdb->query($query_pic);
                }


        // Insert Category

        $categories = $product->get_category_ids();

        if (!empty($categories)) 
        {
            $parentCategories = [];
            $childCategories = [];

            $parentidvalues = [];
            $childidvalues = [];

            foreach ($categories as $categoryId) {
                $parentquery = "SELECT term.slug FROM tsm_terms as term 
                                INNER JOIN tsm_term_taxonomy as taxonomy ON term.term_id = taxonomy.term_id
                                WHERE term.term_id = $categoryId AND taxonomy.parent = 0";

                $childquery = "SELECT term.slug FROM tsm_terms as term 
                                INNER JOIN tsm_term_taxonomy as taxonomy ON term.term_id = taxonomy.term_id
                                WHERE term.term_id = $categoryId AND taxonomy.parent != 0";

                $parentSlug = $wpdb->get_var($parentquery);
                $childSlug = $wpdb->get_var($childquery);

                if ($parentSlug) {
                    $parentCategories[] = $parentSlug;
                }

                if ($childSlug) {
                    $childCategories[] = $childSlug;
                }


                $parentidquery = "SELECT term.term_id FROM tsm_terms as term 
                INNER JOIN tsm_term_taxonomy as taxonomy ON term.term_id = taxonomy.term_id
                WHERE term.term_id = $categoryId AND taxonomy.parent = 0";

                $childidquery = "SELECT term.term_id FROM tsm_terms as term 
                INNER JOIN tsm_term_taxonomy as taxonomy ON term.term_id = taxonomy.term_id
                WHERE term.term_id = $categoryId AND taxonomy.parent != 0";

                $parentidvalue = $wpdb->get_var($parentidquery);
                $childidvalue = $wpdb->get_var($childidquery);

                if ($parentidvalue) {
                    $parentidvalues[] = $parentidvalue;
                }

                if ($childidvalue) {
                    $childidvalues[] = $childidvalue;
                }
            }

            $child = str_replace('-sv', '', $childCategories);
            $parent = str_replace('-sv', '', $parentCategories);

            $parentslug = implode('|', $parent);
            $childslug = implode('|', $child);

            $parentid = implode(',', $parentidvalues);
            $childid = implode(',', $childidvalues);

            // $uniqucode = "$art_no::$lang::$childslug>>$parentslug";

               $uniqucode = "$lang::$art_no";

            $query_deletecategory = "DELETE FROM taw_article_category WHERE `art_no` = '$art_no' and `lang` = '$lang';";
            $wpdb->query($query_deletecategory);

            $query_cate = "INSERT INTO `taw_article_category` (`art_no`, `term_id`, `lang`, `parent_cate`, `unique_code`)
                            VALUES ('$art_no', '$childid', '$lang', '$parentid', '$uniqucode')";
//  print_r( $query_cate);
//  exit;
            $wpdb->query($query_cate);
        }
            // Insert Price

            $post_meta = $wpdb->get_row("SELECT * FROM `tsm_postmeta` WHERE `post_id` = '$id' and `meta_key`='taw_prod_opt'");       
        
                $meta_value = unserialize($post_meta->meta_value);  
                $price_b2b=$meta_value['article_price']['b2b'];
                $price_reseller_sek = $meta_value['article_price']['reseller_sek'];
                $price_reseller_eur = $meta_value['article_price']['reseller_eur'];

                $meta_value = esc_sql($meta_value);
                $price_b2b = esc_sql($price_b2b);
                $price_reseller_sek = esc_sql($price_reseller_sek);
                $price_reseller_eur = esc_sql($price_reseller_eur);

                $query_deleteprice="DELETE FROM `taw_article_price` WHERE `art_no` = '$art_no';";
                $wpdb->query($query_deleteprice);
                if(!empty($price_b2b) || !empty($price_reseller_sek) || !empty($price_reseller_eur) ){
                $query_price = "INSERT INTO `taw_article_price` (`art_no`, `price_b2b`, `price_reseller_eur`, `price_reseller_sek`)
                VALUES ('$art_no', '$price_b2b', '$price_reseller_eur', '$price_reseller_sek')";
                $wpdb->query($query_price);
                }
                $query_deletespareparts = "DELETE FROM `taw_product_spareparts` WHERE `parent_article` = '$art_no';";
                $wpdb->query($query_deletespareparts);
                if(!empty($meta_value))
                {   
                    // Insert Customer Uniqueprice
                    $customer = $meta_value['article_price']['customer_price'];
                    $query_deleteuniqueprice="DELETE FROM `taw_customer_unique_price` WHERE `art_no` = '$art_no';";
                    $wpdb->query($query_deleteuniqueprice);

                    foreach($customer as $c){
                        $customerid=explode('::',$c['customer'])[0];
                        $customerprice=$c['price'];
                        $customercurrency=$c['currency'];

                        $query_uniqueprice = "INSERT INTO `taw_customer_unique_price` (`customer_no`, `price`, `currency`, `art_no`)
                        VALUES ('$customerid', '$customerprice', '$customercurrency', '$art_no')";
                        $wpdb->query($query_uniqueprice);
                    }

                    // Insert Accessories

                    $accessories = $meta_value['article_price']['AccessoriesArticle'];
                    $query_deleteaccessories = "DELETE FROM `taw_product_accessories` WHERE `parent_article` = '$art_no';";
                    $wpdb->query($query_deleteaccessories);

                    foreach ($accessories as $a) {
                        $acs_article = $a['acs_article'];
                        $pallets = $a['pallet'];
                        $query_accessories = "INSERT INTO `taw_product_accessories` (`parent_article`, `acs_article`, `no_plates`)
                        VALUES ('$art_no', '$acs_article', " . (!empty($pallets) ? "'$pallets'" : 'NULL') . ")";
                        $wpdb->query($query_accessories);
                    }

                       // Insert Diagram

                       $diagram1 = '';
                       $diagram2 = '';
                       $diagram3 = '';
                       if (isset($meta_value['article_price']['product_diagram_file'])) {
                            $diagram1 = $meta_value['article_price']['product_diagram_file'];

                        } 

                        if (isset($meta_value['article_price']['product_diagram_file2'])) {
                            $diagram2 = $meta_value['article_price']['product_diagram_file2'];
                           
                        } 

                        if (isset($meta_value['article_price']['product_diagram_file3'])) {
                            $diagram3 = $meta_value['article_price']['product_diagram_file3'];
                           
                        }

                        $query_deletediagram = "DELETE FROM `taw_diagram` WHERE `art_no` = '$art_no';";
                        $wpdb->query($query_deletediagram);

                        if(!empty($diagram1['title']))
                        {
                            $diagram1title = basename($meta_value['article_price']['product_diagram_file']['url']);

                        }

                        if(!empty($diagram2['title']))
                        {
                            $diagram2title = basename($meta_value['article_price']['product_diagram_file2']['url']);

                        }

                        if(!empty($diagram3['title']))
                        {
                            $diagram3title = basename($meta_value['article_price']['product_diagram_file3']['url']);

                        }
                      
if((!empty($diagram1['title']) || (!empty($diagram2['title']))) ||(!empty($diagram3['title']))){
                        $query_diagram = $wpdb->prepare(
                            "INSERT INTO `taw_diagram` (`art_no`, `lang`, `diagram`, `diagram2`, `diagram3`, `unique_code`, `diagram_id`, `diagram2_id`, `diagram3_id`)
                            VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
                            $art_no,
                            $lang,
                            !empty($diagram1['title']) ? $diagram1title :'',
                            !empty($diagram2['title']) ? $diagram2title : '',
                            !empty($diagram3['title']) ? $diagram3title : '',
                            "$lang::$art_no",
                            !empty($diagram1['id']) ? $diagram1['id'] :'0',
                            !empty($diagram2['id']) ? $diagram2['id'] : '0',
                            !empty($diagram3['id']) ? $diagram3['id'] : '0'
                        );

                        $wpdb->query($query_diagram);
}
                    

                    // Insert Spareparts
                    $spareparts = $meta_value['article_price']['SparepartsArticle'];
                    
                    foreach ($spareparts as $s) {
                        $spare_article = $s['spare_article'];
                        $min_qty = $s['min_qty'];
                        $existing_spareentry = $wpdb->get_row($wpdb->prepare("SELECT * FROM `taw_product_spareparts` WHERE `parent_article` = %s AND `spare_article` = %s", $art_no, $spare_article));
                        if (!$existing_spareentry) {

                        $query_spareparts = "INSERT INTO `taw_product_spareparts` (`parent_article`, `spare_article`, `min_qty`)
                        VALUES ('$art_no', '$spare_article', " . (!empty($min_qty) ? "'$min_qty'" : 'NULL') . ")";

                        $wpdb->query($query_spareparts);
                        }
                    }
                }

                $imgpointer = $wpdb->get_row("SELECT * FROM `tsm_postmeta` WHERE `post_id` = '$id' and `meta_key`='_wpii_prod_img_pointer_info'");
                $imgpointer_metavalue = $imgpointer->meta_value;
                $imgpointer_data = json_decode($imgpointer_metavalue);

                if ($imgpointer_data !== null)
                {
                    $meta = get_post_meta($id, 'taw_prod_opt', true);

                    if (!is_array($meta)) {
                        $meta = [];
                    }
            
                    if (!isset($meta['article_price']['SparepartsArticle'])) {
                        $meta['article_price']['SparepartsArticle'] = [];
                    }
                    $new_sparepointer_array = [];
                    foreach ($imgpointer_data->data as $index => $data)
                    {
                        $new_sparepointer_array[] = [
                            'spare_article' => $data->txt,
                            'min_qty' => $data->qty,
                        ];
                        $spare_article = $data->txt;
                        $min_qty = $data->qty;

                        $existingspar_entry = $wpdb->get_row($wpdb->prepare("SELECT * FROM `taw_product_spareparts` WHERE `parent_article` = %s AND `spare_article` = %s", $art_no, $spare_article));

                        if (!$existingspar_entry)
                        {
                        $query_sparepointer = "INSERT INTO `taw_product_spareparts` (`parent_article`, `spare_article`, `min_qty`)
                        VALUES ('$art_no', '$spare_article', " . (!empty($min_qty) ? "'$min_qty'" : 'NULL') . ")";
                        $wpdb->query($query_sparepointer);
                        }
                    }
                      $meta['article_price']['SparepartsArticle'] = $new_sparepointer_array;
                      update_post_meta($id, 'taw_prod_opt', $meta);
                }

                // if ($imgpointer_data !== null) {
                //     // $meta = get_post_meta($id, 'taw_prod_opt', true);

                //     // if (!is_array($meta)) {
                //     //     $meta = [];
                //     // }
            
                //     // if (!isset($meta['article_price']['SparepartsArticle'])) {
                //     //     $meta['article_price']['SparepartsArticle'] = [];
                //     // }
                //     // $new_sparepointer_array = [];
                //     foreach ($imgpointer_data->data as $index => $data) {
                //         // $new_sparepointer_array[] = [
                //         //     'spare_article' => $data->txt,
                //         //     'min_qty' => $data->qty,
                //         // ];
                //         $spare_article = $data->txt;
                //         $min_qty = $data->qty;

                //         $query_sparepointer = "INSERT INTO `taw_product_spareparts` (`parent_article`, `spare_article`, `min_qty`)
                //         VALUES ('$art_no', '$spare_article', " . (!empty($min_qty) ? "'$min_qty'" : 'NULL') . ")";
                //         $wpdb->query($query_sparepointer);

                //     }
                //     // $meta['article_price']['SparepartsArticle'] = $new_sparepointer_array;
                //     // update_post_meta($id, 'taw_prod_opt', $meta);
                // }

            // Insert Attributes

            if (!empty($product_attributes)) {
                $query_deleteattribute = "DELETE FROM taw_article_attributes WHERE `art_no` = '$art_no';";
                $wpdb->query($query_deleteattribute);

                foreach ($product_attributes as $attribute_name => $attribute) {
                    $attr_id = $attribute->get_name();
                    $term_ids = $attribute->get_terms();
                
                    if ($term_ids) {
                        $term_ids_str = implode(',', array_map(function ($term) {
                            return str_replace('-sv', '', $term->slug);
                        }, $term_ids));

                        $query_attribute = "INSERT INTO `taw_article_attributes` (`art_no`, `attr_id`, `term_ids`)
                        VALUES ('$art_no', '$attr_id', '$term_ids_str')";
                        $wpdb->query($query_attribute);
                        
}
                    }
                }
            }
        
    
        }
    }

 add_action('save_post', 'set_product', 20, 2);
 add_action('wp_trash_post', 'custom_action_on_trash', 10, 1);
 add_action('transition_post_status', 'custom_action_on_status_change', 10, 3);
 // Handle trash action
function custom_action_on_trash($post_id) 
{
    if (get_post_type($post_id) === 'product') {
        perform_custom_deletion($post_id);
    }
}

// Handle draft status change action
function custom_action_on_status_change($new_status, $old_status, $post)
{
    if ($post->post_type === 'product' && $new_status === 'draft') {
        perform_custom_deletion($post->ID);
    }
}
function perform_custom_deletion($post_id)
{
    global $wpdb;
    $lang = getSiteCurrentLang();
    $product = wc_get_product($post_id);

    if ($product) {
        $art_no = $product->get_sku();

        //Title delete

        $del_title = $wpdb->get_row($wpdb->prepare("SELECT * FROM `taw_article_title` 
        WHERE `art_no` = %s AND `lang` = %s", $art_no,$lang));
         
        //   print_r($del_title );
        //   exit;

        if ($del_title) {
            $result_title = $wpdb->delete('taw_article_title',array('id' => $del_title->id),array('%s'));
        }

         //Category delete  

        $del_Category = $wpdb->get_row($wpdb->prepare("SELECT * FROM `taw_article_category` 
        WHERE `art_no` = %s AND `lang` = %s", $art_no,$lang));

        if ($del_Category) {
            $result_Category = $wpdb->delete('taw_article_category',array('id' => $del_Category->id),array('%s'));
        }

         
         //Price delete

        $del_price = $wpdb->get_row($wpdb->prepare("SELECT * FROM `taw_article_price` 
        WHERE `art_no` = %s", $art_no));

        if ($del_price) {
            $result_price = $wpdb->delete('taw_article_price',array('id' => $del_price->id),array('%s'));
        }

         //Picture delete

        $del_picture = $wpdb->get_row($wpdb->prepare("SELECT * FROM `taw_article_picture` 
        WHERE `art_no` = %s AND `lang` = %s", $art_no,$lang));

        if ($del_picture) {
            $result_picture = $wpdb->delete('taw_article_picture',array('id' => $del_picture->id),array('%s'));
        }
        //Attribute delete

        $del_attribute = $wpdb->get_row($wpdb->prepare("SELECT * FROM `taw_article_attributes` 
        WHERE `art_no` = %s", $art_no));

        if ($del_attribute) {
            $result_attribute = $wpdb->delete('taw_article_attributes',array('art_no' => $art_no),array('%s'));
        }

         //Customer unique price delete

        $del_uniqueprice = $wpdb->get_row($wpdb->prepare("SELECT * FROM `taw_customer_unique_price` 
        WHERE `art_no` = %s", $art_no));

        if ($del_uniqueprice) {
            $result_uniqueprice = $wpdb->delete('taw_customer_unique_price',array('art_no' => $art_no),array('%s'));
        }

         //Accessories delete

        $del_Accessories = $wpdb->get_row($wpdb->prepare("SELECT * FROM `taw_product_accessories` 
        WHERE `parent_article` = %s", $art_no));

        if ($del_Accessories) {
            $result_Accessories = $wpdb->delete('taw_product_accessories',array('parent_article' => $art_no),array('%s'));
        }

         //Diagram delete

         $del_diagram = $wpdb->get_row($wpdb->prepare("SELECT * FROM `taw_diagram` 
         WHERE `art_no` = %s", $art_no));
 
         if ($del_diagram) {
             $result_diagram = $wpdb->delete('taw_diagram',array('art_no' => $art_no),array('%s'));
         }

         //Spareparts delete

         $del_spareparts = $wpdb->get_row($wpdb->prepare("SELECT * FROM `taw_product_spareparts` 
         WHERE `parent_article` = %s", $art_no));
 
         if ($del_spareparts) {
             $result_spareparts = $wpdb->delete('taw_product_spareparts',array('parent_article' => $art_no),array('%s'));
         }
        
    }
}

add_action('untrash_post', 'restore_product_to_custom_table', 10, 1);

function restore_product_to_custom_table($post_id) {
   
    global $wpdb;
    $lang = getSiteCurrentLang();
    $product = wc_get_product($post_id);

    if (get_post_type($post_id) === 'product') 
    {

        $art_no = $product->get_sku();
        $title = $product->get_name();
        $desc = $product->get_description();
        $shortdesc = $product->get_short_description();
        $attach_id = $product->get_image_id();
       // $pic_name = get_the_title($attach_id);
        $gallery_pics = implode(',', $product->get_gallery_image_ids());
        $alt_text = get_post_meta($attach_id, '_wp_attachment_image_alt', true);
        $product_attributes = $product->get_attributes();
        $categories = $product->get_category_ids();

        // Escape values to prevent SQL injection
        $art_no = esc_sql($art_no);
        $title = esc_sql($title);
        $desc = esc_sql($desc);
        $shortdesc = esc_sql($shortdesc);
        $lang = esc_sql($lang);
        $attach_id = esc_sql($attach_id);
       // $pic_name = esc_sql($pic_name);
        $gallery_pics = esc_sql($gallery_pics);
        $alt_text = esc_sql($alt_text);

        if(!empty($attach_id)){
            $pic_file_path = get_post_meta($attach_id, '_wp_attached_file', true);
            if (!empty($pic_file_path)) {
                $pic_name = basename($pic_file_path);
            }
        }
        

        //Restore Title
 
        $restore_title = $wpdb->get_row($wpdb->prepare("SELECT * FROM `taw_article_title` 
        WHERE `art_no` = %s AND `lang` = %s",$art_no,$lang));
       
            
        if (!$restore_title) {
            if(!empty( $pic_name) || !empty( $gallery_pics) || !empty($attach_id)){
            $wpdb->insert('taw_article_title',
            array('art_no' => $art_no,'title' => $title,'desc' => $desc,'lang' => $lang,'uuid' => $lang . '::' . $art_no,'shortdesc' => $shortdesc),
                array('%s', '%s', '%s', '%s', '%s', '%s'));
        }}
      

        //Restore Category

        $restore_category = $wpdb->get_row($wpdb->prepare("SELECT * FROM `taw_article_category` 
        WHERE `art_no` = %s AND `lang` = %s", $art_no, $lang));

        if (!empty($categories)) {
            if (!$restore_category) {
                $parentCategories = [];
                $childCategories = [];

                $parentidvalues = [];
                $childidvalues = [];

                foreach ($categories as $categoryId) {
                    $parentquery = "SELECT term.slug FROM tsm_terms as term 
                                INNER JOIN tsm_term_taxonomy as taxonomy ON term.term_id = taxonomy.term_id
                                WHERE term.term_id = $categoryId AND taxonomy.parent = 0";

                    $childquery = "SELECT term.slug FROM tsm_terms as term 
                                INNER JOIN tsm_term_taxonomy as taxonomy ON term.term_id = taxonomy.term_id
                                WHERE term.term_id = $categoryId AND taxonomy.parent != 0";

                    $parentSlug = $wpdb->get_var($parentquery);
                    $childSlug = $wpdb->get_var($childquery);

                    if ($parentSlug) {
                        $parentCategories[] = $parentSlug;
                    }

                    if ($childSlug) {
                        $childCategories[] = $childSlug;
                    }


                    $parentidquery = "SELECT term.term_id FROM tsm_terms as term 
                INNER JOIN tsm_term_taxonomy as taxonomy ON term.term_id = taxonomy.term_id
                WHERE term.term_id = $categoryId AND taxonomy.parent = 0";

                    $childidquery = "SELECT term.term_id FROM tsm_terms as term 
                INNER JOIN tsm_term_taxonomy as taxonomy ON term.term_id = taxonomy.term_id
                WHERE term.term_id = $categoryId AND taxonomy.parent != 0";

                    $parentidvalue = $wpdb->get_var($parentidquery);
                    $childidvalue = $wpdb->get_var($childidquery);

                    if ($parentidvalue) {
                        $parentidvalues[] = $parentidvalue;
                    }

                    if ($childidvalue) {
                        $childidvalues[] = $childidvalue;
                    }
                }

                $child = str_replace('-sv', '', $childCategories);
                $parent = str_replace('-sv', '', $parentCategories);

                $parentslug = implode('|', $parent);
                $childslug = implode('|', $child);

                $parentid = implode(',', $parentidvalues);
                $childid = implode(',', $childidvalues);

                // $uniqucode = "$art_no::$lang::$childslug>>$parentslug";
                $uniqucode = "$lang::$art_no";

                $restore_cate = "INSERT INTO `taw_article_category` (`art_no`, `term_id`, `lang`, `parent_cate`, `unique_code`)
                            VALUES ('$art_no', '$childid', '$lang', '$parentid', '$uniqucode')";

                $wpdb->query($restore_cate);
            }
        }
        //Restore Picture

        $restore_picture = $wpdb->get_row($wpdb->prepare("SELECT * FROM `taw_article_picture` 
        WHERE `art_no` = %s AND `lang` = %s", $art_no,$lang));

        if (!$restore_picture) {
            if(!empty( $pic_name) || !empty( $gallery_pics) || !empty($attach_id)){
                $query_pic = "INSERT INTO `taw_article_picture` (`art_no`, `pic_name`, `colour`, `alt_text`, `attach_id`, `lang`, `unique_code`, `gallery_pics`)
                        VALUES ('$art_no', '$pic_name', '', '$alt_text', '$attach_id', '$lang', '$lang::$art_no', '$gallery_pics')";
                         $wpdb->query($query_pic);
            }   
           
        }

        //Restore Price

        $restore_price = $wpdb->get_row($wpdb->prepare("SELECT * FROM `taw_article_price` 
        WHERE `art_no` = %s", $art_no));

        if (!$restore_price) {
        $post_meta = $wpdb->get_row("SELECT * FROM `tsm_postmeta` WHERE `post_id` = '$post_id' and `meta_key`='taw_prod_opt'");       
        
        $meta_value = unserialize($post_meta->meta_value);  
        $price_b2b=$meta_value['article_price']['b2b'];
        $price_reseller_sek = $meta_value['article_price']['reseller_sek'];
        $price_reseller_eur = $meta_value['article_price']['reseller_eur'];

        $meta_value = esc_sql($meta_value);
        $price_b2b = esc_sql($price_b2b);
        $price_reseller_sek = esc_sql($price_reseller_sek);
        $price_reseller_eur = esc_sql($price_reseller_eur);

        $wpdb->insert('taw_article_price',
        array('art_no' => $art_no,'price_b2b' => $price_b2b,'price_reseller_eur' => $price_reseller_eur,'price_reseller_sek' => $price_reseller_sek),
        array('%s', '%s', '%s', '%s'));
        }
       

        //Restore Attribute

        $restore_attribute = $wpdb->get_row($wpdb->prepare("SELECT * FROM `taw_article_attributes` 
        WHERE `art_no` = %s", $art_no));

        if (!empty($product_attributes)) {

            if (!$restore_attribute) {
                foreach ($product_attributes as $attribute_name => $attribute) {
                    $attr_id = $attribute->get_name();
                    $term_ids = $attribute->get_terms();
                
                    if ($term_ids) {
                        $term_ids_str = implode(',', wp_list_pluck($term_ids, 'slug'));
                        $wpdb->insert('taw_article_attributes',
                        array('art_no' => $art_no,'attr_id' => $attr_id,'term_ids' => $term_ids_str),
                        array('%s', '%s', '%s'));                    
                    }
                }
            }
        }

        // Restore Uniqueprice
        
        $restore_uniqueprice = $wpdb->get_row($wpdb->prepare("SELECT * FROM `taw_customer_unique_price` 
        WHERE `art_no` = %s", $art_no));

        if (!$restore_uniqueprice) {
if (is_array($meta_value) && isset($meta_value['article_price']['customer_price'])) {

            $customer = $meta_value['article_price']['customer_price'];
            foreach($customer as $c){
                $customerid=explode('::',$c['customer'])[0];
                $customerprice=$c['price'];
                $customercurrency=$c['currency'];

                $wpdb->insert('taw_customer_unique_price',
                array('customer_no' => $customerid,'price' => $customerprice,'currency' => $customercurrency,'art_no' => $art_no),
                array('%s', '%s', '%s', '%s'));
}
            }
        }

//         //Restore Accessories

//         $restore_Accessories = $wpdb->get_row($wpdb->prepare("SELECT * FROM `taw_product_accessories` 
//         WHERE `parent_article` = %s", $art_no));
//         if (!$restore_Accessories) {
//         $accessories = $meta_value['article_price']['AccessoriesArticle'];
//         foreach ($accessories as $a) {
//             $acs_article = $a['acs_article'];
//             $pallets = $a['pallet'];

//             $wpdb->insert('taw_product_accessories',
//                 array('parent_article' => $art_no,'acs_article' => $acs_article,'no_plates' => $pallets),
//                 array('%s', '%s', '%s'));
//         }
                
//     }
 }
}
function sync_product_price($page_num, $cur_lang)
{
    global $wpdb;

    $page_item = 50;
    $page_start = $page_num * $page_item;
    $count = $wpdb->get_var("SELECT COUNT(*) as count FROM `taw_article_title` where lang='$cur_lang';");

    $q = "SELECT art_no FROM `taw_article_title` where lang='$cur_lang' ORDER BY `id` limit $page_start,$page_item;";

    $res = $wpdb->get_results($q);
    foreach ($res as $r) {
        $sku = $r->art_no;
        if (empty($sku)) {
            continue;
        }
        // $id = wc_get_product_id_by_sku($sku);
        $id = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1", $sku ) );
        if (empty($id)) {
            continue;
        }

        //get product id by lang
        $id = getLanguageProductId($id, $cur_lang);
        if (empty($id)) {
            continue;
        }

        $meta = get_post_meta($id, 'taw_prod_opt', true);
        if (!isset($meta['article_price']) || !is_array($meta['article_price'])) {
            $meta['article_price'] = [];
        }

        //collect price
        $q = "SELECT price_b2b,price_reseller_eur,price_reseller_sek FROM `taw_article_price` where art_no='$sku' limit 1";
        $price_data = $wpdb->get_results($q);
        if (!empty($price_data)) {
            $price_data = $price_data[0];
            $meta['article_price']['b2b'] = $price_data->price_b2b;
            $meta['article_price']['reseller_eur'] = $price_data->price_reseller_eur;
            $meta['article_price']['reseller_sek'] = $price_data->price_reseller_sek;
        }

        //collect special price for customer
        // $q = "SELECT customer_no,price,usr.display_name FROM `taw_customer_unique_price` as cpz left join tsm_users as usr on usr.user_login=cpz.customer_no WHERE cpz.art_no='$sku'";
        // $customer_data = $wpdb->get_results($q);


        // if (!isset($meta['article_price']['customer_price'])) {
        //     $meta['article_price']['customer_price'] = [];
        // }
        // $customer = [];
        // foreach ($customer_data as $c) {
        //     $meta['article_price']['customer_price'][] = ["customer" => $c->customer_no . "::" . $c->display_name, "price" => $c->price];
        // }

        // $meta['article_price']['customer_price'] = array_unique($meta['article_price']['customer_price']);

        update_post_meta($id, 'taw_prod_opt', $meta);
    }

    $res_count = count($res);

    $end = 1;
    $percentage = "0";
    if ($res_count > 0) {
        $end = 0;
        if ($page_start > 0) {
            $percentage = floor($page_start / ($count / 100));
            if ($percentage >= 100) {
                $end = 1;
            }
        }
    }

    $page_num = $page_num + 1;

    return ["page_num" => $page_num, "end" => $end, "percentage" => $percentage];
}

function sync_product_accessories($page_num, $cur_lang)
{
    global $wpdb;

    $page_item = 50;
    $page_start = $page_num * $page_item;
    $count = $wpdb->get_var("SELECT COUNT(*) as count FROM `taw_article_title` where lang='$cur_lang';");

    $q = "SELECT art_no FROM `taw_article_title` where lang='$cur_lang' ORDER BY `id` limit $page_start,$page_item;";

    $res = $wpdb->get_results($q);
  
    foreach ($res as $r) {
        $sku = $r->art_no;
        if (empty($sku)) {
            continue;
        }
        // $id = wc_get_product_id_by_sku($sku);
        $id = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1", $sku ) );
        if (empty($id)) {
            continue;
        }
        
        //get product id by lang
        $id = getLanguageProductId($id, $cur_lang);
        if (empty($id)) {
            continue;
        }

        // Fetch data from taw_product_accessories
        $accessories_data = $wpdb->get_results($wpdb->prepare("SELECT acs_article, no_plates FROM `taw_product_accessories` WHERE parent_article='%s'", $sku));

        // Get the existing meta
        $meta = get_post_meta($id, 'taw_prod_opt', true);

        if (!is_array($meta)) {
            $meta = [];
        }

        if (!isset($meta['article_price']['AccessoriesArticle'])) {
            $meta['article_price']['AccessoriesArticle'] = [];
        }

        // Create a new array to store the updated AccessoriesArticle entries
        $new_accessories_array = [];

        foreach ($accessories_data as $accessory) {
            $new_accessories_array[] = [
                'acs_article' => $accessory->acs_article,
                'pallet' => $accessory->no_plates,
            ];
        }

        // Replace the existing AccessoriesArticle array with the new array
        $meta['article_price']['AccessoriesArticle'] = $new_accessories_array;

        // Update the taw_prod_opt meta
        update_post_meta($id, 'taw_prod_opt', $meta);
    }

    $res_count = count($res);

    $end = 1;
    $percentage = "0";
    if ($res_count > 0) {
        $end = 0;
        if ($page_start > 0) {
            $percentage = floor($page_start / ($count / 100));
            if ($percentage >= 100) {
                $end = 1;
            }
        }
    }

    $page_num = $page_num + 1;

    return ["page_num" => $page_num, "end" => $end, "percentage" => $percentage];
}

function sync_spareparts($page_num, $cur_lang)
{
    global $wpdb;

    $page_item = 50;
    $page_start = $page_num * $page_item;
    $count = $wpdb->get_var("SELECT COUNT(*) as count FROM `taw_article_title` where lang='$cur_lang';");

    $q = "SELECT art_no FROM `taw_article_title` where lang='$cur_lang' ORDER BY `id` limit $page_start,$page_item;";

    $res = $wpdb->get_results($q);
    $top_values = [];
    foreach ($res as $r) {
        $sku = $r->art_no;
        if (empty($sku)) {
            continue;
        }
        // $id = wc_get_product_id_by_sku($sku);
        $id = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1", $sku ) );
        if (empty($id)) {
            continue;
        }
        
        //get product id by lang
        $id = getLanguageProductId($id, $cur_lang);
        if (empty($id)) {
            continue;
        }
        // $sku = '13-04'; // Replace with your actual parent article
        // $id = '28450';

        // Fetch data from taw_product_accessories
        $spareparts_data = $wpdb->get_results($wpdb->prepare("SELECT spare_article, min_qty FROM `taw_product_spareparts` WHERE parent_article=%s", $sku));

        $existing_img_pointer_data = $wpdb->get_row($wpdb->prepare("SELECT * FROM `tsm_postmeta` WHERE `post_id` = %d AND `meta_key` = '_wpii_prod_img_pointer_info'", $id), ARRAY_A);
        
        if ($existing_img_pointer_data) 
        {
            $img_pointer_data = json_decode($existing_img_pointer_data['meta_value'], true);
        
            if ($img_pointer_data && isset($img_pointer_data['data']) && is_array($img_pointer_data['data'])) {
               

               // Create a new array to store the updated _wpii_prod_img_pointer_info entries
                $new_img_pointer_array = [];
        
                // Track existing spare_articles using keys to avoid duplicates
                $existing_spare_articles = [];
        
                foreach ($img_pointer_data['data'] as $existing_entry) {
                    $existing_spare_articles[$existing_entry['txt']] = $existing_entry;
                }
        
                foreach ($spareparts_data as $sparepart) {
                    $spare_article = $sparepart->spare_article;
                    $min_qty = $sparepart->min_qty;
        
                    // Check if spare_article already exists in _wpii_prod_img_pointer_info data
                    if (isset($existing_spare_articles[$spare_article])) {
                        // Move the existing entry to the start of the array only if not already moved
                        if (!isset($existing_spare_articles[$spare_article]['moved'])) {
                            $new_img_pointer_array[] = $existing_spare_articles[$spare_article];
                            $existing_spare_articles[$spare_article]['moved'] = true;
                        }
                    }
                }
        
                // Add the new spare_articles to the array
                foreach ($spareparts_data as $sparepart) {
                    $spare_article = $sparepart->spare_article;
                    $min_qty = $sparepart->min_qty;
        
                    // Check if spare_article already exists in new_img_pointer_array
                    if (!isset($existing_spare_articles[$spare_article]) || !isset($existing_spare_articles[$spare_article]['moved'])) {
                        $new_img_pointer_array[] = [
                            'txt' => $spare_article,
                            'top' => isset($top_values[$sku]) ? $top_values[$sku] : 0,
                            'left' => 0,
                            'qty' => $min_qty,
                        ];
                        $top_values[$sku] = isset($top_values[$sku]) ? $top_values[$sku] + 30 : 30;
                    }
                }
        
                // Update the _wpii_prod_img_pointer_info meta
                $wpdb->update(
                    'tsm_postmeta',
                    array('meta_value' => json_encode(['imgId' => $img_pointer_data['imgId'], 'data' => $new_img_pointer_array])),
                    array('meta_id' => $existing_img_pointer_data['meta_id'])
                );
                
            }else{
                $new_img_pointer_array = [];

                // Initialize top value
                $top_value = 0;

                foreach ($spareparts_data as $sparepart) {
                    $spare_article = $sparepart->spare_article;
                    $min_qty = $sparepart->min_qty;

                    // Add the formatted entry to the array
                    $new_img_pointer_array[] = [
                        'txt' => $spare_article,
                        'top' => $top_value,
                        'left' => 0,
                        'qty' => $min_qty,
                    ];

                    // Update top value for the next entry
                    $top_value += 30;
                }

                // Create the new meta value
                $new_meta_value = json_encode(['imgId' => '', 'data' => $new_img_pointer_array]);
                //print_r($new_meta_value);

                //Insert the new row into tsm_postmeta
                // Check if the row already exists in tsm_postmeta
                $existing_row = $wpdb->get_row(
                    $wpdb->prepare(
                        "SELECT * FROM `tsm_postmeta` WHERE `post_id` = %d AND `meta_key` = '_wpii_prod_img_pointer_info'",
                        $id
                    ),
                    ARRAY_A
                );

                if ($existing_row) {
                    // Update the existing row
                    $wpdb->update(
                        'tsm_postmeta',
                        array('meta_value' => $new_meta_value),
                        array('meta_id' => $existing_row['meta_id'])
                    );
                } else {
                    // Insert the new row
                    $wpdb->insert(
                        'tsm_postmeta',
                        array(
                            'post_id'    => $id,
                            'meta_key'   => '_wpii_prod_img_pointer_info',
                            'meta_value' => $new_meta_value,
                        ),
                        array('%d', '%s', '%s')
                    );
            }           
                                

                }
            // exit;
            

        }else {
            $new_img_pointer_array = [];

                // Initialize top value
                $top_value = 0;

                foreach ($spareparts_data as $sparepart) {
                    $spare_article = $sparepart->spare_article;
                    $min_qty = $sparepart->min_qty;

                    // Add the formatted entry to the array
                    $new_img_pointer_array[] = [
                        'txt' => $spare_article,
                        'top' => $top_value,
                        'left' => 0,
                        'qty' => $min_qty,
                    ];

                    // Update top value for the next entry
                    $top_value += 30;
                }

                // Create the new meta value
                $new_meta_value = json_encode(['imgId' => '', 'data' => $new_img_pointer_array]);
            // Insert the new row
            $wpdb->insert(
                'tsm_postmeta',
                array(
                    'post_id'    => $id,
                    'meta_key'   => '_wpii_prod_img_pointer_info',
                    'meta_value' => $new_meta_value,
                ),
                array('%d', '%s', '%s')
            );
        }    
        // // Get the existing meta
        $meta = get_post_meta($id, 'taw_prod_opt', true);

        if (!is_array($meta)) {
            $meta = [];
        }

        if (!isset($meta['article_price']['SparepartsArticle'])) {
            $meta['article_price']['SparepartsArticle'] = [];
        }

        // Create a new array to store the updated SparepartsArticle entries
        $new_spareparts_array = [];

        foreach ($spareparts_data as $spares) {
            $new_spareparts_array[] = [
                'spare_article' => $spares->spare_article,
                'min_qty' => $spares->min_qty,
            ];
            
        }

        // Replace the existing SparepartsArticle array with the new array
        $meta['article_price']['SparepartsArticle'] = $new_spareparts_array;

        // Update the taw_prod_opt meta
        update_post_meta($id, 'taw_prod_opt', $meta);


    }

    $res_count = count($res);

    $end = 1;
    $percentage = "0";
    if ($res_count > 0) {
        $end = 0;
        if ($page_start > 0) {
            $percentage = floor($page_start / ($count / 100));
            if ($percentage >= 100) {
                $end = 1;
            }
        }
    }

    $page_num = $page_num + 1;

    return ["page_num" => $page_num, "end" => $end, "percentage" => $percentage];
}
function sync_product_customeruniqueprice($page_num, $cur_lang)
{
    global $wpdb;

    $page_item = 50;
    $page_start = $page_num * $page_item;
    $count = $wpdb->get_var("SELECT COUNT(*) as count FROM `taw_article_title` where lang='$cur_lang';");

    $q = "SELECT art_no FROM `taw_article_title` where lang='$cur_lang' ORDER BY `id` limit $page_start,$page_item;";

    $res = $wpdb->get_results($q);
  
    foreach ($res as $r) {
        $sku = $r->art_no;
        if (empty($sku)) {
            continue;
        }
        // $id = wc_get_product_id_by_sku($sku);
        $id = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1", $sku ) );
        if (empty($id)) {
            continue;
        }
        
        //get product id by lang
        $id = getLanguageProductId($id, $cur_lang);
        if (empty($id)) {
            continue;
        }

        // Fetch data from taw_product_accessories
        $customer_price_data = $wpdb->get_results($wpdb->prepare("SELECT customer_no, price, currency, usr.display_name FROM `taw_customer_unique_price` as cpz left join `tsm_users` as usr on usr.user_login=cpz.customer_no WHERE cpz.art_no='%s'", $sku));
        // $q = "SELECT customer_no,price,usr.display_name FROM `taw_customer_unique_price` as cpz left join tsm_users as usr on usr.user_login=cpz.customer_no WHERE cpz.art_no='$sku'";

        // Get the existing meta
        $meta = get_post_meta($id, 'taw_prod_opt', true);

        if (!is_array($meta)) {
            $meta = [];
        }

        if (!isset($meta['article_price']['customer_price'])) {
            $meta['article_price']['customer_price'] = [];
        }

        // Create a new array to store the updated AccessoriesArticle entries
        $new_customer_price_array = [];

        foreach ($customer_price_data as $customer_price) {
            $new_customer_price_array[] = [
                'customer' => $customer_price->customer_no . "::" . $customer_price->display_name,
                'price' => $customer_price->price,
                'currency' => $customer_price->currency,
            ];
        }

        // Replace the existing AccessoriesArticle array with the new array
        $meta['article_price']['customer_price'] = $new_customer_price_array;

        // Update the taw_prod_opt meta
        update_post_meta($id, 'taw_prod_opt', $meta);
    }

    $res_count = count($res);

    $end = 1;
    $percentage = "0";
    if ($res_count > 0) {
        $end = 0;
        if ($page_start > 0) {
            $percentage = floor($page_start / ($count / 100));
            if ($percentage >= 100) {
                $end = 1;
            }
        }
    }

    $page_num = $page_num + 1;

    return ["page_num" => $page_num, "end" => $end, "percentage" => $percentage];
}

function sync_product_category($page_num, $cur_lang)
{

    global $wpdb;

    $page_item = 50;
    $page_start = $page_num * $page_item;
    $count = $wpdb->get_var("SELECT COUNT(*) as count FROM `taw_article_title` where lang='$cur_lang';");

    $q = "SELECT art_no FROM `taw_article_title` where lang='$cur_lang'  ORDER BY `id` limit $page_start,$page_item;";

    $res = $wpdb->get_results($q);

    foreach ($res as $r) {
        $sku = $r->art_no;
        if (empty($sku)) {
            continue;
        }
        $id = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1", $sku ) );
        // $id = wc_get_product_id_by_sku($sku);
        if (empty($id)) {
            continue;
        }

        $product = getCurrentLangProductBySku($sku, $cur_lang);

        if (empty($product)) {
            continue;
        }

        //upate category
            $cate_ids = $wpdb->get_var("SELECT GROUP_CONCAT(term_id) as term_ids FROM `taw_article_category` WHERE art_no='$sku' and lang='$cur_lang' GROUP by art_no");
            $part_ids = $wpdb->get_var("SELECT GROUP_CONCAT(parent_cate) as term_ids FROM `taw_article_category` WHERE art_no='$sku' and lang='$cur_lang' GROUP by art_no");

            $cate_ids = explode(",", $cate_ids);
            $part_ids = explode(",", $part_ids);
            
            // Merge the two arrays
            $category_ids = array_merge($cate_ids, $part_ids);
            
            // Remove any duplicate values
            $category_ids = array_unique($category_ids);
          
            if (!empty($category_ids)) {
                $product->set_category_ids($category_ids);
            }
            $product->save();
    }

    $res_count = count($res);

    $end = 1;
    $percentage = "0";
    if ($res_count > 0) {
        $end = 0;
        if ($page_start > 0) {
            $percentage = floor($page_start / ($count / 100));
            if ($percentage >= 100) {
                $end = 1;
            }
        }
    }

    $page_num = $page_num + 1;

    return ["page_num" => $page_num, "end" => $end, "percentage" => $percentage];
}

function sync_product_image($page_num, $cur_lang)
{

    global $wpdb;

    $page_item = 50;
    $page_start = $page_num * $page_item;
    $count = $wpdb->get_var("SELECT COUNT(*) as count FROM `taw_article_picture` where lang='$cur_lang';");

    $q = "SELECT art_no,attach_id,gallery_pics FROM `taw_article_picture` where lang='$cur_lang' ORDER BY `id` limit $page_start,$page_item;";

    $res = $wpdb->get_results($q);

    foreach ($res as $r) {
        $sku = $r->art_no;
        if (empty($sku)) {
            continue;
        }
           
        $id = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1", $sku ) );
        
        // $id = wc_get_product_id_by_sku($sku);
              
        if (empty($id)) {
            continue;
        }

        $product = getCurrentLangProductBySku($sku, $cur_lang);
        if (empty($product)) {
            continue;
        }

        //update picture
        if (!empty($r->attach_id)) {
            $product->set_image_id($r->attach_id);
            $product->save();
            //save gallery images
            update_post_meta($product->get_id(), '_product_image_gallery', $r->gallery_pics);
        }
    }

    $res_count = count($res);

    $end = 1;
    $percentage = "0";
    if ($res_count > 0) {
        $end = 0;
        if ($page_start > 0) {
            $percentage = floor($page_start / ($count / 100));
            if ($percentage >= 100) {
                $end = 1;
            }
        }
    }

    $page_num = $page_num + 1;

    return ["page_num" => $page_num, "end" => $end, "percentage" => $percentage];
}

function sync_diagram($page_num, $cur_lang)
{

    global $wpdb;

    $page_item = 50;
    $page_start = $page_num * $page_item;
    $count = $wpdb->get_var("SELECT COUNT(*) as count FROM `taw_diagram` where lang='$cur_lang';");

    $q = "SELECT art_no, diagram_id, diagram2_id, diagram3_id FROM `taw_diagram` where lang='$cur_lang' ORDER BY `id` limit $page_start,$page_item;";

    $res = $wpdb->get_results($q);

    foreach ($res as $r) {
        $sku = $r->art_no;
        if (empty($sku)) {
            continue;
        }
    
        $id = $wpdb->get_var($wpdb->prepare("SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1", $sku));
    
        if (empty($id)) {
            continue;
        }
    
        $product = getCurrentLangProductBySku($sku, $cur_lang);
        if (empty($product)) {
            continue;
        }
    
        // Initialize the meta array
        $meta = get_post_meta($product->get_id(), 'taw_prod_opt', true);
        if (!is_array($meta)) {
            $meta = [];
        }
    
        // Initialize the product_diagram_file array
        $product_diagram_file = [
            'url' => '',
            'id' => '',
            'width' => '',
            'height' => '',
            'thumbnail' => '',
            'alt' => '',
            'title' => '',
            'description' => '',
        ];

        // Initialize the product_diagram_file array
        $product_diagram_file2 = [
            'url' => '',
            'id' => '',
            'width' => '',
            'height' => '',
            'thumbnail' => '',
            'alt' => '',
            'title' => '',
            'description' => '',
        ];

        // Initialize the product_diagram_file array
        $product_diagram_file3 = [
            'url' => '',
            'id' => '',
            'width' => '',
            'height' => '',
            'thumbnail' => '',
            'alt' => '',
            'title' => '',
            'description' => '',
        ];
    
        // Update product_diagram_file if the corresponding diagram is not empty
        if (!empty($r->diagram_id)) {
            $diagram_metadata = wp_get_attachment_metadata($r->diagram_id);
    
            if ($diagram_metadata) {
                $product_diagram_file = [
                    'url' => wp_get_attachment_url($r->diagram_id),
                    'id' => $r->diagram_id,
                    'width' => $diagram_metadata['width'],
                    'height' => $diagram_metadata['height'],
                    'thumbnail' => wp_get_attachment_thumb_url($r->diagram_id),
                    'alt' => get_post_meta($r->diagram_id, '_wp_attachment_image_alt', true),
                    'title' => get_the_title($r->diagram_id),
                    'description' => '',
                ];
            }
        }

        if (!empty($r->diagram2_id)) {
            $diagram2_metadata = wp_get_attachment_metadata($r->diagram2_id);
    
            if ($diagram2_metadata) {
                $product_diagram_file2 = [
                    'url' => wp_get_attachment_url($r->diagram2_id),
                    'id' => $r->diagram2_id,
                    'width' => $diagram_metadata['width'],
                    'height' => $diagram_metadata['height'],
                    'thumbnail' => wp_get_attachment_thumb_url($r->diagram2_id),
                    'alt' => get_post_meta($r->diagram2_id, '_wp_attachment_image_alt', true),
                    'title' => get_the_title($r->diagram2_id),
                    'description' => '',
                ];
            }
        }

        if (!empty($r->diagram3_id)) {
            $diagram3_metadata = wp_get_attachment_metadata($r->diagram3_id);
    
            if ($diagram3_metadata) {
                $product_diagram_file3 = [
                    'url' => wp_get_attachment_url($r->diagram3_id),
                    'id' => $r->diagram3_id,
                    'width' => $diagram_metadata['width'],
                    'height' => $diagram_metadata['height'],
                    'thumbnail' => wp_get_attachment_thumb_url($r->diagram3_id),
                    'alt' => get_post_meta($r->diagram3_id, '_wp_attachment_image_alt', true),
                    'title' => get_the_title($r->diagram3_id),
                    'description' => '',
                ];
            }
        }
    
        // Add or update the product_diagram_file within the article_price array
        $meta['article_price']['product_diagram_file'] = $product_diagram_file;
        $meta['article_price']['product_diagram_file2'] = $product_diagram_file2;
        $meta['article_price']['product_diagram_file3'] = $product_diagram_file3;


    
        // Update the taw_prod_opt meta
        update_post_meta($product->get_id(), 'taw_prod_opt', $meta);
    }
    $res_count = count($res);

    $end = 1;
    $percentage = "0";
    if ($res_count > 0) {
        $end = 0;
        if ($page_start > 0) {
            $percentage = floor($page_start / ($count / 100));
            if ($percentage >= 100) {
                $end = 1;
            }
        }
    }

    $page_num = $page_num + 1;

    return ["page_num" => $page_num, "end" => $end, "percentage" => $percentage];
}

function getCurrentLangProductBySku($sku, $cur_lang)
{ 
    global $wpdb;
    $id = $wpdb->get_var( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_sku' AND meta_value='%s' LIMIT 1", $sku ) );

    // $id = wc_get_product_id_by_sku($sku);
    $product = null;
    if (empty($id)) {
        return $product;
    }

    if ($cur_lang == "en") {
        $product = wc_get_product($id);
    } else {

        $cur_id = getLanguageProductId($id, $cur_lang);
        if (empty($cur_id)) {
            return $product;
        } else {
            $product = wc_get_product($cur_id);
        }
    }

    return $product;
}

// Simple, grouped and external products
add_filter('woocommerce_product_get_price', 'custom_price', 90, 2);
add_filter('woocommerce_product_get_regular_price', 'custom_price', 90, 2);
// Variations 
add_filter('woocommerce_product_variation_get_regular_price', 'custom_price', 90, 2);
add_filter('woocommerce_product_variation_get_price', 'custom_price', 90, 2);

// Variable (price range)
add_filter('woocommerce_variation_prices_price', 'custom_variable_price', 90, 3);
add_filter('woocommerce_variation_prices_regular_price', 'custom_variable_price', 90, 3);

// Handling price caching (see explanations at the end)
add_filter('woocommerce_get_variation_prices_hash', 'add_price_multiplier_to_variation_prices_hash', 90, 3);


## This goes outside the constructor ##

// Utility function to change the prices with a multiplier (number)


function custom_price($price, $product)
{
    return getCustomPrice($product, $price);
}

function custom_variable_price($price, $variation, $product)
{
    return getCustomPrice($product, $price);
}

function getCustomPrice($product, $price)
{
   // error_log('This is a custom log message.');


    if (!is_user_logged_in()) {
        return "0";
    }

    if (!(current_user_can('c_uam_cap_price') || current_user_can('c_uam_cap_reseller_price'))) {
        return "0";
    }

    global $wpdb;
    $meta = get_post_meta($product->get_ID(), 'taw_prod_opt', true);

    $user = wp_get_current_user();

    $logged_in_user_id = get_current_user_id();  // WordPress function to get the logged-in user ID
    $logged_in_user_role = wp_get_current_user()->roles[0];  // Assuming the first role is sufficient



    //check customer special price
    if (isset($meta['article_price']['customer_price'])) {
        $customers = $meta['article_price']['customer_price'];

        error_log(json_encode($customers));
        foreach ($customers as $c) {

            if ($user->user_login == explode("::", $c['customer'])[0]) {
                return $c['price'];
                break;
            }
        }
    }

    //check customer role price
    $key = "b2b";
    $role = "";
    foreach ($user->roles as $r) {
        $role = $r;
        break;
    }
    if (!empty($r)) {
        if ($r == TAW_ROLE_RESELLER_EUR) {
            $key = "reseller_eur";
        } else if ($r == TAW_ROLE_RESELLER_SEK) {
            $key = "reseller_sek";
        }
    }

        // New logic: Check if product's categories match restricted categories
        $categories = wp_get_post_terms($product->get_ID(), 'product_cat');  // Get the product's categories

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
            return '0'; // If restricted, return price as 0
        }

    if (isset($meta['article_price'][$key])) {
        return empty($meta['article_price'][$key]) ? 0 : $meta['article_price'][$key];
    }

    return $price;
}

function add_price_multiplier_to_variation_prices_hash($price_hash, $product, $for_display)
{
    $price_hash[] = getCustomPrice($product, 0);
    return $price_hash;
}

function getProductsByFilter()
{
    $data = array_merge((array) $_GET, (array) $_POST);
    include_once(__DIR__ . '/template/blk-product-item.php');
    exit();
}

add_action("wp_ajax_getProductsByFilter", 'getProductsByFilter');
add_action("wp_ajax_nopriv_getProductsByFilter", 'getProductsByFilter');

//product detail
//add_filter( 'woocommerce_single_product_image_thumbnail_html', 'single_product_image_thumbnail_html', 10, 2);
function single_product_image_thumbnail_html($html, $post_thumbnail_id)
{
    $holder = "woocommerce-product-gallery__image !flex flex-col justify-center items-center !h-[192px] sm:!h-[256px] lg:!h-[288px] xl:!h-[384px]";
    // $image = "!w-48 sm:!w-64 lg:!w-72 xl:!w-96 !h-auto";
    $image = "!max-h-[192px] sm:!max-h-[256px] lg:!max-h-[288px] xl:!max-h-[384px] !w-auto";
    $replaced = str_replace('woocommerce-product-gallery__image', $holder, $html);
    $replaced = str_replace('wp-post-image', $image, $replaced);
    return $replaced;
}

add_action('woocommerce_product_meta_end', 'product_meta_end');
function product_meta_end()
{
    global $product;
    $lang = apply_filters('wpml_current_language', NULL);
    $json = json_decode($product, true);
    $metat_data = get_post_meta($json['id'], 'taw_prod_opt', true);
    $article_price = $metat_data['article_price'] ?? [];
    $trans_dsheet =icl_t('TAW_TEXT_DOMAIN', 'Datasheet','Datasheet');
    $isDataSheetEnabled = 0;
    $datasheetFileId = 0;
    $datasheetFileName = '';
    if (array_key_exists("product_enable_datasheet", $article_price)) {
        $isDataSheetEnabled = $article_price['product_enable_datasheet'];
        $datasheetFileId = $article_price['product_datasheet_file']['id'];
        $datasheetFileName = $article_price['product_datasheet_file']['title'];
    }

    $isInstructionsEnabled = 0;
    $instructionsFileId = 0;
    $instructionsFileName = '';
    if (array_key_exists("product_enable_download", $article_price)) {
        $isInstructionsEnabled = $article_price['product_enable_download'];
        $instructionsFileId = $article_price['product_download_file']['id'];
        $instructionsFileName = $article_price['product_download_file']['title'];
    }

    $datasheet = THINGSATWEB_BASE . 'img/ic_datasheet.svg';
    $instructions = THINGSATWEB_BASE . 'img/ic_instructions.svg';
    $warehouse = THINGSATWEB_BASE . 'img/ic_warehouse.svg';
    $load_capacity = THINGSATWEB_BASE . 'img/ic_load_capacity.svg';
    $stepfile = THINGSATWEB_BASE . 'img/stepfilered.png';
    $addspareparts = THINGSATWEB_BASE . 'img/screw.png';


    $attributes = $product->get_attributes();

?>
    <div class="flex justify-center lg:justify-start mt-6">
        <?php if(current_user_can('c_uam_cap_data_sheet')):
        $meta  = get_post_meta($product->get_id(), 'taw_prod_opt');
        $meta = isset($meta[0]) ? $meta[0] : array();
        $datasheetcheck=$meta['article_price']['product_enable_download'];
        if($datasheetcheck=='1'){
        ?>

        <a href="https://smartstoring.eu/downloads/?id=<?php echo $product->get_id() ?>&print=generate_datasheet&user_id=<?php echo get_current_user_id();?>&lang=<?php echo $lang;?> " onclick="window.open(this.href, '_blank'); return false;">          
            <div class="flex flex-row items-center bg-red-600 rounded-full p-1 cursor-pointer">
                <div class="w-4 h-4 p-0.5 bg-white rounded-full flex items-center justify-center">
                    <img class="text-center" src=<?php echo $datasheet; ?> alt="Datasheet">
                </div>
                <span class="text-xs text-white px-1"><?php echo $trans_dsheet; ?></span>
            </div>
        </a>
        <?php } endif;
        ?>

       <?php if(current_user_can('c_uam_cap_step_file')):?>
        <?php 
        $product_id=$product->get_id();
        $product = wc_get_product($product_id);
        $meta = get_post_meta($product->get_id(), 'taw_prod_opt');
        $meta = isset($meta[0]) ? $meta[0] : array();
      //  $diagram = $meta['article_price']['product_step_file']['url'];
        if (
            isset($meta['article_price']) && isset($meta['article_price']['product_step_file']) &&
            isset($meta['article_price']['product_step_file']['url'])) {
            $diagram = $meta['article_price']['product_step_file']['url'];
        } else {
                $diagram = ''; 
        }
        if (!empty($diagram)) :
        ?>
        <a href="https://smartstoring.eu/downloads/<?php echo $product->get_id() ?>/stepfile?step=generate_stepfile">
            <div class="flex flex-row items-center bg-red-600 rounded-full p-1 cursor-pointer 
            <?php if ($isDataSheetEnabled == 1) : ?> ml-7 <?php endif; ?>">
                <div class="w-4 h-4 p-0.5 bg-white rounded-full flex items-center justify-center">
                    <img class="text-center" src="<?php echo $stepfile; ?>" alt="Instructions" style="padding:2px;">
                </div>
                <span class="text-xs text-white px-1">Step File</span>
            </div>
        </a>
        <?php endif;?>
        <?php endif;?>

        <?php if ($isInstructionsEnabled == 1 && current_user_can('c_uam_cap_instruction_pdf') && !empty($instructionsFileName)) : ?>
            <a href="https://smartstoring.eu/downloads/<?php echo $instructionsFileId; ?>/<?php echo $instructionsFileName; ?>">
                <div class="flex flex-row items-center bg-red-600 rounded-full p-1 cursor-pointer 
                    <?php if ($isDataSheetEnabled == 1) : ?> ml-7 <?php endif; ?>">
                    <div class="w-4 h-4 p-0.5 bg-white rounded-full flex items-center justify-center">
                        <img class="text-center" src=<?php echo $instructions; ?> alt="Instructions">
                    </div>
                    <span class="text-xs text-white px-1">Instructions</span>
                </div>
            </a>
        <?php endif; ?>
    </div>

    <!-- Warehouse and load capacity: -->
    
    <!-- <div class="flex justify-center lg:justify-start mt-6">
        <div class="flex flex-col items-center">
            <div class="w-6 h-auto">
                <img class="text-center" src=<?php // echo $warehouse; ?> alt="Warehousing">
            </div>
            <span class="text-xs font-light">Warehousing</span>
        </div>
        <?php

        // $pa_load = $attributes["pa_load"] ?? 0;
        // if (!empty($pa_load)) :
        //     $terms = $pa_load->get_terms();
        //     $load_name = wp_list_pluck($terms, 'name');
        //     $loadCapacity = $load_name[0];
        ?>
            <div class="flex flex-col items-center ml-7">
                <div class="w-6 h-auto">
                    <img class="text-center" src=<?php //echo $load_capacity; ?> alt="Weight">
                </div>
                <span class="text-xs font-light"><?php //echo $loadCapacity; ?> Load Capacity</span>
            </div>
        <?php // endif; ?>
    </div> -->

    <?php
    // $your_img_src = "";
    // $field = '_wpii_prod_img_pointer_info';
    // $item = get_post_meta($product->get_id(), $field);

    // if (!empty($item)) {
    //     $field = '_wpii_prod_img_pointer_info';
    //     $item = get_post_meta($product->get_id(), $field);
    //     if (!empty($item)) {
    //         $item = json_decode($item[0], true);
    //     }

    //     $list = [];
    //     if (!empty($item['data'])) {
    //         $list = $item['data'];
    //     }

    //     $your_img_src_id = "";
    //     if (!empty($item['imgId'])) {
    //         $your_img_src_id = $item['imgId'];
    //         $your_img_src = wp_get_attachment_image_src($your_img_src_id, 'full');
    //         if (!empty($your_img_src)) {
    //             $your_img_src = $your_img_src[0];
    //         }
    //     }
    // }
    // if (!empty($your_img_src)) :
    ?>
   
    <!-- <div class="flex justify-center lg:justify-start mt-6">
        <div class="flex flex-row items-center bg-red-600 rounded-full p-1 cursor-pointer" id="hide">
            <div class="w-4 h-4 p-0.5 bg-white rounded-full flex items-center justify-center">
                <img class="text-center" src=<?php //echo $addspareparts; ?> alt="Datasheet" style="padding:2px;">
            </div>
            <span class="text-xs text-white px-1" >Add Spareparts</span>
        </div>    
    </div> -->
    <?php // endif; ?>

    <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function(){
        $("#hide").click(function(){
        $("#spareparts").show();
        });
    });
    </script> -->

<?php
}

add_action('woocommerce_before_single_product_summary', 'before_single_product_summary');
function before_single_product_summary()
{
    $back = THINGSATWEB_BASE . 'img/ic_backward_arrow.svg';
    $translated_title1 = icl_t('TAW_TEXT_DOMAIN', 'Back', 'Back');
?>
    <div class="hidden md:flex items-center cursor-pointer mb-3" onclick="history.go(-1);">
        <div class="w-2 h-auto xl:block">
            <img src=<?php echo $back; ?> alt="Back">
        </div>
        <span class="ml-1 text-sm font-medium"><?php echo $translated_title1 ?></span>
    </div>
<?php
}

// add_filter('woocommerce_related_products', 'add_related_products');
function add_related_products($related_product_ids)
{
    global $product;
    global $wpdb;
    $sku = $product->get_sku();
    $sku = substr($sku, 0, 5);
    $q = "SELECT post_id FROM `tsm_postmeta` WHERE meta_key='_sku' and meta_value LIKE '%$sku%'";
    $result = $wpdb->get_results($q);
    $ids = array();
    foreach ($result as $key => $value) {
        if ($product->get_id() != $value->post_id) {
            $ids[] = $value->post_id;
        }
    }
    return $ids;
}

// Add the description (content) tab for a new product, so it can be edited with Elementor.
add_filter('woocommerce_product_tabs', function ($tabs) {
    global $product;
    $attributes = $product->get_attributes();

    foreach ($attributes as $attr => $attr_deets) {

        $attribute_label = wc_attribute_label($attr);

        if (isset($attributes[$attr]) || isset($attributes['pa_' . $attr])) {

            $attribute = isset($attributes[$attr]) ? $attributes[$attr] : $attributes['pa_' . $attr];

            if ($attribute['is_taxonomy']) {

                $formatted_attributes[$attribute_label] = implode(', ', wc_get_product_terms($product->id, $attribute['name'], array('fields' => 'names')));
            } else {

                $formatted_attributes[$attribute_label] = $attribute['value'];
            }
        }
    }
    $productcategory = $product->get_categories();
    $lang = getSiteCurrentLang();
    
    //Technical Specification
    //Product Features
    
    if ((strpos($productcategory, "Tailor-made product") === false) || (strpos($productcategory, "Kundanpassad Produkt") !== false)) {
        if (isset($formatted_attributes['Weight capacity']) || isset($formatted_attributes['Extension'])
            || isset($formatted_attributes['Colour']) || isset($formatted_attributes['Load metod']) || isset($formatted_attributes['Type of load'])
            || isset($formatted_attributes['Loading']) ||  isset($formatted_attributes['Load Way']) || isset($formatted_attributes['Picking Method'])
            || isset($formatted_attributes['Short or long side handled']) || isset($formatted_attributes['Number of shelves']) || isset($formatted_attributes['Shelf lock'])
            || isset($formatted_attributes['Mounted onto product'])) 
        {
            $title = __('Product Features', 'elementor');
                if ($lang === 'sv') {
                    $title = __('Produktegenskaper', 'elementor');
                }
                $tabs['productfeatures'] = [
                    'title' => $title,
                    'priority' => 21,
                    'callback' => 'woocommerce_product_productfeatures_tab',
                ];
            
        }
    }

    //Technical Specification
    if ((strpos($productcategory, "Tailor-made product") === false) || (strpos($productcategory, "Kundanpassad Produkt") !== false)) {
            if (isset($formatted_attributes['Loading Width']) || isset($formatted_attributes['Loading Depth']) || isset($formatted_attributes['Height'])
                || isset($formatted_attributes['Rack Depth']) || isset($formatted_attributes['Section width']) || isset($formatted_attributes['Section Height'])
                || isset($formatted_attributes['Width']) || isset($formatted_attributes['Length'])
                || isset($formatted_attributes['Depth']) || isset($formatted_attributes['Loading height']) || isset($formatted_attributes['Handle Height'])
            ) {

                $title = __('Technical Specification', 'elementor');
                if ($lang === 'sv') {
                    $title = __('Teknisk specifikation', 'elementor');
                }
                $tabs['technical'] = [
                    'title' => $title,
                    'priority' => 21,
                    'callback' => 'woocommerce_product_technical_tab',
                ];
            }
        }
        

         if(current_user_can('c_uam_cap_diagram')){
        $tabs['diagram'] = [
            'title' => __('', 'elementor'),
            'priority' => 21,
            'callback' => 'woocommerce_product_diagram_tab',
        ];
        } 

        //Weight and volume
        if ((isset($formatted_attributes['Product Weight'])) || (isset($formatted_attributes['Quantity on pallet'])) ||
            (isset($formatted_attributes['Pallet weight'])) || (isset($formatted_attributes['Number of pallets / Trailer'])) ||
            (isset($formatted_attributes['Quantity in the Package'])) || (isset($formatted_attributes['Pallet Size']))
        ) {
            $wetitle = __('Weight and Volume', 'elementor');
            if ($lang === 'sv') {
                $wetitle = __('Vikt och volym', 'elementor');
            }
            $tabs['weightvolume'] = [
                'title' => $wetitle,
                'priority' => 21,
                'callback' => 'woocommerce_product_weightvolume_tab',
            ];
        }

        //Weight Capacity support beam
        if (
            isset($formatted_attributes['WCSB 1 pallet']) || isset($formatted_attributes['WCSB 2 pallets'])
            || isset($formatted_attributes['WCSB 3 pallets'])
        ) {

            $wsuporttitle = __('Weight Capacity Support Beam', 'elementor');
            if ($lang === 'sv') {
                $wsuporttitle = __('Viktkapacitet stödbalk', 'elementor');
            }
            $tabs['weightcapacity'] = [
                'title' => $wsuporttitle,
                'priority' => 21,
                'callback' => 'woocommerce_product_weightcapacity_tab',
            ];
        }
    
 

    if (current_user_can("c_uam_cap_spare_parts")) {
    $tabs['spare-parts'] = [
        'title' => __('Spare Parts', 'elementor'),
        'priority' => 21,
        'callback' => 'woocommerce_product_spare_parts_tab',
    ];
    }

    // $tabs['variation'] = [
    //     'title' => __( 'Variations', 'elementor' ),
    //     'priority' => 21,
    //     'callback' => 'woocommerce_product_variation_tab',
    // ];



    // if(current_user_can( "c_uam_cap_accessories" )){
    //     $tabs['accessories'] = [
    //         'title' => __( 'Accessories', 'elementor' ),
    //         'priority' => 22,
    //         'callback' => 'woocommerce_product_accessories_tab',
    //     ];
    // }

    if (current_user_can("c_uam_cap_download_file")) {
        $tabs['download-files'] = [
            'title' => __('Download Files', 'elementor'),
            'priority' => 22,
            'callback' => 'woocommerce_product_download_files',
        ];
    }
    return $tabs;
});

function woocommerce_product_variation_tab()
{
    include_once(__DIR__ . '/template/tab-variation.php');
}
function woocommerce_product_diagram_tab()
{
    include_once(__DIR__ . '/template/tab-diagram.php');
}
function woocommerce_product_technical_tab()
{
    include_once(__DIR__ . '/template/tab-technical.php');
}
function woocommerce_product_productfeatures_tab()
{
    include_once(__DIR__ . '/template/tab-productfeatures.php');
}
function woocommerce_product_weightvolume_tab()
{
    include_once(__DIR__ . '/template/tab-weightvolume.php');
}
function woocommerce_product_weightcapacity_tab()
{
    include_once(__DIR__ . '/template/tab-weightcapacity.php');
}
function woocommerce_product_accessories_tab()
{
    include_once(__DIR__ . '/template/tab-accessories.php');
}
function woocommerce_product_spare_parts_tab()
{
    include_once(__DIR__ . '/template/tab-spare-parts.php');
}
function woocommerce_product_download_files()
{
    include_once(__DIR__ . '/template/tab-download-files.php');
}

/**
 * export functions
 */
include(THINGSATWEB_DIR . "/includes/TawExport.php");
new TawExport();

/**
 * import functions
 */
include(THINGSATWEB_DIR . "/includes/TawImport.php");
new TawImport();

add_action('wp_ajax_test', 'test');

function getLanguageProductId($id, $lang)
{
    return apply_filters('wpml_object_id', $id, 'product', FALSE, $lang);
}

function getTridId($element_id, $element_type)
{
    return apply_filters('wpml_element_trid', NULL, $element_id, $element_type);
}

function getLanguageProductTridId($product_id)
{
    return getTridId($product_id, 'post_product');
}

function getLanguageCateTridId($term_id)
{
    return getTridId($term_id, 'tax_product_cat');
}

function addNewLangDataToWPML($product_id, $sku, $cur_lang, $trid)
{
    update_post_meta($product_id, "_sku", $sku);

    $set_language_args = array(
        'element_id'    => $product_id,
        'element_type'  => 'post_product',
        'trid'   => $trid,
        'language_code'   => $cur_lang,
        'source_language_code' => 'en'
    );

    do_action('wpml_set_element_language_details', $set_language_args);
}

function removeDuplicateAttrs()
{
    $lang = $_GET['lang'] ?? "en";
    global $wpdb;
    $q = "SELECT SQL_CALC_FOUND_ROWS  tsm_posts.ID FROM tsm_posts JOIN tsm_icl_translations as wpml_translations ON tsm_posts.ID = wpml_translations.element_id AND wpml_translations.element_type = CONCAT('post_', tsm_posts.post_type) WHERE 1=1  AND tsm_posts.post_type = 'product' AND ((tsm_posts.post_status = 'publish')) AND ( ( ( wpml_translations.language_code = '$lang' OR 0 ) AND tsm_posts.post_type  IN ('post','page','attachment','wp_block','wp_template','wp_template_part','wp_navigation','e-landing-page','elementor_library','product','product_variation' )  ) OR tsm_posts.post_type  NOT  IN ('post','page','attachment','wp_block','wp_template','wp_template_part','wp_navigation','e-landing-page','elementor_library','product','product_variation' )  ) limit 0,10";

    $product_ids = $wpdb->get_results($q);
    $attr_ar = [];
    foreach ($product_ids as $pid) {
        $pid = $pid->ID;
        if (empty($pid)) continue;
        $product = wc_get_product($pid);
        $attr = $product->get_attributes();

        if (empty($product)) {
            continue;
        }
        foreach ($attr as $key => $v) {

            $opt = $v->get_options();
            foreach ($opt as $o) {
                $attr_ar[$pid][$key][get_term($o)->name][] = $o;
            }
        }
        // exit;
        //echo $pid."<br/>";
    }
    print_r($attr_ar);

    exit;
}
add_action('wp_ajax_removeDuplicateAttrs', 'removeDuplicateAttrs');

function test()
{

    global $wpdb;
    $obj = $wpdb->get_row("SELECT attach_id,gallery_pics FROM `taw_article_picture` WHERE art_no='11 201-0' and lang='sv'");

    update_post_meta(11588, '_product_image_gallery', $obj->gallery_pics);

    print_r($obj->attach_id);
    echo "<br/>";
    print_r($obj->gallery_pics);
}

function print_order($product_id)
{
   // $lang = $_GET['lang'] ?? "en";
   $lang = $_GET['lang'] ?? apply_filters('wpml_current_language', NULL);
function formatAttribute($attribute) {
                                // Check if the value matches the pattern of <number>-<unit> with no space between number and unit (e.g., 199mm-250mm)
                                if (preg_match('/^\d+-(\w+)$/', $attribute, $matches)) {
                                    return str_replace('-', ' ', $attribute); // Replace hyphen with space (e.g., 199-mm -> 199 mm)
                                }

                                // Check if the value is a range like '199 mm-250mm'
                                if (preg_match('/^\d+\s\w+-\d+\s\w+$/', $attribute)) {
                                    return $attribute; // No change if it's a range with space
                                }

                                // Check if the value is a range without spaces like '199mm-250mm'
                                if (preg_match('/^\d+\w+-\d+\w+$/', $attribute)) {
                                    return $attribute; // No change if both values are number+unit without space
                                }

                                // If none of the above, just return the original value
                                return $attribute;
                            }
$suffix = ($lang === 'sv') ? 'Produktdatablad' : 'Product data sheet';
//    $user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;
//     if ($user_id > 0) {

//         wp_set_current_user($user_id);
//        // wp_set_auth_cookie($user_id);
//     }
$current_user_id = get_current_user_id();
   $user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;
    if ($user_id > 0) {
        wp_set_current_user($user_id);
       // wp_set_auth_cookie($user_id);
    }

    if ($user_id > 0 && $user_id != $current_user_id) {
        echo "<h1 style='color:red;'>unauthorized access</h1>";
       // echo "<script>window.location.href = '" . site_url('/my-account') . "';</script>";
        die();
    }
    
    global $wpdb;
    $product = wc_get_product($product_id);
    // Retrieve the SKU
    $sku = $product->get_sku();
    if (empty($sku)) {
        $sku = 'document'; // Fallback if SKU is not set
    }

    // Final filename
$filename = $sku . ' ' . $suffix;

    include_once(__DIR__ . '/vendor/autoload.php');

    $dompdf = new \Dompdf\Dompdf();

    ob_start(); // Start output buffering

    include(__DIR__ . '/template/page-print.php'); // Include the template

    $html = ob_get_clean(); // Store the buffered output in $html

    $dompdf->loadHtml($html);

    $dompdf->setPaper('A4', 'portrait');

    $dompdf->render();

    // Get the PDF content
    $pdf_content = $dompdf->output();

    // Set the content type header to display PDF in the browser
    header('Content-Type: application/pdf');
    header('Content-Disposition: inline; filename="' . $filename . '.pdf"'); // Prompt the user to download
    // Output the PDF content
    echo $pdf_content;

    exit();
}

add_action('wp_ajax_print', 'print_order');

function step_file($product_id)
{
    global $wpdb;
    $product = wc_get_product($product_id);
    $meta = get_post_meta($product->get_id(), 'taw_prod_opt');
    $meta = isset($meta[0]) ? $meta[0] : array();
    $diagram = $meta['article_price']['product_step_file']['url'];

    // Fetch the image content
    $image_data = file_get_contents($diagram);

    $filename = $product->get_sku() . '.stp';

    // Set the appropriate headers
    header('Content-Type: application/STEP');
    header('Content-disposition: attachment; filename="' . $filename . '"');
    header('Content-Length: ' . strlen($image_data));

    // Output the image data
    echo $image_data;
    exit;
}

add_action('wp_ajax_step', 'step_file');

add_action( 'wp_ajax_ajaxcart', 'quadlayers_add_to_cart_function' );

add_action('wp_ajax_nopriv_ajaxcart', 'quadlayers_add_to_cart_function');



function quadlayers_clear_notices() {
    if ( function_exists( 'wc_clear_notices' ) ) {
        wc_clear_notices();
    } elseif ( function_exists( 'wc_add_notice' ) ) {
        WC()->session->get( 'wc_notices', array() );
    }
}

function quadlayers_add_to_cart_function() {
    $data = array_merge( (array) $_GET, (array) $_POST );
    
    $product_id = isset( $data['product_id'] ) ? $data['product_id'] : "";
    $product_qty = isset( $data['product_qty'] ) ? $data['product_qty'] : "";
    

    

    // Add product to cart
        if ( WC()->cart->add_to_cart( $product_id, $product_qty ) ) {
        $response['status'] = '1'; // Successfully added to cart
quadlayers_clear_notices(); // Clear notices
    } else {
        $response['status'] = '0'; // Cart is not empty
quadlayers_clear_notices(); // Clear notices
    }

    wp_send_json( $response ); // Send the JSON-encoded response
}

function wpcf7_before_send_mail_function($contact_form, $abort, $submission) {
    global $wpdb;
    $lang = getSiteCurrentLang();
    $first_name = $submission->get_posted_data('first-name');
    $last_name = $submission->get_posted_data('last-name');
    $email = $submission->get_posted_data('Email');
    $phone = $submission->get_posted_data('your-phone');
    $business = $submission->get_posted_data('text-909');
    $user_types = $submission->get_posted_data('Selection');

    $post_id = $submission->get_meta('container_post_id');
    $form_id = $contact_form->id();

    // Check if $user_types is an array and not empty
    if (is_array($user_types) && !empty($user_types)) {
        $user_type_str = implode(', ', $user_types);

        if ($lang == 'en') {
            if ($user_types[0] == "B2B CUSTOMER") {
                $user_type = "B2B CUSTOMER";
            } else if ($user_types[0] == "RESELLER") {
                $user_type = "RESELLER_EUR";
            }
        } elseif ($lang == 'sv') {
            if ($user_types[0] == "B2B CUSTOMER") {
                $user_type = "B2B CUSTOMER";
            } else if ($user_types[0] == "RESELLER") {
                $user_type = "RESELLER_SEK";
            }
        }

        // Update $user_type_str after modifying $user_types
        $user_type_str = implode(', ', $user_types);

        if ($form_id == '833') {
            $current_time = current_time('mysql');
            $full_name = $first_name . ' ' . $last_name;
            $becomeacustomerform = "INSERT INTO `custom_uam_user_requests` (`time`,`name`,`business`,`email`,`phone`,`role`) 
            VALUES ('$current_time','$full_name','$business','$email','$phone','$user_type');";

            $result = $wpdb->query($becomeacustomerform);
        }
    }

    return $contact_form;
}

add_filter('wpcf7_before_send_mail', 'wpcf7_before_send_mail_function', 10, 3);

add_action('wp_ajax_get_user_request_details', 'get_user_request_details_callback');

function get_user_request_details_callback() {
    global $wpdb;
    $lang = getSiteCurrentLang();
    $id = $_POST['id'];

    $query = "SELECT * FROM custom_uam_user_requests WHERE id = %d";
    $res = $wpdb->get_row($wpdb->prepare($query, $id), OBJECT);
   
    $name=explode(" ",$res->name);
    $first_name=$name[0];
    $last_name=count($name)>1?$name[1]:"";

    if($res->role=="B2B CUSTOMER"){
        $res->role="custom_uam_b2b";
    }else if($res->role=="RESELLER_EUR"){
        $res->role="custom_uam_reseller_eur";
    }else if($res->role=="RESELLER_SEK"){
        $res->role="custom_uam_reseller_sek";
    }else{
        $res->role="custom_uam_Normal User";
    }
               
  $data=array(
    'user_login' => $res->email,
    'user_email' => $res->email,
    'first_name' => $first_name,
    'last_name' => $last_name,
    'display_name' => $res->name,
    'role' => $res->role
  );
  
  $user_id = wp_insert_user($data);
  $datause=array(
    'user_login' => $res->email,
    'user_email' => $res->email,
    'first_name' => $first_name,
    'last_name' => $last_name,
    'display_name' => $res->name,
    'role' => $res->role
  );
    exit();
}

add_action('save_post_product', 'spareparts', 10, 3);

function spareparts($post_id, $post) {
    // Check if this is an autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check user's permissions
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Check if the post type is 'product'
    if ($post->post_type !== 'product') {
        return;
    }
$existing_pointer = get_post_meta($post_id, '_wpii_prod_img_pointer_info', true);
    $existing_produtsetting = get_post_meta($post_id, 'taw_prod_opt', true);
    // Get the meta information from the original post
    $img_pointer_info = isset($_POST['_wpii_prod_img_pointer_info']) ? $_POST['_wpii_prod_img_pointer_info'] : $existing_pointer;

    $taw_prod_opt = isset($_POST['taw_prod_opt']) ? $_POST['taw_prod_opt'] : $existing_produtsetting;

    // Get the Swedish post ID
    $swedish_post_id = apply_filters('wpml_object_id', $post_id, 'post', true, 'sv');

    // Check if there is a corresponding Swedish version
    if ($swedish_post_id && $swedish_post_id !== $post_id) {
        // Save the same meta information for the Swedish version
        update_post_meta($swedish_post_id, '_wpii_prod_img_pointer_info', $img_pointer_info);
        update_post_meta($swedish_post_id, 'taw_prod_opt', $taw_prod_opt);
    }
}


// Add a new column to My Account > Orders
function custom_wc_add_user_id_column( $columns ) {
    // Check if the current user has the capability 'c_uam_cap_group_info'
    if ( current_user_can( 'c_uam_cap_group_info' ) ) {
        $new_columns = array(
            'order-userid' => __( 'User Name', 'woocommerce' ),
        );

        // Insert the new column after 'order-number'
        $columns = array_slice( $columns, 0, 1, true ) +
                   $new_columns +
                   array_slice( $columns, 1, null, true );
    }

    return $columns;
}
add_filter( 'woocommerce_account_orders_columns', 'custom_wc_add_user_id_column' );


function custom_uam_save_restrict_artno() {
    $response = array('error' => false);
    global $wpdb;
    $returnAletrs = [];

    // Decode the JSON array of art numbers
    $all_artnos = json_decode(stripslashes($_POST['restrict_artno']), true);
    $role_id = isset($_POST['role_id']) ? sanitize_text_field($_POST['role_id']) : '';
    $type = isset($_POST['type']) ? sanitize_text_field($_POST['type']) : '';

    if (empty($all_artnos)) {
        $response['error'] = true;
        $response['message'] = 'Art Nos are required';
        wp_send_json_error($response);
    }

    $new_items = array();

    foreach ($all_artnos as $restrict_artno) {
        $restrict_artno = sanitize_text_field($restrict_artno);
        checkWithRestrictProduct($restrict_artno, $type, $role_id, $returnAletrs);
        
        $query = $wpdb->prepare(
            "INSERT INTO `taw_restrict_product` (`art_no`, `Type`, `roleid`) VALUES (%s, %s, %s);",
            $restrict_artno, $type, $role_id
        );
        $result = $wpdb->query($query);
        if ($result === false) {
            $response['error'] = true;
            $response['message'] = 'Failed to save Art No: ' . $restrict_artno;
            wp_send_json_error($response);
        } else {
            // Fetch additional data if needed (product_title, edit_url) and add to $new_items
            $new_item = [
                'art_no' => $restrict_artno,
                'product_title' => '', // Add logic to fetch product title
                'edit_url' => '', // Add logic to fetch edit URL
                'AlertArray' => $returnAletrs,
                'Stack-Priceing-alert'=>$alertMsg
            ];
            $new_items[] = $new_item;
        }
    }

    // Prepare the response with all new items
    $response['new_items'] = $new_items;
    wp_send_json_success($response);
}
add_action('wp_ajax_custom_uam_save_restrict_artno', 'custom_uam_save_restrict_artno');
function checkWithRestrictProduct($atrNO, $type, $roleID, &$returnAletrs) {
    global $wpdb;
    $FromRestrictTable = [];
    $usersFromrestrict=[];
    $flag=0;

    if ($type == 'role') {
        // Prepare and execute queries
        $query = $wpdb->prepare("SELECT role, id FROM tsm_product_stack_pricing WHERE art_no = %s", $atrNO);
        $stackpricing = $wpdb->get_results($query);

        $query1 = $wpdb->prepare("SELECT roleid FROM taw_restrict_product WHERE art_no = %s and Type = 'role'", $atrNO);
        $restrictProduct = $wpdb->get_results($query1);

        foreach ($stackpricing as $pricing) {
            array_push($FromRestrictTable, $pricing);
        }

        foreach ($stackpricing as $pricing) {
            if ($pricing->role != $roleID) {
                $table = 'tsm_product_stack_pricing';

                $query5 = $wpdb->prepare(
                    "SELECT role, users FROM $table WHERE id = %d", 
                    $pricing->id
                );
                $result = $wpdb->get_row($query5);
                $Stack_Pricing_users = explode(',', $result->users);
                $roleee=$result->role;

                foreach ($Stack_Pricing_users as $user_id) {
                    $user_id = trim($user_id);
                    $query6 = $wpdb->prepare("SELECT * FROM taw_restrict_product WHERE Type = 'user' AND roleid = %d", $user_id);
                    $result = $wpdb->get_var($query6);
    
                    if (!$result) {
                        $query = $wpdb->prepare("SELECT status FROM $table WHERE id = %d", $pricing->id);
                        $current_status = $wpdb->get_var($query);

                        if($current_status=='1'){
                            $query7 = $wpdb->prepare("SELECT * FROM taw_restrict_product WHERE Type = 'role' AND roleid = %s", $roleee);
                            $result2 = $wpdb->get_var($query7);
                        
                            if(!$result2){
                                $wpdb->update($table, ['status' => $status], ['id' => $pricing->id], ['%d'], ['%d']);
                                $returnAletrs[] = "There is an offer for this {$atrNO} product in Stack pricing for {$roleID} and it has been deactivated";
                                $flag=1;
                            }
                        }
                    }
                    else {
                    }
                }


             /*  $status = 0;
                $id = (int)$pricing->id;

                if (count($FromRestrictTable) > 0) {
                    if (!in_array($roleID, $FromRestrictTable)) {
                        $query = $wpdb->prepare("SELECT status FROM $table WHERE id = %d", $id);
                        $current_status = $wpdb->get_var($query);

                        if($current_status=='1'){
                            $wpdb->update($table, ['status' => $status], ['id' => $id], ['%d'], ['%d']);
                            $returnAletrs[] = "There is an offer for this {$atrNO} product in Stack pricing for {$roleID} and it has been deactivated";
                            $flag=1;
                        }
                    }
                } else {
                    $query = $wpdb->prepare("SELECT status FROM $table WHERE id = %d", $id);

                    $current_status = $wpdb->get_var($query);
                    if($current_status=='1'){
                        $wpdb->update($table, ['status' => $status], ['id' => $id], ['%d'], ['%d']);
                        $returnAletrs[] = "There is an offer for this {$atrNO} product in Stack pricing for {$roleID}. It has been deactivated.";
                        $flag=1;
                    }
                }*/
            }
        }
    } else {
        $roleID = (int)$roleID;
        $query = $wpdb->prepare("SELECT id, role,users FROM tsm_product_stack_pricing WHERE art_no = %s", $atrNO);
        $results = $wpdb->get_results($query);

        foreach ($results as $result) {
            $stringArray = explode(',', $result->users);
            $intArray = array_map('intval', $stringArray);
            $id = (int)$result->id;

            if (count($intArray) > 0) {
                $table = 'tsm_product_stack_pricing';
                $status = 0;

                if (in_array($roleID, $intArray) && count($intArray) > 1) {
                    $erer = gettype((string)$roleID);
                    $wpdb->update(
                        $table, 
                        ['users' => $roleID, 'enable_all_users' => '0'], // Columns and their new values
                        ['id' => $id], // WHERE conditions
                        ['%s', '%s'], // Format specifiers for the values being updated
                        ['%d']        // Format specifier for the WHERE condition
                    );
                    
                    $flag=2;
                    $returnAletrs[] = "In Stack pricing, there is an offer for {$atrNO} product other than this {$type} user in the same role, so those users are removed";
                } else {
                    $roleis = $result->role;  

                    $query6 = $wpdb->prepare(
                        "SELECT * FROM taw_restrict_product WHERE Type = 'role' AND roleid = %d AND art_no = %s",
                        $roleis, $atrNO
                    );
                    $result = $wpdb->get_var($query6);

                    if (!$result) {
                          $wpdb->update($table, ['status' => 0], ['id' => $id], ['%d'], ['%d']);
                    $flag=2;
                    $returnAletrs[] = "In Stack pricing, there is an offer for {$atrNO} product other than this {$type} user in a different role, so this offer is deactivated.";
                    }

                }
            }
        }
    }

    // Debugging statement
    error_log(print_r($returnAletrs, true));
}

function checkStackPrice_Before_delete(){
    $flag=[];
    global $wpdb;
    $artNo = sanitize_text_field($_POST['artNo']);

    $roleId = sanitize_text_field($_POST['roleId']);

    if (strval($roleId) != 'custom_uam_reseller_sek' && strval($roleId) != 'custom_uam_reseller_eur' && strval($roleId) != 'custom_uam_b2b') {
                $query = $wpdb->prepare(
                "SELECT id,users FROM tsm_product_stack_pricing WHERE FIND_IN_SET(%s, users) > 0 AND art_no = %s",
                $roleId,
                $artNo
            );
       $results = $wpdb->get_results($query);      
          
       if (!empty($results)) {
   
        $users=[];
        $id=(int)$results->id;
      
        foreach ($results as $result) {
            $row = explode(',', $result->users); 
           
            $id=$result->id;

            if(count($row)>=2){
                foreach($row as $user ){
                    if($user!=$roleId){
                        array_push($users,$user);
                    }
                }
        
                $users_string = implode(',', $users);
                if(count($users)>1){
                    $update_query = $wpdb->prepare( 
                        "UPDATE tsm_product_stack_pricing SET status = %s, enable_all_users = %d WHERE id = %d",
                        $users_string, // Pass the comma-separated string
                        0,            // The value to set for 'enable_all_users'
                        $id           // The id of the row to update
                    );
                    $wpdb->query($update_query);
                    array_push($flag,'There is an offer for this product for this user as this product is restrect ted for some other user offer is deactivated ');

                }else{
                $update_query = $wpdb->prepare( 
                    "UPDATE tsm_product_stack_pricing SET users = %s, enable_all_users = %d WHERE id = %d",
                    $users_string, // Pass the comma-separated string
                    0,            // The value to set for 'enable_all_users'
                    $id           // The id of the row to update
                );
                
                $wpdb->query($update_query);
                array_push($flag,'This user is removed from one of the stack pricing rule for this product as this product is still restricted for aother users');
            }
                $users=[];
               }
        }
        }
     }else{
        $query_restrict_product = $wpdb->prepare(
            "SELECT * FROM taw_restrict_product WHERE type = 'role' AND roleid != %s",
            $roleId
        );
        
        // Fetch the results
        $results_restrict_product = $wpdb->get_results($query_restrict_product);
        if (!empty($results_restrict_product)) {
            $query = $wpdb->prepare(
                "SELECT id FROM tsm_product_stack_pricing WHERE art_no = %s AND role = %s AND status = %s",
                $artNo,
                $roleId,
                '1' // Adding the condition for status
            );
            
            $results = $wpdb->get_results($query);    
            if (!empty($results)) {
                foreach ($results as $row) {
                    $id = $row->id; // Access the id property
                    $update_result = $wpdb->update(
                        'tsm_product_stack_pricing',
                        array('status' => '0'), // New values
                        array('id' => $id), // Where condition
                        array('%s'), // Format for the new value
                        array('%d')  // Format for the where condition
                    );
                    array_push($flag, 'This product is still restricted for some other role so the offer for this role in stack pricing is inactivated');
                }
            }
     }
    }
     wp_send_json_success($flag);
}
add_action('wp_ajax_checkStackPrice_Before_delete', 'checkStackPrice_Before_delete');


function custom_uam_delete_artno() {
    $response = array('error' => false);
    global $wpdb;

    $artNo = sanitize_text_field($_POST['artNo']);
    $roleId = sanitize_text_field($_POST['roleId']);

    $query = $wpdb->prepare(
        "DELETE FROM taw_restrict_product WHERE roleid = '$roleId' AND art_no = '$artNo'",
    );

    // Execute the query
    $result = $wpdb->query($query);

    if ($result === false) {
        $response['error'] = true;
        $response['message'] = 'Failed to delete Art No: ' . $artNo;
        wp_send_json_error($response);
    }
    wp_send_json_success($response);
}
add_action('wp_ajax_custom_uam_delete_artno', 'custom_uam_delete_artno');

function checkStackPrice_Before_delete_multiartno($artNo,$roleId){
   
    $flag=[];
    global $wpdb;
    $artNo = $artNo;
    $roleId = $roleId;
 
    if (strval($roleId) != 'custom_uam_reseller_sek' && strval($roleId) != 'custom_uam_reseller_eur' && strval($roleId) != 'custom_uam_b2b') {
                $query = $wpdb->prepare(
                "SELECT id,users FROM tsm_product_stack_pricing WHERE FIND_IN_SET(%s, users) > 0 AND art_no = %s",
                $roleId,
                $artNo
            );

            $query1 = $wpdb->prepare(
                "SELECT * FROM taw_restrict_product WHERE  art_no = %s",
                $roleId,
                $artNo
            );
            $FromRestrictProduct=$wpdb->get_results($query1);
            $results = $wpdb->get_results($query);
            if((!empty($FromRestrictProduct))){

            }
         
            if (!empty($results)) {
   
                $users=[];
                $id=(int)$results->id;
               
                foreach ($results as $result) {
                    $row = explode(',', $result->users); 
                    $id=$result->id;
        
                    if(count($row)>=2){
                        foreach($row as $user ){
                            if($user!=$roleId){
                                array_push($users,$user);
                            }
                        }
                
                        $users_string = implode(',', $users);
                        $update_query = $wpdb->prepare( 
                            "UPDATE tsm_product_stack_pricing SET users = %s WHERE id = %d",
                            $users_string, // Pass the comma-separated string
                            $id
                        );
                        $wpdb->query($update_query);
                        array_push($flag,'This user is removed from one of the stack pricing rule for this product as this product is still restricted for aother users');
                        $users=[];
                       }
                }
                }  
}
else{
    $query_restrict_product = $wpdb->prepare(
        "SELECT * FROM taw_restrict_product WHERE type = 'role' AND roleid != %s",
        $roleId
    );

    $results_restrict_product = $wpdb->get_results($query_restrict_product);

    if (!empty($results_restrict_product)) {
        $query = $wpdb->prepare(
            "SELECT id FROM tsm_product_stack_pricing WHERE art_no = %s AND role = %s AND status = %s",
            $artNo,
            $roleId,
            '1' // Adding the condition for status
        );
        
        $results = $wpdb->get_results($query);
        
        if (!empty($results)) {
            foreach ($results as $row) {
                $id = $row->id; // Access the id property
                $update_result = $wpdb->update(
                    'tsm_product_stack_pricing',
                    array('status' => '0'), // New values
                    array('id' => $id), // Where condition
                    array('%s'), // Format for the new value
                    array('%d')  // Format for the where condition
                );
                array_push($flag, 'This product is still restricted for some other role so the offer for this role in stack pricing is inactivated');
            }
        }
        
 }
}
  return $flag;
}
function custom_uam_delete_multipleartno() {
    // Initialize response array
    $stackPricing=[];
    $response = array('success' => false);
    
    // Check if the request is valid
    if (isset($_POST['multipleartNosToDelete'], $_POST['roleId'])) {
        global $wpdb;
        
        // Sanitize input
        $multipleartNosToDelete = explode(',', sanitize_text_field($_POST['multipleartNosToDelete']));
        $roleId = sanitize_text_field($_POST['roleId']);
        
        // Loop through artNos array and delete each entry for the specific role ID
        foreach ($multipleartNosToDelete as $multipleartNo) {
            $checked=checkStackPrice_Before_delete_multiartno($multipleartNo,$roleId);
       
            if(!empty($checked)){
                array_push($stackPricing,$checked);
            }
            $query = $wpdb->prepare(
                "DELETE FROM taw_restrict_product WHERE roleid = '$roleId' AND art_no = '$multipleartNo'"   
            );
            // Execute the query
            $result = $wpdb->query($query);
        
            if ($result === false) {
                $response['error'] = true;
                $response['message'] = 'Failed to delete Art No: ' . $multipleartNo;
                wp_send_json_error($response);
            }
        }

        // If deletion is successful
        $response['success'] = true;
        wp_send_json_success($stackPricing);
    } else {
        // Invalid request handling
        $response['message'] = 'Invalid request parameters.';
        wp_send_json_error($response);
    }
}
add_action('wp_ajax_custom_uam_delete_multipleartno', 'custom_uam_delete_multipleartno');

function custom_uam_get_role_data() {
    global $wpdb;
    $role_id = esc_html(trim($_POST['id']));

    $results = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT id, art_no, updated_at, Type, roleid FROM taw_restrict_product WHERE roleid = %s",
            $role_id
        ),
        ARRAY_A
    );

    if ($results) {
        wp_send_json_success($results);
    } else {
        wp_send_json_error('No data found');
    }
}
add_action('wp_ajax_custom_uam_get_role_data', 'custom_uam_get_role_data');
add_action('wp_ajax_nopriv_custom_uam_get_role_data', 'custom_uam_get_role_data');


// Add AJAX action for retrieving product title by SKU
add_action('wp_ajax_get_product_title_by_sku', 'get_product_title_by_sku');
add_action('wp_ajax_nopriv_get_product_title_by_sku', 'get_product_title_by_sku');

function get_product_title_by_sku() {
    // Verify nonce for security
   // check_ajax_referer('get_product_title_by_sku_nonce', 'nonce');

    // Get the SKU from the AJAX request
    $sku = isset($_POST['sku']) ? sanitize_text_field($_POST['sku']) : '';

    if (!empty($sku)) {
        // Get the product ID based on the SKU
        $product_id = wc_get_product_id_by_sku($sku);

        if ($product_id) {
            // Get the product title
            $product_title = get_the_title($product_id);

            // Send the product title as a response
            wp_send_json_success(array('title' => $product_title));
        } else {
            wp_send_json_error('Product not found for the provided SKU.');
        }
    } else {
        wp_send_json_error('Invalid SKU.');
    }

    // Always exit to prevent further execution
    wp_die();
}

function custom_uam_get_product_title_by_sku() {
    // Check if artNo is provided in POST data
    
    if (isset($_POST['artNo'])) {
        $artNo = sanitize_text_field($_POST['artNo']);

        // Your database query to fetch product title
        global $wpdb;
        $sql = $wpdb->prepare("
            SELECT p.post_title
            FROM tsm_posts AS p
            INNER JOIN tsm_postmeta AS pm ON p.ID = pm.post_id
            WHERE pm.meta_key = '_sku' AND pm.meta_value = %s
        ", $artNo);

        $product_title = $wpdb->get_var($sql);

        if ($product_title) {
            // Return product title as JSON response
            wp_send_json_success(array('title' => $product_title));
        } else {
            // Return error message if product title is not found
            wp_send_json_error('Product title not found for the provided Art No');
        }
    } else {
        // Return error message if artNo is not provided
        wp_send_json_error('Art No parameter is missing');
    }
}
add_action('wp_ajax_custom_uam_get_product_title_by_sku', 'custom_uam_get_product_title_by_sku');
add_action('wp_ajax_nopriv_custom_uam_get_product_title_by_sku', 'custom_uam_get_product_title_by_sku');



function custom_uam_checksame_artno_exists() {
    global $wpdb;

    $art_no = isset($_POST['artNo']) ? sanitize_text_field($_POST['artNo']) : '';
    $role_id = isset($_POST['roleId']) ? sanitize_text_field($_POST['roleId']) : '';
    $type = isset($_POST['type']) ? sanitize_text_field($_POST['type']) : '';

    if (empty($art_no) || empty($role_id)) {
        wp_send_json_error(['message' => 'Invalid parameters.']);
        return;
    }
    
    $check_artno = $wpdb->prepare("SELECT COUNT(*) FROM taw_restrict_product
     WHERE art_no = %s AND roleid = %s AND Type = %s", $art_no, $role_id,$type);

    $check_artnovalue = $wpdb->get_var($check_artno);

    if ($check_artnovalue > 0) {
        wp_send_json_success(['exists' => true]);
    } else {
        wp_send_json_success(['exists' => false]);
    }
}
add_action('wp_ajax_custom_uam_checksame_artno_exists', 'custom_uam_checksame_artno_exists');

function custom_uam_check_artno_exists() {
    global $wpdb;

    $art_no = isset($_POST['artNo']) ? sanitize_text_field($_POST['artNo']) : '';
    $role_id = isset($_POST['roleId']) ? sanitize_text_field($_POST['roleId']) : '';
    $type = isset($_POST['type']) ? sanitize_text_field($_POST['type']) : '';

    if (empty($art_no) || empty($role_id)) {
        wp_send_json_error(['message' => 'Invalid parameters.']);
        return;
    }

    // Check if the role_id is actually a user ID
    if (is_numeric($role_id)) {
        $user = get_userdata($role_id);
        if ($user) {
            $user_roles = $user->roles;
            if (!empty($user_roles)) {
                $role_id = $user_roles[0]; // Assuming the user has only one role
            } else {
                wp_send_json_error(['message' => 'No role found for this user.']);
                return;
            }
        } else {
            wp_send_json_error(['message' => 'User not found.']);
            return;
        }
    }

    // Check if the Art No already exists with the provided Role ID and Type
    $check_artno = $wpdb->prepare(
        "SELECT COUNT(*) FROM taw_restrict_product WHERE art_no = %s AND roleid = %s ",
        $art_no, $role_id
    );
    $exists = $wpdb->get_var($check_artno);

    // Prepare the response
    $response = array(
        'exists' => $exists > 0,
        'type_check' => $exists > 0 // Optional: You can include more details here
    );

    wp_send_json_success($response);
}

add_action('wp_ajax_custom_uam_check_artno_exists', 'custom_uam_check_artno_exists');

function custom_uam_get_user_role() {
    $user_id = isset($_POST['userId']) ? sanitize_text_field($_POST['userId']) : '';

    if (empty($user_id)) {
        wp_send_json_error(['message' => 'Invalid parameters.']);
        return;
    }

    $user = get_userdata($user_id);
    if ($user) {
        $user_roles = $user->roles;
        if (!empty($user_roles)) {
            $role = $user_roles[0]; // Assuming the user has only one role
            wp_send_json_success(['roleId' => $role]);
        } else {
            wp_send_json_error(['message' => 'No role found for this user.']);
        }
    } else {
        wp_send_json_error(['message' => 'User not found.']);
    }
}

add_action('wp_ajax_custom_uam_get_user_role', 'custom_uam_get_user_role');
add_action('wp_ajax_nopriv_custom_uam_get_user_role', 'custom_uam_get_user_role');


function custom_uam_search_skus() {
    // Ensure this is a POST request
    if ( ! isset( $_POST['search_term'] ) ) {
        wp_send_json_error( 'Missing search term' );
    }

    // Sanitize input
    $search_term = sanitize_text_field( $_POST['search_term'] );

    // Query to fetch SKUs based on $search_term
    // Example query, replace with your actual query
    $skus = array(); // Example array of SKUs

    // Example response structure
    $response = array(
        'success' => true,
        'data' => $skus
    );

    // Send JSON response
    wp_send_json( $response );
}

// Hook for AJAX action
add_action( 'wp_ajax_custom_uam_search_skus', 'custom_uam_search_skus' );
add_action( 'wp_ajax_nopriv_custom_uam_search_skus', 'custom_uam_search_skus' );

function enqueue_jquery_ui_autocomplete() {
    wp_enqueue_script('jquery-ui-autocomplete');
}
add_action('wp_enqueue_scripts', 'enqueue_jquery_ui_autocomplete');

function restrict_get_product_matches_skus() {
    global $wpdb;

    if (isset($_POST['search_query'])) {
        $search_query = sanitize_text_field($_POST['search_query']);
        // Use searchArticle function to get results
        $results = searchArticlerestrict($search_query);

        // Return results as JSON
        wp_send_json(array_values($results));
    }

    wp_die();
}

function searchArticlerestrict($term) {
    global $wpdb;
    
    $q = $wpdb->prepare("SELECT meta_value FROM `tsm_postmeta` WHERE meta_key='_sku' AND meta_value LIKE %s", '%' . $wpdb->esc_like($term) . '%');
    $r = $wpdb->get_results($q);
    
    $option = [];
    foreach ($r as $v) {
        $option[$v->meta_value] = $v->meta_value;
    }

    return $option;
}

add_action('wp_ajax_restrict_get_product_matches_skus', 'restrict_get_product_matches_skus'); // For logged-in users
add_action('wp_ajax_nopriv_restrict_get_product_matches_skus', 'restrict_get_product_matches_skus'); // For logged-out users






//cat
function restrictcategory_get_product_matches_skus() {
    global $wpdb;

    if (isset($_POST['search_query'])) {
        $search_query = sanitize_text_field($_POST['search_query']);
        // Use searchArticle function to get results
        $results = searchArticlerestrictcategory($search_query);

        // Return results as JSON
        wp_send_json(array_values($results));
    }

    wp_die();
}

function searchArticlerestrictcategory($term) {
    global $wpdb;
    
    // Corrected the query to fetch category names
    $q = $wpdb->prepare(
        "SELECT tsm_terms.name FROM tsm_terms JOIN tsm_term_taxonomy ON tsm_terms.term_id = tsm_term_taxonomy.term_id 
        join tsm_icl_translations on tsm_icl_translations.element_id=tsm_terms.term_id
        WHERE tsm_term_taxonomy.taxonomy = 'product_cat' and tsm_icl_translations.element_type='tax_product_cat' 
        and tsm_icl_translations.language_code='en' AND tsm_terms.name LIKE %s", 
        '%' . $wpdb->esc_like($term) . '%'
    );

    $r = $wpdb->get_results($q);
    
    // Prepare the results for output
    $option = [];
    foreach ($r as $v) {
        // Storing the category names
        $option[] = $v->name;
    }

    return $option;
}


add_action('wp_ajax_restrictcategory_get_product_matches_skus', 'restrictcategory_get_product_matches_skus'); // For logged-in users
add_action('wp_ajax_nopriv_restrictcategory_get_product_matches_skus', 'restrictcategory_get_product_matches_skus'); // For logged-out users


function custom_uam_checksame_category_exists() {
    global $wpdb;

    $art_no = isset($_POST['artNo']) ? sanitize_text_field($_POST['artNo']) : '';
    $role_id = isset($_POST['roleId']) ? sanitize_text_field($_POST['roleId']) : '';
    $type = isset($_POST['type']) ? sanitize_text_field($_POST['type']) : '';

    if (empty($art_no) || empty($role_id)) {
        wp_send_json_error(['message' => 'Invalid parameters.']);
        return;
    }
    $q = $wpdb->prepare("SELECT slug FROM `tsm_terms` WHERE  `name` = %s", $art_no);
    $result = $wpdb->get_results($q);
    // if (!empty($result)) 
    // {
        $slug = $result[0]->slug; 

        $check_artno = $wpdb->prepare("SELECT COUNT(*) FROM taw_restrict_category
        WHERE art_no = %s AND roleid = %s AND Type = %s", $slug, $role_id,$type);

        $check_artnovalue = $wpdb->get_var($check_artno);

        if ($check_artnovalue > 0) {
            wp_send_json_success(['exists' => true]);
        } else {
            wp_send_json_success(['exists' => false]);
        }
    // }
}
add_action('wp_ajax_custom_uam_checksame_category_exists', 'custom_uam_checksame_category_exists');

function custom_uam_check_restrict_exists() {
    global $wpdb;

    $art_no = isset($_POST['artNo']) ? sanitize_text_field($_POST['artNo']) : '';
    $role_id = isset($_POST['roleId']) ? sanitize_text_field($_POST['roleId']) : '';
    $type = isset($_POST['type']) ? sanitize_text_field($_POST['type']) : '';

    if (empty($art_no) || empty($role_id)) {
        wp_send_json_error(['message' => 'Invalid parameters.']);
        return;
    }

    // Check if the role_id is actually a user ID
    if (is_numeric($role_id)) {
        $user = get_userdata($role_id);
        if ($user) {
            $user_roles = $user->roles;
            if (!empty($user_roles)) {
                $role_id = $user_roles[0]; // Assuming the user has only one role
            } else {
                wp_send_json_error(['message' => 'No role found for this user.']);
                return;
            }
        } else {
            wp_send_json_error(['message' => 'User not found.']);
            return;
        }
    }
    $q = $wpdb->prepare("SELECT slug FROM `tsm_terms` WHERE  `name` = %s", $art_no);
    $result = $wpdb->get_results($q);
    $slug = $result[0]->slug; 
    // Check if the Art No already exists with the provided Role ID and Type
    $check_artno = $wpdb->prepare(
        "SELECT COUNT(*) FROM taw_restrict_category WHERE art_no = %s AND roleid = %s ",
        $slug, $role_id
    );
    $exists = $wpdb->get_var($check_artno);

    // Prepare the response
    $response = array(
        'exists' => $exists > 0,
        'type_check' => $exists > 0 // Optional: You can include more details here
    );

    wp_send_json_success($response);
}

add_action('wp_ajax_custom_uam_check_restrict_exists', 'custom_uam_check_restrict_exists');

function custom_uam_save_restrictcategory_artno() {
    $response = array('error' => false);
    global $wpdb;
    $returnAletrs = [];

    // Decode the JSON array of art numbers
    $all_artnos = json_decode(stripslashes($_POST['restrict_artno']), true);
    $role_id = isset($_POST['role_id']) ? sanitize_text_field($_POST['role_id']) : '';
    $type = isset($_POST['type']) ? sanitize_text_field($_POST['type']) : '';

    if (empty($all_artnos)) {
        $response['error'] = true;
        $response['message'] = 'Art Nos are required';
        wp_send_json_error($response);
    }

    $new_items = array();

    foreach ($all_artnos as $restrict_artno) {
        $restrict_cate = htmlspecialchars(sanitize_text_field($restrict_artno), ENT_QUOTES, 'UTF-8');

        // Query to get the slug based on the name
        $q = $wpdb->prepare("SELECT slug FROM `tsm_terms` WHERE  `name` = %s", $restrict_cate);
        $result = $wpdb->get_results($q);

        // Check if the query returns any result
        if (!empty($result)) {
            $slug = $result[0]->slug; // Extract the slug value

            // Now insert into taw_restrict_category with the slug
            $query = $wpdb->prepare(
                "INSERT INTO `taw_restrict_category` (`art_no`, `Type`, `roleid`) VALUES (%s, %s, %s);",
                $slug, $type, $role_id
            );
            $insert_result = $wpdb->query($query);

            if ($insert_result === false) {
                $response['error'] = true;
                $response['message'] = 'Failed to save Art No: ' . $restrict_cate;
                wp_send_json_error($response);
            } else {
                // Prepare the new item to be added to the response
                $new_item = [
                    'art_no' => $slug,  // Save the slug in the response
                    // 'product_title' => '', // Add logic to fetch product title
                    // 'edit_url' => '', // Add logic to fetch edit URL
                    'AlertArray' => $returnAletrs,
                    'Stack-Priceing-alert' => $alertMsg // Adjust the alert message as needed
                ];
                $new_items[] = $new_item;
            }
        } else {
            // If no slug is found, handle the error gracefully
            $response['error'] = true;
            $response['message'] = 'Slug not found for Art No: ' . $restrict_cate;
            wp_send_json_error($response);
        }
    }

    // Prepare the response with all new items
    $response['new_items'] = $new_items;
    wp_send_json_success($response);
}
add_action('wp_ajax_custom_uam_save_restrictcategory_artno', 'custom_uam_save_restrictcategory_artno');

function custom_uam_delete_multiplecategory() {
    // Initialize response array
    $stackPricing=[];
    $response = array('success' => false);
    
    // Check if the request is valid
    if (isset($_POST['multipleartNosToDelete'], $_POST['roleId'])) {
        global $wpdb;
        
        // Sanitize input
        $multipleartNosToDelete = explode(',', sanitize_text_field($_POST['multipleartNosToDelete']));
        $roleId = sanitize_text_field($_POST['roleId']);
        
        // Loop through artNos array and delete each entry for the specific role ID
        foreach ($multipleartNosToDelete as $multipleartNo) {
            $checked=checkStackPrice_Before_delete_multiartno($multipleartNo,$roleId);
       
            if(!empty($checked)){
                array_push($stackPricing,$checked);
            }
            $query = $wpdb->prepare(
                "DELETE FROM taw_restrict_category WHERE roleid = '$roleId' AND art_no = '$multipleartNo'"   
            );
            // Execute the query
            $result = $wpdb->query($query);
        
            if ($result === false) {
                $response['error'] = true;
                $response['message'] = 'Failed to delete Art No: ' . $multipleartNo;
                wp_send_json_error($response);
            }
        }

        // If deletion is successful
        $response['success'] = true;
        wp_send_json_success($stackPricing);
    } else {
        // Invalid request handling
        $response['message'] = 'Invalid request parameters.';
        wp_send_json_error($response);
    }
}
add_action('wp_ajax_custom_uam_delete_multiplecategory', 'custom_uam_delete_multiplecategory');

function custom_uam_get_role_categorydata() {
    global $wpdb;
    $role_id = esc_html(trim($_POST['id']));

    $results = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT id, art_no, updated_at, Type, roleid FROM taw_restrict_category WHERE roleid = %s",
            $role_id
        ),
        ARRAY_A
    );

    if ($results) {
        wp_send_json_success($results);
    } else {
        wp_send_json_error('No data found');
    }
}
add_action('wp_ajax_custom_uam_get_role_categorydata', 'custom_uam_get_role_categorydata');
add_action('wp_ajax_nopriv_custom_uam_get_role_categorydata', 'custom_uam_get_role_categorydata');

function custom_uam_delete_category() {
    $response = array('error' => false);
    global $wpdb;

    $artNo = sanitize_text_field($_POST['artNo']);
    $roleId = sanitize_text_field($_POST['roleId']);

    $query = $wpdb->prepare(
        "DELETE FROM taw_restrict_category WHERE roleid = '$roleId' AND art_no = '$artNo'",
    );

    // Execute the query
    $result = $wpdb->query($query);

    if ($result === false) {
        $response['error'] = true;
        $response['message'] = 'Failed to delete Art No: ' . $artNo;
        wp_send_json_error($response);
    }
    wp_send_json_success($response);
}
add_action('wp_ajax_custom_uam_delete_category', 'custom_uam_delete_category');









add_action('wp_ajax_get_edit_url_by_sku', 'get_edit_url_by_sku');
add_action('wp_ajax_nopriv_get_edit_url_by_sku', 'get_edit_url_by_sku');

function get_edit_url_by_sku() {
    if (!isset($_POST['sku'])) {
        wp_send_json_error('SKU not provided');
    }

    $sku = sanitize_text_field($_POST['sku']);
    $product_id = wc_get_product_id_by_sku($sku);

    if (!$product_id) {
        wp_send_json_error('Product not found');
    }

    $edit_url = get_edit_post_link($product_id);
    wp_send_json_success(['edit_url' => $edit_url]);
}

add_filter( 'fkcart_zero_state_shop_link', 'custom_zero_state_shop_link', 10, 2 );

// function custom_zero_state_shop_link( $shop_link, $front ) {
//     // Get the site URL and append the desired product category path
//     $shop_link = 'https://smartstoring.tawdev.com/product-category/pull-out-units/beam-mounted-pull-out-units/';
//     return $shop_link;
// }

function custom_zero_state_shop_link( $shop_link, $front ) {
    $lang = getSiteCurrentLang();
    if($lang=='en')
    {
        $shop_link = get_site_url() . '/product-category/pull-out-units/beam-mounted-pull-out-units/';
    }elseif($lang=='sv'){
        $shop_link = get_site_url() . '/produkt-kategori/utdragsenheter/pallutdragningsenhet/';
    }    
    return $shop_link;
}

function custom_fkcart_shop_continue_link( $shop_link, $front ) {
    $lang = getSiteCurrentLang();
    if($lang=='en')
    {
    return get_site_url() . '/product-category/pull-out-units/beam-mounted-pull-out-units/';
    }elseif($lang=='sv'){
        return get_site_url() . '/produkt-kategori/utdragsenheter/pallutdragningsenhet/';
    }    
}
add_filter( 'fkcart_shop_continue_link', 'custom_fkcart_shop_continue_link', 10, 2 );





// Add this code to your theme's functions.php or a custom plugin

add_action('template_redirect', 'restrict_single_product_access');
function restrict_single_product_access() {
    // Only run this on single product pages
    if (!is_product()) {
        return;
    }
    
    global $wpdb, $post;
    
    // Get the current product
    $product = wc_get_product($post->ID);
    if (!$product) {
        return;
    }
    
    // Get product SKU
    $product_sku = $product->get_sku();
    
    // Get current user info (same logic as before)
    $current_user = wp_get_current_user();
    
    if (!($current_user instanceof WP_User) || $current_user->ID == 0) {
        $user_role = 'custom_uam_guest';
        $userid = 'guest';
    } else {
        $user_roles = $current_user->roles;
        $current_user_role = isset($user_roles[0]) ? $user_roles[0] : '';
        
        // Fetch roles data
        $option_name = 'tsm_user_roles';
        $serialized_roles_data = $wpdb->get_var($wpdb->prepare("SELECT option_value FROM {$wpdb->prefix}options WHERE option_name = %s", $option_name));
        $roles_data = unserialize($serialized_roles_data);
        
        foreach ($user_roles as $role) {
            if (isset($roles_data[$role]['roleissubrole']) && $roles_data[$role]['roleissubrole'] == '1') {
                foreach ($roles_data as $role_key => $role_data) {
                    if (isset($role_data['subroles']) && in_array($current_user_role, $role_data['subroles'])) {
                        $user_role = $role_key;
                        break;
                    }
                }
            } else {
                $user_role = isset($user_roles[0]) ? $user_roles[0] : 'guest';
            }
        }
        
        $userid = $current_user->ID;
    }
    
    // Fetch the customerno for the current user
    $customerno_query = $wpdb->prepare("SELECT meta_value FROM tsm_usermeta WHERE user_id = %d AND meta_key LIKE %s", $userid, '%customer_no%');
    $customerno = $wpdb->get_var($customerno_query);
    
    if ($customerno) {
        $user_ids_query = $wpdb->prepare("SELECT user_id FROM tsm_usermeta WHERE meta_value = %s", $customerno);
        $related_user_ids = $wpdb->get_col($user_ids_query);
    } else {
        $related_user_ids = [$current_user->ID];
    }
    
    $roleid_list = implode(',', array_map('intval', $related_user_ids));
    
    // Check product restrictions (same logic as before)
    $restrictuserout = $wpdb->get_results("
        SELECT art_no, roleid, 'user' as Type
        FROM taw_restrict_product 
        WHERE art_no = '$product_sku' 
        AND roleid NOT IN ($roleid_list) 
        AND Type='user'
    ", ARRAY_A);
    
    $restrictuserin = $wpdb->get_results("
        SELECT art_no, roleid, 'user' as Type
        FROM taw_restrict_product 
        WHERE art_no = '$product_sku' 
        AND roleid IN (" . implode(',', array_map('intval', $related_user_ids)) . ") 
        AND Type='user'
    ", ARRAY_A);
    
    $restrictroleout = $wpdb->get_results("
        SELECT art_no, roleid, 'role' as Type
        FROM taw_restrict_product 
        WHERE art_no = '$product_sku' 
        AND roleid != '$user_role' 
        AND Type='role'
    ", ARRAY_A);
    
    $restrictrolein = $wpdb->get_results("
        SELECT art_no, roleid, 'role' as Type
        FROM taw_restrict_product 
        WHERE art_no = '$product_sku' 
        AND roleid = '$user_role' 
        AND Type='role'
    ", ARRAY_A);
    
    // Merge results
    $mergedout_results = array_merge($restrictroleout, $restrictuserout);
    $mergedin_results = array_merge($restrictrolein, $restrictuserin);
    
    // Create lookup for in_results
    $in_result_lookup = [];
    foreach ($mergedin_results as $in_art_no_obj) {
        $in_result_lookup[$in_art_no_obj['art_no']] = $in_art_no_obj['Type'];
    }
    
    // Check if product should be restricted
    $is_restricted = false;
    
    foreach ($mergedout_results as $out_art_no_obj) {
        $out_art_no = $out_art_no_obj['art_no'];
        $out_art_type = $out_art_no_obj['Type'];
        
        if (isset($in_result_lookup[$out_art_no])) {
            if ($in_result_lookup[$out_art_no] !== 'user' && $out_art_type === 'user') {
                $is_restricted = true;
                break;
            }
        } else {
            $is_restricted = true;
            break;
        }
    }
    
    // If product is restricted, redirect or show error
    if ($is_restricted) {
        // You can redirect to a custom page or show a message
        wp_redirect(home_url('/restricted-access/')); // Change this to your preferred URL
        exit;
        
        // OR show a message
        // wp_die(__('You do not have permission to access this product.', 'your-text-domain'));
    }
}