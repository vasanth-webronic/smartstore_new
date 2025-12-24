
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
define('TAW_FILE_VERSION', "?v=7.618");
define('TAW_ROLE_B2B', "custom_uam_b2b");
define('TAW_ROLE_RESELLER_SEK', "custom_uam_reseller_sek");
define('TAW_ROLE_RESELLER_EUR', "custom_uam_reseller_eur");



function taw_loadMyscript()
{

    // if (is_page(array('product','shop'))) {       
    wp_enqueue_style('tailwind-css', THINGSATWEB_BASE . '/css/tailwind.css' . TAW_FILE_VERSION);
    wp_enqueue_style('product-css', THINGSATWEB_BASE . '/css/product.css' . TAW_FILE_VERSION);
    wp_enqueue_style('slider-css', THINGSATWEB_BASE . '/css/slider.css' . TAW_FILE_VERSION);
    wp_enqueue_script('product-js', THINGSATWEB_BASE . '/js/product.js' . TAW_FILE_VERSION, ['jquery', 'jquery-migrate'], null, true);
    wp_enqueue_script('slider-js', THINGSATWEB_BASE . '/js/slider.js' . TAW_FILE_VERSION, ['jquery', 'jquery-migrate'], null, true);
    // }
}

function taw_loadMyscriptAdmin()
{
    wp_enqueue_media();
    wp_enqueue_style('backend-taw-css', THINGSATWEB_BASE . '/css/backend.css' . TAW_FILE_VERSION);
    wp_enqueue_script('taw-backend-js', THINGSATWEB_BASE . '/js/backend.js' . TAW_FILE_VERSION, ['jquery', 'jquery-migrate'], null, true);
    wp_enqueue_script('taw-autocomplete-js', THINGSATWEB_BASE . '/js/jquery.autocomplete.min.js', ['jquery', 'jquery-migrate'], null, true);
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
    // add_submenu_page("product_customise", 'Article Diagram', 'Article Diagram', 'manage_options', 'getDiagramList', 'getDiagramList');

    // add_submenu_page("product_customise", 'Shipping', 'Shipping', 'manage_options', 'thingsatweb_shipping');
    add_submenu_page("product_customise", "Imports", "Imports", "manage_options", "product_imports", "productImportPage");
    add_submenu_page("product_customise", "Exports", "Exports", "manage_options", "product_exports", "productExportPage");
    add_submenu_page("product_customise", "Sync To Product", "Sync To Product", "manage_options", "syncToProduct", "syncToProduct");
    add_submenu_page("product_customise", "Sync To Woocommerce", "Sync To Woocommerce", "manage_options", "syncToWoocommerce", "syncToWoocommerce");
}
function custom_rewrite_rule()
{

    add_rewrite_tag('%id%', '([^&]+)');
    add_rewrite_tag('%type%', '([^&]+)');
    add_rewrite_rule('^downloads/([^/]*)/?', 'index.php?pagename=downloads&id=$matches[1]&type=$matches[2]', 'top');

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
    $query = "SELECT ID FROM {$wpdb->posts} WHERE post_type='attachment' and post_name='$filename'";
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
    $exRec = $wpdb->get_var("SELECT id FROM `taw_article_attributes` where art_no='$art_no'");

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
        $pic_name = get_the_title($attach_id);
        $gallery_pics = implode(',', $product->get_gallery_image_ids());
        $alt_text = get_post_meta($attach_id, '_wp_attachment_image_alt', true);
        $product_attributes = $product->get_attributes();

        $product_language = apply_filters('wpml_post_language_details', null, $id);
        $lang_code = isset($product_language['language_code']) ? $product_language['language_code'] : '';
        $uuid = "$lang_code::$sku";
        // Escape values to prevent SQL injection

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
        $existing_title = $wpdb->get_row($wpdb->prepare("SELECT * FROM `taw_article_title` WHERE `art_no` = %s AND `lang` = %s", $sku, $cur_lang));

        if (empty($existing_title)) {

            $insert_title = $wpdb->prepare("INSERT INTO `taw_article_title` (`art_no`, `title`, `desc`, `lang`, `uuid`, `shortdesc`) 
            VALUES (%s, %s, %s, %s, %s, %s)", $sku, $title, $desc, $lang_code, $uuid, $shortdesc);
             //print_r($insert_title);
            $wpdb->query($insert_title);
        }else{
            $update_titlequery = "UPDATE `taw_article_title`
            SET `title` = '$title', `desc` = '$desc',  `shortdesc` = '$shortdesc'
            WHERE `art_no` = '$sku' and `lang` = '$lang_code'";
            //print_r($update_titlequery);
            $wpdb->query($update_titlequery);
            }
           
        

        //Picture

        $existing_picture = $wpdb->get_row(
            $wpdb->prepare("SELECT * FROM `taw_article_picture` WHERE `art_no` = %s AND `lang` = %s", $sku, $cur_lang)
        );
        if (empty($existing_picture)) {
            $color = '';
            $insert_picture = $wpdb->prepare("INSERT INTO `taw_article_picture` (`art_no`, `pic_name`, `colour`,`alt_text`, `attach_id`, `lang`, `unique_code`, `gallery_pics`) 
                        VALUES (%s, %s, %s, %s, %s, %s, %s, %s)", $sku, $pic_name, $color, $alt_text, $attach_id, $lang_code, $uuid, $gallery_pics);
            $wpdb->query($insert_picture);
           // print_r($insert_picture);
        }else{
            $color = '';
            $update_picquery = "UPDATE `taw_article_picture`
            SET `pic_name` = '$pic_name', `colour` = '$color',  `alt_text` = '$alt_text', `attach_id` = '$attach_id',  `unique_code` = '$uuid',  `gallery_pics` = '$gallery_pics'
            WHERE `art_no` = '$sku' and `lang` = '$lang_code'";
           // print_r($update_picquery);
            $wpdb->query($update_picquery);

        }

        //Attribute 

        $existing_attribute = $wpdb->get_row("SELECT * FROM `taw_article_attributes` WHERE `art_no` = %s", $sku);

        if (empty($existing_attribute)) {
            if (!empty($product_attributes)) {

                foreach ($product_attributes as $attribute_name => $attribute) {
                    $attr_id = $attribute->get_name();
                    $term_ids = $attribute->get_terms();

                    if ($term_ids) {
                        // $term_ids_str = implode(',', wp_list_pluck($term_ids, 'slug'));
                        $term_ids_str = implode(',', array_map(function ($term) {
                            return str_replace('-sv', '', $term->slug);
                        }, $term_ids));

                        $insert_attribute = $wpdb->prepare("INSERT INTO `taw_article_attributes` (`art_no`, `attr_id`, `term_ids`) 
                    VALUES (%s, %s, %s)", $sku, $attr_id, $term_ids_str);
                        $wpdb->query($insert_attribute);
                    }
                }
            }
        }else{
            foreach ($product_attributes as $attribute_name => $attribute) {
                $attr_id = $attribute->get_name();
                $term_ids = $attribute->get_terms();

                if ($term_ids) {
                    // $term_ids_str = implode(',', wp_list_pluck($term_ids, 'slug'));
                    $term_ids_str = implode(',', array_map(function ($term) {
                        return str_replace('-sv', '', $term->slug);
                    }, $term_ids));

                    $update_attribute = "UPDATE `taw_article_attributes`
            SET `attr_id` = '$attr_id', `term_ids` = '$term_ids_str'
            WHERE `art_no` = '$sku' ";
            //print_r($update_titlequery);
                    $wpdb->query($update_attribute);

        }}}

        // Insert Price


        $post_meta = $wpdb->get_row("SELECT * FROM `tsm_postmeta` WHERE `post_id` = '$id' and `meta_key`='taw_prod_opt'");

        if ($post_meta !== null) {
            $meta_value = unserialize($post_meta->meta_value);

            if (isset($meta_value['article_price']['b2b'])) {
                $price_b2b = $meta_value['article_price']['b2b'];
            } else {
                $price_b2b = null;
            }

            if (isset($meta_value['article_price']['reseller_sek'])) {
                $price_reseller_sek = $meta_value['article_price']['reseller_sek'];
            } else {
                $price_reseller_sek = null;
            }

            if (isset($meta_value['article_price']['reseller_eur'])) {
                $price_reseller_eur = $meta_value['article_price']['reseller_eur'];
            } else {
                $price_reseller_eur = null;
            }

            // Check if any of the prices are not 0 and not empty
            if ($price_b2b != 0 || $price_reseller_sek != 0 || $price_reseller_eur != 0) {
                if ($price_b2b !== '' || $price_reseller_sek !== '' || $price_reseller_eur !== '') {
                    $existing_price = $wpdb->get_row("SELECT * FROM `taw_article_price` WHERE `art_no` = %s", $sku);

                    if (empty($existing_price)) {
                        $insert_price = $wpdb->prepare("INSERT INTO `taw_article_price` (`art_no`, `price_b2b`, `price_reseller_eur`, `price_reseller_sek`) 
                        VALUES (%s, %d, %d, %d)", $sku, $price_b2b, $price_reseller_eur, $price_reseller_sek);

                        // Ensure the prepared statement is correct
                        // print_r($wpdb->prepare("INSERT INTO `taw_article_price` (`art_no`, `price_b2b`, `price_reseller_eur`, `price_reseller_sek`) 
                        // VALUES (%s, %d, %d, %d)", $sku, $price_b2b, $price_reseller_eur, $price_reseller_sek));

                        // Execute the insert query
                        $wpdb->query($insert_price);
                    } else {
                        //print_r(2);
                        // The SKU already exists, you might want to perform an update here.
                        $update_price = "UPDATE `taw_article_price`
                        SET `price_b2b` = '$price_b2b', `price_reseller_eur` = '$price_reseller_eur', `price_reseller_sek` = '$price_reseller_sek'
                        WHERE `art_no` = '$sku' ";
                        //print_r($update_titlequery);
                                $wpdb->query($update_price);
            
                    }
                }
            }
        }


        //Insert Customer Uniqueprice

        if (isset($meta_value['article_price']['customer_price'])) {
            $customer = $meta_value['article_price']['customer_price'];
        } else {
            $customer = null;
        }
        $existing_uniqueprice = $wpdb->get_row("SELECT * FROM `taw_customer_unique_price` WHERE `art_no` = %s", $sku);
        if (empty($existing_uniqueprice)) {
            if (is_array($customer)) {
            foreach ($customer as $c) {
                $customerid = explode('::', $c['customer'])[0];
                $customerprice = $c['price'];
                if (isset($c['currency'])) {
                $customercurrency = $c['currency'];
            }
                if ($customerid != 0 && $customerid != '') {
                    $insert_uniqueprice = $wpdb->prepare("INSERT INTO `taw_customer_unique_price` (`customer_no`, `price`, `currency`, `art_no`) 
            VALUES (%s, %s, %s, %s)", $customerid, $customerprice, $customercurrency, $sku);
                    $wpdb->query($insert_uniqueprice);
                }
            }
        }
        }else{
            $query_deleteuniqueprice="DELETE FROM `taw_customer_unique_price` WHERE `art_no` = '$sku';";
            $wpdb->query($query_deleteuniqueprice);
            if (is_array($customer)) {
                foreach ($customer as $c) {
                    $customerid = explode('::', $c['customer'])[0];
                    $customerprice = $c['price'];
                    if (isset($c['currency'])) {
                    $customercurrency = $c['currency'];
                }
                    if ($customerid != 0 && $customerid != '') {
                        $insert_uniqueprice = $wpdb->prepare("INSERT INTO `taw_customer_unique_price` (`customer_no`, `price`, `currency`, `art_no`) 
                VALUES (%s, %s, %s, %s)", $customerid, $customerprice, $customercurrency, $sku);
                        $wpdb->query($insert_uniqueprice);
                    }
                }
            }

            

        }

        // Insert Category

        $category = $product->get_category_ids();

        if (!empty($category)) {
            $firstCategoryId = $category[0] ?? 0;
            $parentquery = "SELECT slug FROM tsm_terms WHERE term_id = '$firstCategoryId'";
            $parentquery = $wpdb->get_var($parentquery);

            $secondCategoryId = $category[1] ?? 0;
            $childquery = "SELECT slug FROM tsm_terms WHERE term_id = '$secondCategoryId'";
            $childquery = $wpdb->get_var($childquery);

            $uniqucode = "$sku::$lang_code::$childquery>>$parentquery";

            $existing_category = $wpdb->get_row("SELECT * FROM `taw_article_category` WHERE `art_no` = '$sku' and `lang` = '$cur_lang'");

            if (empty($existing_category)) {

                $insert_category = $wpdb->prepare("INSERT INTO `taw_article_category` (`art_no`, `term_id`, `lang`, `parent_cate`, `unique_code`) 
            VALUES (%s, %s, %s, %s, %s)", $sku, $firstCategoryId, $lang_code, $secondCategoryId, $uniqucode);
                $wpdb->query($insert_category);
            }else{
                $query_deletecategory = "DELETE FROM taw_article_category WHERE `art_no` = '$sku' and `lang` = '$lang_code';";
                $wpdb->query($query_deletecategory);

              $insert_categorydata = $wpdb->prepare("INSERT INTO `taw_article_category` (`art_no`, `term_id`, `lang`, `parent_cate`, `unique_code`) 
            VALUES (%s, %s, %s, %s, %s)", $sku, $firstCategoryId, $lang_code, $secondCategoryId, $uniqucode);
                $wpdb->query($insert_categorydata);
            }
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

add_action("wp_ajax_sync_woocommerce_data", 'sync_woocommerce_data');
function sync_woocommerce_product_data()
{
   // echo "<script>console.log('started sync');</script>";
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
    } else {
        $data = ['end' => 1];
    }

    wp_send_json_success($data);
}

function sync_attributes($page_num)
{
    global $wpdb;

    $page_item = 50;
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
    $page_item = 50;
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
            } else {
                $product = wc_get_product($cur_id);
            }
        }

        if (empty($product)) {
            continue;
        }

        $product->set_name($r->title);
        $product->set_regular_price($r->price_b2b);

        $sentances = explode(".", $r->desc);
        $short_desc = $r->desc;
        if (count($sentances) > 2) {
            $short_desc = $sentances[0] . ". " . $sentances[1] . ".";
        }
        $product->set_short_description($r->shortdesc);
        $product->set_description($r->desc);

        $product->save();

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
                $pic_name = get_the_title($attach_id);
            }

            // Insert Title and description

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

            $existing_record_picture = $wpdb->get_row("SELECT * FROM `taw_article_picture` WHERE `art_no` = '$art_no' and `lang` = '$lang'");

            if ($existing_record_picture) {
                $query_pic = "UPDATE `taw_article_picture`
                        SET `pic_name` = '$pic_name', `alt_text` = '$alt_text', `attach_id` = '$attach_id', `lang` = '$lang', `unique_code` = '$lang::$art_no', `gallery_pics` = '$gallery_pics'
                        WHERE `art_no` = '$art_no' and `lang` = '$lang'";
            } else {
                if(!empty( $pic_name) || !empty( $gallery_pics) || !empty($attach_id)){
                $query_pic = "INSERT INTO `taw_article_picture` (`art_no`, `pic_name`, `colour`, `alt_text`, `attach_id`, `lang`, `unique_code`, `gallery_pics`)
                        VALUES ('$art_no', '$pic_name', '', '$alt_text', '$attach_id', '$lang', '$lang::$art_no', '$gallery_pics')";
            }   }
            $wpdb->query($query_pic);

            // Insert Category

            $category = $product->get_category_ids();
        
            if (!empty($category)) {
                $firstCategoryId = $category[0] ?? 0;
                $parentquery = "SELECT slug FROM tsm_terms WHERE term_id = '$firstCategoryId'";
                $parentquery = $wpdb->get_var($parentquery);
        
                $secondCategoryId = $category[1] ?? 0;
                $childquery = "SELECT slug FROM tsm_terms WHERE term_id = '$secondCategoryId'";
                $childquery = $wpdb->get_var($childquery);

                $uniqucode="$art_no::$lang::$childquery>>$parentquery";
                
                //$existing_record_category = $wpdb->get_row("SELECT * FROM `taw_article_category` WHERE `art_no` = '$art_no' and `lang` = '$lang'");
                $query_deletecategory = "DELETE FROM taw_article_category WHERE `art_no` = '$art_no' and `lang` = '$lang';";
                $wpdb->query($query_deletecategory);
                // if ($existing_record_category) {
                    
                //     $query_cate = "UPDATE `taw_article_category`
                //             SET `term_id` = '$firstCategoryId', `lang` = '$lang', `parent_cate` = '$secondCategoryId', `unique_code` = '$uniqucode'
                //             WHERE `art_no` = '$art_no' and `lang` = '$lang'";
                //             print_r( $query_cate);
                // } else {
                //     print_r(2);
                    $query_cate = "INSERT INTO `taw_article_category` (`art_no`, `term_id`, `lang`, `parent_cate`, `unique_code`)
                            VALUES ('$art_no', '$firstCategoryId', '$lang', '$secondCategoryId', '$uniqucode')";
    //  print_r(   $query_cate );
    //  exit;
    //             // }
                // exit;
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
                VALUES ('$art_no', '$acs_article', '$pallets')";
                $wpdb->query($query_accessories);
            }

            
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

 //add_action('save_post', 'set_product', 20, 2);
 add_action('wp_trash_post', 'custom_action_on_trash', 10, 1);

function custom_action_on_trash($post_id) 
{
    global $wpdb;
    $lang = getSiteCurrentLang();
    $product = wc_get_product($post_id);
    if (get_post_type($post_id) === 'product') 
    {
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
        $pic_name = get_the_title($attach_id);
        $gallery_pics = implode(',', $product->get_gallery_image_ids());
        $alt_text = get_post_meta($attach_id, '_wp_attachment_image_alt', true);
        $product_attributes = $product->get_attributes();
        $category = $product->get_category_ids();

        // Escape values to prevent SQL injection
        $art_no = esc_sql($art_no);
        $title = esc_sql($title);
        $desc = esc_sql($desc);
        $shortdesc = esc_sql($shortdesc);
        $lang = esc_sql($lang);
        $attach_id = esc_sql($attach_id);
        $pic_name = esc_sql($pic_name);
        $gallery_pics = esc_sql($gallery_pics);
        $alt_text = esc_sql($alt_text);

        //Restore Title
 
        $restore_title = $wpdb->get_row($wpdb->prepare("SELECT * FROM `taw_article_title` 
        WHERE `art_no` = %s AND `lang` = %s",$art_no,$lang));
       
            
        if (!$restore_title) {
            $wpdb->insert('taw_article_title',
            array('art_no' => $art_no,'title' => $title,'desc' => $desc,'lang' => $lang,'uuid' => $lang . '::' . $art_no,'shortdesc' => $shortdesc),
                array('%s', '%s', '%s', '%s', '%s', '%s'));
        }
      

        //Restore Category

        $restore_category = $wpdb->get_row($wpdb->prepare("SELECT * FROM `taw_article_category` 
        WHERE `art_no` = %s AND `lang` = %s", $art_no,$lang));
        //   print_r($art_no);
        //   exit;
        if (!empty($category)) {
            if (!$restore_category) {
                $firstCategoryId = $category[0] ?? 0;
                $parentquery = "SELECT slug FROM tsm_terms WHERE term_id = '$firstCategoryId'";
                $parentquery = $wpdb->get_var($parentquery);
        
                $secondCategoryId = $category[1] ?? 0;
                $childquery = "SELECT slug FROM tsm_terms WHERE term_id = '$secondCategoryId'";
                $childquery = $wpdb->get_var($childquery);

                $uniqucode="$art_no::$lang::$childquery>>$parentquery";

                $wpdb->insert('taw_article_category',
                array('art_no' => $art_no,'term_id' => $firstCategoryId,'lang' => $lang,'parent_cate' => $secondCategoryId,'unique_code' => $uniqucode),
                array('%s', '%s', '%s', '%s', '%s'));
            }
        }
        //Restore Picture

        // $restore_picture = $wpdb->get_row($wpdb->prepare("SELECT * FROM `taw_article_picture` 
        // WHERE `art_no` = %s AND `lang` = %s", $art_no,$lang));

        // if (!$restore_picture) {
        //     $wpdb->insert('taw_article_picture',
        //     array('art_no' => $art_no,'pic_name' => $pic_name,'colour' => '','alt_text' => $alt_text,'attach_id' => $attach_id,'lang' => $lang,'unique_code' => $lang::$art_no,'gallery_pics' => $gallery_pics,),
        //     array('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s'));
        // }

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

//         $restore_attribute = $wpdb->get_row($wpdb->prepare("SELECT * FROM `taw_article_attributes` 
//         WHERE `art_no` = %s", $art_no));

//         if (!empty($product_attributes)) {

//             if (!$restore_attribute) {
//                 foreach ($product_attributes as $attribute_name => $attribute) {
//                     $attr_id = $attribute->get_name();
//                     $term_ids = $attribute->get_terms();
                
//                     if ($term_ids) {
//                         $term_ids_str = implode(',', wp_list_pluck($term_ids, 'slug'));
//                         $wpdb->insert('taw_article_attributes',
//                         array('art_no' => $art_no,'attr_id' => $attr_id,'term_ids' => $term_ids_str),
//                         array('%s', '%s', '%s'));                    
//                     }
//                 }
//             }
//         }

        // Restore Uniqueprice
        
        // $restore_uniqueprice = $wpdb->get_row($wpdb->prepare("SELECT * FROM `taw_customer_unique_price` 
        // WHERE `art_no` = %s", $art_no));

        // if (!$restore_uniqueprice) {
        //     $customer = $meta_value['article_price']['customer_price'];
        //     foreach($customer as $c){
        //         $customerid=explode('::',$c['customer'])[0];
        //         $customerprice=$c['price'];
        //         $customercurrency=$c['currency'];

        //         $wpdb->insert('taw_customer_unique_price',
        //         array('customer_no' => $customerid,'price' => $customerprice,'currency' => $customercurrency,'art_no' => $art_no),
        //         array('%s', '%s', '%s', '%s'));
        //     }
        // }

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

        $meta = get_post_meta($id, 'taw_prod_opt');
        if (!isset($meta['article_price'])) {
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
        $q = "SELECT currency,customer_no,price,usr.display_name FROM `taw_customer_unique_price` as cpz left join tsm_users as usr on usr.user_login=cpz.customer_no WHERE cpz.art_no='$sku'";
        $customer_data = $wpdb->get_results($q);


        if (!isset($meta['article_price']['customer_price'])) {
            $meta['article_price']['customer_price'] = [];
        }
        $customer = [];
        foreach ($customer_data as $c) {
            $meta['article_price']['customer_price'][] = ["customer" => $c->customer_no . "::" . $c->display_name, "price" => $c->price,  "currency" => $c->currency];
        }

        // $meta['article_price']['customer_price'] = array_unique($meta['article_price']['customer_price']);
        if (is_array($meta['article_price']['customer_price'])) {
            $meta['article_price']['customer_price'] = array_unique($meta['article_price']['customer_price']);
        } 

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
add_filter('woocommerce_product_get_price', 'custom_price', 99, 2);
add_filter('woocommerce_product_get_regular_price', 'custom_price', 99, 2);
// Variations 
add_filter('woocommerce_product_variation_get_regular_price', 'custom_price', 99, 2);
add_filter('woocommerce_product_variation_get_price', 'custom_price', 99, 2);

// Variable (price range)
add_filter('woocommerce_variation_prices_price', 'custom_variable_price', 99, 3);
add_filter('woocommerce_variation_prices_regular_price', 'custom_variable_price', 99, 3);

// Handling price caching (see explanations at the end)
add_filter('woocommerce_get_variation_prices_hash', 'add_price_multiplier_to_variation_prices_hash', 99, 3);


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

    if (!is_user_logged_in()) {
        return "0";
    }

    if (!(current_user_can('c_uam_cap_price') || current_user_can('c_uam_cap_reseller_price'))) {
        return "0";
    }

    global $wpdb;
    $meta = get_post_meta($product->get_ID(), 'taw_prod_opt', true);

    $user = wp_get_current_user();

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
    $json = json_decode($product, true);
    $metat_data = get_post_meta($json['id'], 'taw_prod_opt', true);
    $article_price = $metat_data['article_price'] ?? [];

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

    $datasheet = THINGSATWEB_BASE . '/img/ic_datasheet.svg';
    $instructions = THINGSATWEB_BASE . '/img/ic_instructions.svg';
    $warehouse = THINGSATWEB_BASE . '/img/ic_warehouse.svg';
    $load_capacity = THINGSATWEB_BASE . '/img/ic_load_capacity.svg';
    $stepfile = THINGSATWEB_BASE . '/img/stepfilered.png';
    $addspareparts = THINGSATWEB_BASE . '/img/screw.png';


    $attributes = $product->get_attributes();

?>
    <div class="flex justify-center lg:justify-start mt-6">
        <?php if(current_user_can('c_uam_cap_data_sheet')):
        ?>

        <a href="/downloads/<?php echo $product->get_id() ?>/datasheet?print=generate_datasheet">          
            <div class="flex flex-row items-center bg-red-600 rounded-full p-1 cursor-pointer">
                <div class="w-4 h-4 p-0.5 bg-white rounded-full flex items-center justify-center">
                    <img class="text-center" src=<?php echo $datasheet; ?> alt="Datasheet">
                </div>
                <span class="text-xs text-white px-1">Datasheet</span>
            </div>
        </a>
        <?php endif;
        ?>


        <?php 
        $product_id=$product->get_id();
        $product = wc_get_product($product_id);
        $meta = get_post_meta($product->get_id(), 'taw_prod_opt');
        $meta = isset($meta[0]) ? $meta[0] : array();
        $diagram = $meta['article_price']['product_step_file']['url'];
        if (!empty($diagram)) :
        ?>
        <a href="/downloads/<?php echo $product->get_id() ?>/stepfile?step=generate_stepfile">
            <div class="flex flex-row items-center bg-red-600 rounded-full p-1 cursor-pointer 
            <?php if ($isDataSheetEnabled == 1) : ?> ml-7 <?php endif; ?>">
                <div class="w-4 h-4 p-0.5 bg-white rounded-full flex items-center justify-center">
                    <img class="text-center" src="<?php echo $stepfile; ?>" alt="Instructions" style="padding:2px;">
                </div>
                <span class="text-xs text-white px-1">Step File</span>
            </div>
        </a>
        <?php endif;
        ?>

        <?php if ($isInstructionsEnabled == 1 && current_user_can('c_uam_cap_instruction_pdf') && !empty($instructionsFileName)) : ?>
            <a href="/downloads/<?php echo $instructionsFileId; ?>/<?php echo $instructionsFileName; ?>">
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
    $back = THINGSATWEB_BASE . '/img/ic_backward_arrow.svg';
?>
    <div class="hidden md:flex items-center cursor-pointer mb-3" onclick="history.go(-1);">
        <div class="w-2 h-auto xl:block">
            <img src=<?php echo $back; ?> alt="Back">
        </div>
        <span class="ml-1 text-sm font-medium">Back</span>
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
    // if ((strpos($productcategory, "Tailor-made product") === false) || (strpos($productcategory, "Kundanpassad Produkt") !== false)) {
    //     if (
    //         isset($formatted_attributes['Load metod']) || isset($formatted_attributes['Type of load']) || isset($formatted_attributes['Load option'])
    //         || isset($formatted_attributes['Load Way']) || isset($formatted_attributes['Picking Method']) || isset($formatted_attributes['Orientation'])
    //         || isset($formatted_attributes['Mobile']) || isset($formatted_attributes['Handled']) || isset($formatted_attributes['Frame Color'])
    //         || isset($formatted_attributes['Gravity']) || isset($formatted_attributes['Top Beam']) || isset($formatted_attributes['Section type'])
    //         || isset($formatted_attributes['Load']) || isset($formatted_attributes['Extension']) || isset($formatted_attributes['Loading Width'])
    //         || isset($formatted_attributes['Loading Depth']) || isset($formatted_attributes['Width']) || isset($formatted_attributes['Length'])
    //         || isset($formatted_attributes['Height']) || isset($formatted_attributes['Package Size']) || isset($formatted_attributes['Colour'])
    //         || isset($formatted_attributes['Depth']) || isset($formatted_attributes['Loading height']) || isset($formatted_attributes['Rack Depth'])
    //         || isset($formatted_attributes['Quantity in the Package'])
    //     ) {

    //         $title = __('Technical Specification old', 'elementor');
    //         if ($lang === 'sv') {
    //             $title = __('Teknisk specifikation', 'elementor');
    //         }
    //         $tabs['technicalchange'] = [
    //             'title' => $title,
    //             'priority' => 21,
    //             'callback' => 'woocommerce_product_technicalchange_tab',
    //         ];
    //     }
    // }

    //Product Features
    
    if ((strpos($productcategory, "Tailor-made product") === false) || (strpos($productcategory, "Kundanpassad Produkt") !== false)) {
        if (isset($formatted_attributes['Weight capacity']) || isset($formatted_attributes['Extension'])
            || isset($formatted_attributes['Colour']) || isset($formatted_attributes['Load metod']) || isset($formatted_attributes['Type of load'])
            || isset($formatted_attributes['Loading']) ||  isset($formatted_attributes['Load Way']) || isset($formatted_attributes['Picking Method'])
            || isset($formatted_attributes['Short or long side handled']) || isset($formatted_attributes['Number of shelves']) || isset($formatted_attributes['Shelf lock'])) 
        {
            $tabs['productfeatures'] = [
                'title' => __('Product Features', 'elementor'),
                'priority' => 21,
                'callback' => 'woocommerce_product_productfeatures_tab',
            ];
        }
    }

    //Technical Specification
    if ((strpos($productcategory, "Tailor-made product") === false) || (strpos($productcategory, "Kundanpassad Produkt") !== false)) {
            if (isset($formatted_attributes['Loading Width']) || isset($formatted_attributes['Loading Depth']) || isset($formatted_attributes['Height'])
                || isset($formatted_attributes['Rack Depth']) || isset($formatted_attributes['Width']) || isset($formatted_attributes['Length'])
                || isset($formatted_attributes['Depth']) || isset($formatted_attributes['Loading height'])
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
        if ((isset($formatted_attributes['Product Weight'])) || (isset($formatted_attributes['Quantity at pallet'])) ||
            (isset($formatted_attributes['Package weight'])) || (isset($formatted_attributes['Number of pallets / Trailer'])) ||
            (isset($formatted_attributes['Quantity in the Package'])) || (isset($formatted_attributes['Package Size']))
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
    global $wpdb;
    $product = wc_get_product($product_id);

    include_once(__DIR__ . '/vendor/autoload.php');

    $dompdf = new \Dompdf\Dompdf();

    ob_start(); // Start output buffering

    include(__DIR__ . '/template/page-print.php'); // Include the template

    $html = ob_get_clean(); // Store the buffered output in $html

    $dompdf->loadHtml($html);

    $dompdf->setPaper(array(0, 0, 800, 1300), 'mm');

    $dompdf->render();

    $canvas = $dompdf->getCanvas();
    $PAGE_NUM = $canvas->get_page_number(); // Retrieve the page number
    $PAGE_COUNT = $canvas->get_page_count();

    $family_name = $obj['extra']['family_name'];

    // Generate a unique filename for the PDF
    $filename = urldecode($product->get_sku()) . '.pdf'; // Use rawurlencode to encode the filename
    //$filename = str_replace('%20', ' ', $filename);
    $dompdf->stream($filename, array('Attachment' => true)); // Set the 'Attachment' option to true

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


function quadlayers_add_to_cart_function () {
    $data = array_merge((array) $_GET, (array) $_POST);
    $product_id = isset($data['product_id']) ? $data['product_id'] : "";
    $product_qty = isset($data['product_qty']) ? $data['product_qty'] : "";
    

    // if ( WC()->cart->get_cart_contents_count() == 0 ) {
        if ( WC()->cart->add_to_cart( $product_id,  $product_qty)){
        $response['status'] = '1'; // Successfully added to cart
    } else {
        $response['status'] = '0'; // Cart is not empty
    }

    wp_send_json($response); // Send the JSON-encoded response
}