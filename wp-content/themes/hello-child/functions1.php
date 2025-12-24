<?php /*
 This file is part of a child theme called hello-child.
 Functions in this file will be loaded before the parent theme's functions.
 For more information, please read
 https://developer.wordpress.org/themes/advanced-topics/child-themes/
 */


// this code loads the parent's stylesheet (leave it in place unless you know what you're doing)

function your_theme_enqueue_styles()
{

    $parent_style = 'parent-style';

    wp_enqueue_style(
        $parent_style,
        get_template_directory_uri() . '/style.css'
    );

    wp_enqueue_style(
        'child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array($parent_style),
        wp_get_theme()->get('Version')
    );
}

add_action('wp_enqueue_scripts', 'your_theme_enqueue_styles');

/*  Add your own functions below this line.
======================================== */



add_filter( 'woocommerce_add_to_cart_redirect', 'wp_get_referer', 100 );
function my_scripts_method()
{
    wp_enqueue_script(
        'custom-script',
        get_stylesheet_directory_uri() . '/js/custom_script.js',
        array('jquery')
    );
}

add_filter( 'dgwt/wcas/form/magnifier_ico', function ( $html, $class ) {
  $html = '<i class="fa fa-search ' . $class . '"></i>';
  return $html;
}, 10, 2 );


add_action('wp_enqueue_scripts', 'my_scripts_method');
if (class_exists('CSF')) {
    $lang=getSiteCurrentLang();
    $prefix = 'taw_general_opt';
    
    if($lang!="en"){
        $prefix.="_".$lang;
    }
    CSF::createOptions(
        $prefix,
        array(
            'menu_title' => 'Thingsatweb',
            'menu_slug' => 'thingsatweb',
            'theme' => 'light',
            'framework_title' => wp_kses(
                sprintf(__("Thingsatweb Options <small>V %s</small>", 'thingsatweb'), 1),
                array('small' => array())
            ),
            'footer_credit' => wp_kses(
                __('Developed by: <a target="_blank" href="https://thingsatweb.com">Thingsatweb</a>', 'thingsatweb'),
                array(
                    'a' => array(
                        'href' => array(),
                        'target' => array()
                    ),
                )
            ),
        )
    );



    // // Create a section
    CSF::createSection(
        $prefix,
        array(
            'title' => 'Filter Settings',
            'fields' => array(
                array(
                    'id' => 'filter_attr',
                    'type' => 'group',
                    'title' => 'Filter Settings',
                    'fields' => array(
                        array(
                            'id' => 'title',
                            'type' => 'text',
                            'title' => 'Filter Name',
                        ),
                        array(
                            'id' => 'attr',
                            'type' => 'select',
                            'title' => 'Filter Type',
                            'multiple' => false,
                            'options' => 'getProdAttribute',
                            'placeholder' => 'Select',
                            'sortable' => true,
                            'chosen' => true,
                            'ajax' => true
                        ),
                        array(
                            'id' => 'opt',
                            'type' => 'group',
                            'title' => 'Filter Options',
                            'fields' => array(
                                array(
                                    'id' => 'title',
                                    'type' => 'text',
                                    'title' => 'Title',
                                ),
                                array(
                                    'id' => 'term',
                                    'type' => 'select',
                                    'title' => 'Option',
                                    'multiple' => false,
                                    'options' => 'getProdAttributeTerms',
                                    'placeholder' => 'Select',
                                    'sortable' => true,
                                    'chosen' => true,
                                    'ajax' => true
                                ),
                                array(
                                    'id' => 'display_type',
                                    'type' => 'select',
                                    'title' => 'Display Type',
                                    'options' => ['default' => 'Default', 'img_txt' => 'Image and Text', 'color_txt' => 'Color and Text'],
                                )
                                ,
                                array(
                                    'id' => 'img',
                                    'type' => 'media',
                                    'title' => 'Image',
                                    'dependency' => array('display_type', '==', "img_txt"),
                                ),
                                array(
                                    'id' => 'color',
                                    'type' => 'color',
                                    'title' => 'Color',
                                    'dependency' => array('display_type', '==', 'color_txt'),
                                )
                            ),
                        )
                    )
                )
            )
        )
    );


    function getProdAttribute()
    {
        $attributes = wc_get_attribute_taxonomies();
        $attrs = [];
        foreach ($attributes as $key => $value) {
            $attrs[$value->attribute_name . "::" . $value->attribute_label] = $value->attribute_label;
        }
        return $attrs;
    }

    function getProdAttributeTerms()
    {
        global $wpdb;
        $data = array_merge((array) $_GET, (array) $_POST);

        $attributes = wc_get_attribute_taxonomies();
        $attrs = [];
        foreach ($attributes as $key => $value) {
            $terms = get_terms('pa_' . $value->attribute_name, ['hide_empty' => false]);
            foreach ($terms as $t) {
                $attrs[$value->attribute_name . "::" . $t->term_id . "::" . $t->name] = $value->attribute_name . "::" . $t->term_id . "::" . $t->name;
            }
        }
        return $attrs;
    }

    $prefix = 'taw_prod_opt';
    //$data=get_option('taw_prod_opt');
    //Create a metabox
    CSF::createMetabox(
        $prefix,
        array(
            'title' => esc_html__('Product Settings', 'thingsatweb'),
            'post_type' => 'product',
        )
    );

    // Create a section
    CSF::createSection(
        $prefix,
        array(
            'title' => '',
            'fields' => array(
                array(
                    'type' => 'subheading',
                    'content' => 'Article Price',
                ),
                array(
                    'id' => 'article_price',
                    'type' => 'tabbed',
                    'title' => '',
                    'tabs' => array(
                        array(
                            'title' => 'Default Price',
                            // 'icon'      => 'fa fa-heart',
                            'fields' => array(
                                array(
                                    'id' => 'b2b',
                                    'type' => 'number',
                                    //'value' => $b2b_price,
                                    'title' => 'B2B',
                                ),
                                array(
                                    'id' => 'reseller_sek',
                                    'type' => 'number',
                                    //'value' => $price_reseller_sek,
                                    'title' => 'Reseller SEK',
                                ),
                                array(
                                    'id' => 'reseller_eur',
                                    'type' => 'number',
                                    //'value' => $price_reseller_eur,
                                    'title' => 'Reseller EUR',
                                ),
                            )
                        ),
                        array(
                            'title' => 'Accessories',
                            'fields' => array(
                                array(
                                    'id' => 'AccessoriesArticle',
                                    'type' => 'repeater',
                                    'title' => 'Product Accessories',
                                    'fields' => array(
                                        array(
                                            'id' => 'acs_article',
                                            'type' => 'select',
                                            'title' => 'Art No',
                                            'chosen' => true,
                                            'ajax' => true,
                                            'multiple' => false,
                                            'options' => 'searchArticle',
                                        ),
                                        array(
                                            'id' => 'pallet',
                                            'type' => 'number',
                                            'placeholder' => '0',
                                            'title' => 'No of Pallets',
                                        ),
                                )
                            )
                        ),
                    ),
                        array(
                            'title' => 'Special Price',
                            'fields' => array(
                                array(
                                    'id' => 'customer_price',
                                    'type' => 'repeater',
                                    'title' => 'Customers',
                                    'fields' => array(
                                        array(
                                            'id' => 'customer',
                                            'type' => 'select',
                                            'title' => 'Customer',
                                            'placeholder' => 'Search customer',
                                            'chosen' => true,
                                            'ajax' => true,
                                            'multiple' => false,
                                            'options' => 'searchCustomer',
                                        ),
                                        array(
                                            'id' => 'price',
                                            'type' => 'number',
                                            'placeholder' => '0',
                                            'title' => 'Special Price',
                                        ),
                                        array(
                                            'id' => 'currency',
                                            'type' => 'select', // Use 'select' type for Yes/No options
                                            'title' => 'Currency',
                                            'placeholder' => 'Select Currency',
                                            'chosen' => true,
                                            'options' => array(
                                                'SEK' => 'SEK',
                                                'EUR' => 'EUR',
                                            ),
                                        ),
                                    )
                                )
                            )
                        ),
                        array(
                            'title' => 'Download Options',
                            'fields' => array(
                                array(
                                    'id' => 'product_enable_download',
                                    'type' => 'switcher',
                                    'title' => esc_html__('Enable Datasheet Download', 'induxter'),
                                    'default' => true,
                                    'text_on' => esc_html__('Yes', 'induxter'),
                                    'text_off' => esc_html__('No', 'induxter'),
                                    'desc' => esc_html__('Enable or Disable Datasheet button', 'induxter'),
                                ),
                                array(
                                    'id' => 'product_download_file',
                                    'type' => 'media',
                                    'title' => esc_html__('Product Download File', 'induxter'),
                                    'dependency' => array('product_enable_download', '==', true),
                                    'desc' => esc_html__('Select product datasheet file to download', 'induxter'),
                                ),
                                array(
                                    'id' => 'product_enable_datasheet',
                                    'type' => 'switcher',
                                    'title' => esc_html__('Enable Instructions Download', 'induxter'),
                                    'default' => true,
                                    'text_on' => esc_html__('Yes', 'induxter'),
                                    'text_off' => esc_html__('No', 'induxter'),
                                    'desc' => esc_html__('Enable or Disable Instructions button', 'induxter'),
                                ),
                                array(
                                    'id' => 'product_datasheet_file',
                                    'type' => 'media',
                                    'title' => esc_html__('Instructions Download File', 'induxter'),
                                    'dependency' => array('product_enable_datasheet', '==', true),
                                    'desc' => esc_html__('Select product instructions file to download', 'induxter'),
                                ),
                                array(
                                    'id' => 'product_diagram_file',
                                    'type' => 'media',
                                    'title' => esc_html__('Diagram File', 'induxter'),
                                   
                                    'desc' => esc_html__('Select product diagram file', 'induxter'),
                                ),
                                array(
                                    'id' => 'product_step_file',
                                    'type' => 'media',
                                    'title' => esc_html__('Step File', 'induxter'),
                                   
                                    'desc' => esc_html__('Select product step file', 'induxter'),
                                ),
                                array(
                                    'id' => 'product_file_downloads',
                                    'type' => 'repeater',
                                    'title' => esc_html__('Additional File Downloads', 'induxter'),
                                    'fields' => array(
                                        array(
                                            'id' => 'name',
                                            'type' => 'text',
                                            'title' => esc_html__('File Name', 'induxter'),
                                        ),
                                        array(
                                            'id' => 'file',
                                            'type' => 'media',
                                            'title' => esc_html__('Select File', 'induxter'),
                                        ),
                                    ),
                                ),
                            )
                        ),
                    )
                )
            )
        )
    );

    // array(
    //     'title'  => '',
    //     'fields' => array(
    //         array(
    //             'type'     => 'callback',                    
    //             'function' => 'my_callback_function',
    //         )
    //     )
    // ),

    // array(
    //     'title'     => 'Special Price',
    //     'fields'    => array(
    //         array(
    //             'id'     => 'taw_customer_price',
    //             'type'   => 'repeater',
    //             'title'  => 'Customers',
    //             'fields' => array(                      
    //                 array(
    //                     'id'          => 'customer',
    //                     'type'        => 'select',
    //                     'title'       => 'Customer',                          
    //                     'placeholder' => 'Search customer',
    //                     'chosen'      => true,
    //                     'ajax'        => true,
    //                     'multiple'    => false,                           
    //                     'options' => 'searchCustomer',                                          
    //                 ),array(
    //                     'id'    => 'price',
    //                     'type'  => 'text',                       
    //                     'title' => 'Special Price',
    //                 ),
    //             )                 
    //         )
    //     )
    //   ),




    // A Callback function
    function my_callback_function()
    {
        global $post;
        $product = wc_get_product($post->ID);

        include_once(THINGSATWEB_DIR . '/template/blk-product-settings.php');
    }
}

function checkSpecialProduct($product_meta)
{
    $res = 1;

    if (is_numeric($product_meta)) {
        $product_meta = get_post_meta($product_meta, 'induxter_product_meta', true);
    }
    if (empty($product_meta)) {
        return $res;
    }

    if (array_key_exists('product_custom_article', $product_meta)) {
        if ($product_meta['product_custom_article'] == 1) {
            $res = -1;
            if (array_key_exists('product_custom_article_users', $product_meta)) {
                $users = $product_meta['product_custom_article_users'];
                if (!empty($users)) {
                    foreach ($users as $value) {
                        if ($value['product_custom_article_user'] == get_current_user_id()) {
                            $res = 1;
                            break;
                        }
                    }
                }
            }
        }
    }
    return $res;
}

//redirect to my-account page if other than admin user
add_action('admin_page_access_denied', function () {
    die(wp_redirect('my-account'));
});



function searchCustomer()
{
    global $wpdb;
    $data = array_merge((array) $_GET, (array) $_POST);
    $term = $data['term'];

    $q = "SELECT us.user_login,us.display_name FROM `tsm_users` as us where us.display_name like '%$term%'";

    $r = $wpdb->get_results($q);
    $opt = [];
    foreach ($r as $v) {
        $opt[$v->user_login . "::" . $v->display_name] = $v->user_login . "::" . $v->display_name;
    }

    return $opt;
}
function searchArticle()
{
    global $wpdb;
    $data = array_merge((array) $_GET, (array) $_POST);
    $term = $data['term'];

    $q = "SELECT meta_value FROM `tsm_postmeta` where meta_key='_sku' and meta_value like '%$term%'";

    $r = $wpdb->get_results($q);
    $option = [];
    foreach ($r as $v) {
        $option[$v->meta_value] = $v->meta_value;
    }

    return $option;
}
//add_action('shutdown', 'sql_logger');
function sql_logger()
{
    global $wpdb;
    $log_file = fopen(THINGSATWEB_DIR . '/sql_log.txt', 'a');
    fwrite($log_file, "//////////////////////////////////////////\n\n" . date("F j, Y, g:i:s a") . "\n");
    foreach ($wpdb->queries as $q) {
        fwrite($log_file, $q[0] . " - ($q[1] s)" . "\n\n");
    }
    fclose($log_file);
}

function product_price($price) {

    if(empty($price)) { return ""; }

    $source = $price;
    $currency = get_woocommerce_currency_symbol();
    $price = str_replace($currency,"",$price);
    $price = str_replace(",","",$price);
    $price = str_replace(".","",$price);
    $price = str_replace("0","",$price);
    if(preg_match('#[1-9]#', $price)) {
        return $source;
    }else {
        return "";
    }
}

function product_buy_or_quote($price) {

    $currency = get_woocommerce_currency_symbol();
    $price = str_replace($currency,"",$price);
    $price = str_replace(",","",$price);
    $price = str_replace(".","",$price);
    $price = str_replace("0","",$price);
    if(preg_match('#[1-9]#', $price)) {
        return "Buy";
    }else {
        return "Quote";
       // icl_register_string('default', 'Quote', 'Quote');
		//echo __('Quote','default');
     //   $r=__('Quote','default');
     //   return $r;
       // return __('Quote','default');
    }
}
add_action( 'woocommerce_email_order_details_table', 'remove_price_from_order_details_table', 10, 4 );
function remove_price_from_order_details_table( $order, $sent_to_admin, $plain_text, $email ) {
    // Remove the 'order-total' column from the data table
    unset( $order->data['order_total'] );
}

/*
add_filter( 'woocommerce_product_single_add_to_cart_text', 'woocommerce_custom_product_add_to_cart_text' ); 
// To change add to cart text on product archives(Collection) page
add_filter( 'woocommerce_product_add_to_cart_text', 'woocommerce_custom_product_add_to_cart_text' );  
function woocommerce_custom_product_add_to_cart_text($text) {
global $product;  
if($product->get_type()=="grouped"){
return $text;
}
if(is_user_logged_in()){
return __( 'Add To Request', 'woocommerce' );
}else{
return __( 'Add To Quote', 'woocommerce' );
}    
}
add_filter('woocommerce_order_button_text',function(){
if(is_user_logged_in()){
return __( 'Place Request', 'woocommerce' );
}else{
return __( 'Place Quote', 'woocommerce' );
}   
});
function product_abbreviation_texts()
{
// Double check user capabilities
if ( !current_user_can('manage_options') ) {
return;
}
include(get_theme_file_path('inc/product_abbreviation.php'));   
}
function add_my_custom_menus(){
add_submenu_page(
'options-general.php',      
__( 'Product  abbreviation', 'custom-uam' ),    
__( 'Manage Product abbreviation', 'custom-uam' ),
'manage_options', 
'product_abbreviation_texts',    
'product_abbreviation_texts');
}   
add_action( 'admin_menu', 'add_my_custom_menus');
function myCustomSideBar(){
echo "<h1>Sidebar</h1>";
}
function woocommerce_product_variation_tab() {
wc_get_template( 'single-product/tabs/variations.php' );
}
function woocommerce_product_accessories_tab() {
wc_get_template( 'single-product/tabs/accessories.php' );
}
function woocommerce_product_spare_parts_tab() {
wc_get_template( 'single-product/tabs/spare-parts.php' );
}
function woocommerce_product_download_files() {
wc_get_template( 'single-product/tabs/download-files.php' );
}
//add_action( 'woocommerce_sidebar', 'myCustomSideBar');
// Add the description (content) tab for a new product, so it can be edited with Elementor.
add_filter( 'woocommerce_product_tabs', function( $tabs ) {    
$tabs['variation'] = [
'title' => __( 'Variation', 'elementor' ),
'priority' => 1,
'callback' => 'woocommerce_product_variation_tab',
];
if(current_user_can( "c_uam_cap_accessories" )){
$tabs['accessories'] = [
'title' => __( 'Accessories', 'elementor' ),
'priority' => 20,
'callback' => 'woocommerce_product_accessories_tab',
];
}
if(current_user_can( "c_uam_cap_spare_parts" )){  
$tabs['spare-parts'] = [
'title' => __( 'Spare Parts', 'elementor' ),
'priority' => 21,
'callback' => 'woocommerce_product_spare_parts_tab',
];
}
if(current_user_can( "c_uam_cap_download_file" )){       
$tabs['download-files'] = [
'title' => __( 'Download Files', 'elementor' ),
'priority' => 21,
'callback' => 'woocommerce_product_download_files',
];
}
return $tabs;
} );
/*
add_action( 'woocommerce_product_options_general_product_data', 'product_options_general_product_data' );
function product_options_general_product_data() {
return;
global $post;
$data=get_post_meta( $post->ID);
//print_r($data);
woocommerce_wp_text_input(
array(
'id'        => '_b2b_regular_price',
'value'     => 0,
'label'     => __( 'B2B Regular price', 'woocommerce' ) . ' (' . get_woocommerce_currency_symbol() . ')',
'data_type' => 'price',
)
);
woocommerce_wp_text_input(
array(
'id'          => '_b2b_regular_price',
'value'       => 0,
'data_type'   => 'price',
'label'       => __( 'B2B Sale price', 'woocommerce' ) . ' (' . get_woocommerce_currency_symbol() . ')',
'description' => '',
)
);
echo "<hr/ style='border-top:none;border-bottom:solid 1px #eee;'>";
woocommerce_wp_text_input(
array(
'id'        => '_reseller_regular_price',
'value'     => 0,
'label'     => __( 'Reseller Regular price', 'woocommerce' ) . ' (' . get_woocommerce_currency_symbol() . ')',
'data_type' => 'price',
)
);
woocommerce_wp_text_input(
array(
'id'          => '_reseller_regular_price',
'value'       => 0,
'data_type'   => 'price',
'label'       => __( 'Reseller Sale price', 'woocommerce' ) . ' (' . get_woocommerce_currency_symbol() . ')',
'description' => '',
)
);
}
function display_spare_accessores_result(){ 
$id=isset($_POST[ 'id' ])?$_POST[ 'id' ]:'';
$type=isset($_POST[ 'type' ])?$_POST[ 'type' ]:'';
$terms = get_the_terms($id, 'product_cat' );
$cat=$terms[0]->slug;
if($type=="accessories"){
$type="tillbehor";
}else if($type=="spare-parts"){
$type="reservdelar";
}
$category=$type."-".$cat;
$args = array( 
'post_type'      => 'product', // product, not products
'post_status'    => 'publish', 
'tax_query' => array(array(
'taxonomy' => 'product_cat',
'field'    => 'slug',
'terms'     =>  $category,//'accessories-beam-mounted-pull-out-units', // When you have more term_id's seperate them by komma.
'operator'  => 'IN'         
))
// change this based on your needs
);
$ajaxposts = new WP_Query( $args );
if ( $ajaxposts->posts ){ 
echo "<ul class='my_custom_tab_products products columns-3'>";
while ( $ajaxposts->have_posts() ) { 
$ajaxposts->the_post(); 
wc_get_template_part( 'content', 'product' ); // use WooCommerce function to get html
} 
echo "</ul>";
} else { 
echo "No data found";
}
exit;
}
add_action( "wp_ajax_display_spare_accessores_result",'display_spare_accessores_result');
function product_download_options(){
wc_get_template( 'single-product/download.php' );
}
add_action( 'woocommerce_single_product_summary', 'product_download_options', 50 );
/****** overide woocomerce wc-template-funtions ********
function woocommerce_maybe_show_product_subcategories( $loop_html = '' ) {
if ( wc_get_loop_prop( 'is_shortcode' ) && ! WC_Template_Loader::in_content_filter() ) {
return $loop_html;
}
$display_type = woocommerce_get_loop_display_mode();       
if(is_product_category()){
global $wp;
if(count(explode("/",$wp->request))>2){
$display_type="products";
}                    
}
// If displaying categories, append to the loop.
if ( 'subcategories' === $display_type || 'both' === $display_type ) {
ob_start();
woocommerce_output_product_categories(
array(
'parent_id' => is_product_category() ? get_queried_object_id() : 0,
)
);
$loop_html .= ob_get_clean();
if ( 'subcategories' === $display_type || 1) {
wc_set_loop_prop( 'total', 0 );
// This removes pagination and products from display for themes not using wc_get_loop_prop in their product loops.  @todo Remove in future major version.
global $wp_query;
if ( $wp_query->is_main_query() ) {
$wp_query->post_count    = 0;
$wp_query->max_num_pages = 0;
}
}
}
return $loop_html;
}
function exclude_product_cat_children($wp_query) {
if (is_product_category() && isset ( $wp_query->query_vars['product_cat'] ) && $wp_query->is_main_query()) {
$tax_query=$wp_query->get("tax_query");
$tax_query=is_array($tax_query)?$tax_query:array($tax_query);
$tax_query[]= array ( 'taxonomy' => 'product_cat',
'field' => 'slug',
'terms' => $wp_query->query_vars['product_cat'],
'include_children' => false
);
$wp_query->set('tax_query', $tax_query);        
}
}
add_filter('pre_get_posts', 'exclude_product_cat_children');
function dynamic_select_role_values ( $scanned_tag, $replace ) {  
if ( $scanned_tag['name'] != 'f-role' )  
return $scanned_tag;
$roles_obj = new WP_Roles();
$roles_names_array = $roles_obj->get_names(); 
foreach ($roles_names_array as $key=>$role_name) {      
if(false !==strrpos($key,'custom_uam')){
$scanned_tag['raw_values'][] = $role_name;            
}
}
$pipes = new WPCF7_Pipes($scanned_tag['raw_values']);
$scanned_tag['values'] = $pipes->collect_befores();
$scanned_tag['pipes'] = $pipes;
return $scanned_tag;  
}  
add_filter( 'wpcf7_form_tag', 'dynamic_select_role_values', 10, 2);
add_filter( 'woocommerce_grouped_price_html',function($price,$product,$child){
return "";
},10,3);
function custom_uam_save_abbreviation(){
$abbreviation_name=isset($_POST[ 'abbreviation_name' ])?$_POST[ 'abbreviation_name' ]:'';
$abbreviation_meaning=isset($_POST[ 'abbreviation_meaning' ])?$_POST[ 'abbreviation_meaning' ]:'';
$abbreviation_key=isset($_POST[ 'abbreviation_key' ])?$_POST[ 'abbreviation_key' ]:'';
$abbreviation_req_type=isset($_POST[ 'abbreviation_req_type' ])?$_POST[ 'abbreviation_req_type' ]:'';
if(empty($abbreviation_name)||empty($abbreviation_meaning)){
return -1;
}
$data=get_option('custom_uam_abbreviation',"");
$data=json_decode( $data, true);
if($abbreviation_req_type=="add"){
$data[$abbreviation_name]=$abbreviation_meaning;
}else if($abbreviation_req_type=="edit"){
unset($data[$abbreviation_key]);
$data[$abbreviation_name]=$abbreviation_meaning;       
}else if($abbreviation_req_type=="delete"){
unset($data[$abbreviation_key]);
}
update_option('custom_uam_abbreviation',json_encode($data));
}
//remove tabs in product edit page
function remove_tab($tabs){
unset($tabs['general']); // it is to remove general tab
//unset($tabs['inventory']); // it is to remove inventory tab
//unset($tabs['advanced']); // it is to remove advanced tab
//unset($tabs['linked_product']); // it is to remove linked_product tab
//unset($tabs['attribute']); // it is to remove attribute tab
//unset($tabs['variations']); // it is to remove variations tab
return($tabs);
}
add_filter('woocommerce_product_data_tabs', 'remove_tab', 10, 1);
/******* API for visma *******
function register_my_testroutes() {
register_rest_route( 'sm/v1', '/clearFilterCache', array(
// Notice how we are registering multiple endpoints the 'schema' equates to an OPTIONS request.
array(
'methods'  => 'GET',
'callback' => 'apiClearFilterCache',           
),        
) );   
}
function apiClearFilterCache(){
global $wpdb;
/* $query="SELECT meta_id,meta_value,post_id FROM `wp_postmeta` WHERE meta_key='_product_attributes' and meta_value like '%utdrag%'";
$data=$wpdb->get_results($query);
foreach($data as $d){
$attr=unserialize($d->meta_value);  
foreach($attr as $key=>$v){
if($key=="pa_utdrag"){
unset($attr['pa_utdrag']);
update_post_meta($d->post_id, '_product_attributes', $attr);
echo "updated postid :  $d->post_id \n";                
break;
}
}       
}
//delete option
$query="SELECT * FROM `wp_options` WHERE option_name like '%tsm_filter_%'";
$data=$wpdb->get_results($query);
foreach($data as $d){
echo "deleted ".$d->option_name." \n";
delete_option( $d->option_name);
}
echo "removed all cached option";
//return rest_ensure_response( $data );
}
add_action('rest_api_init', 'register_my_testroutes');
function get_custom_attribute_fitler_for_category(){
$category=isset($_POST[ 'category' ])?$_POST[ 'category' ]:'';
$category=isset($_POST[ 'nonce' ])?$_POST[ 'category' ]:'';
if(empty($category)){
return "No filter found";
}
$nonce=isset($_POST[ 'nonce' ])?$_POST[ 'nonce' ]:"";
if ( !wp_verify_nonce($nonce, "my_prod_filter")) {
exit("No naughty business please");
}
$key_cat="tsm_filter_".$category;
$res2=get_option($key_cat);
if(!$res2){
global $wpdb;
$args = array( 
'post_type'      => 'product', // product, not products
'post_status'    => 'publish',
'posts_per_page' => -1,
'fields' => 'ids',
'tax_query' => array(array(
'include_children' => false,
'taxonomy' => 'product_cat',
'field'    => 'slug',
'terms'     =>  $category,//'accessories-beam-mounted-pull-out-units', // When you have more term_id's seperate them by komma.
'operator'  => 'IN'         
))
// change this based on your needs
);
$ajaxposts = new Wp_query($args);
$post_ids=implode(",", $ajaxposts->posts);    
$query="SELECT meta_value,post_id FROM `wp_postmeta` WHERE meta_key='_product_attributes' and post_id in($post_ids)";
$data=$wpdb->get_results($query);
$res=[];
foreach ($data as  $value) {
$data2=unserialize($value->meta_value);
foreach ($data2 as $key => $v2) {
if($v2["is_taxonomy"]==1){               
$v=wc_get_product_terms($value->post_id,$key,array( 'fields' => 'names' ));
foreach($v as $vv) {
$res[$key][$vv]=$vv;
}               
}          
}
}
$res2=[];
foreach ($res as $key => $value) {      
$label=wc_attribute_label($key);
if(empty($label)){
$label=str_replace("pa_", "", $key);
} 
$res2[$key]=array("name"=>$label,"val"=>$value);     
}
update_option($key_cat,$res2);
}
wc_get_template( 'inc/product-filter.php',array('data' =>$res2));
exit;
}
add_action( 'wp_ajax_nopriv_get_custom_attribute_fitler_for_category', 'get_custom_attribute_fitler_for_category' );
add_action( 'wp_ajax_get_custom_attribute_fitler_for_category', 'get_custom_attribute_fitler_for_category' );
function get_products_by_fitler(){
$filters=isset($_POST[ 'filters' ])?$_POST[ 'filters' ]:[];
$category=isset($_POST[ 'category' ])?$_POST[ 'category' ]:"";  
$page=isset($_POST[ 'page' ])?$_POST[ 'page' ]:1;  
$nonce=isset($_POST[ 'nonce' ])?$_POST[ 'nonce' ]:"";
if ( !wp_verify_nonce($nonce, "my_prod_filter")) {
exit("No naughty business please");
}
$attr=array('relation' => 'AND',""=>array(
'include_children' => false,
'taxonomy' => 'product_cat',
'field'    => 'slug',
'terms'     =>  array($category),//'accessories-beam-mounted-pull-out-units', // When you have more term_id's seperate them by komma.
'operator'  => 'IN'         
));
foreach ($filters as $value) {
$ar=explode("::", $value);
$key=$ar[0];
$val=$ar[1];
if(isset($attr[$key])){
$attr[$key]['terms'][]=$val;
}else{
$attr[$key]=array(
'taxonomy'        => $key,
'field'           => 'slug',
'terms'           =>  array($val),
'operator'        => 'IN',
);
}
}   
//$attr=array();
$product = new WP_Query(
array(
// 'fields' => 'ids', 
'post_type'      => 'product',
'post_status'    => 'publish',
'posts_per_page' => 15,
'paged'=>$page,  
'tax_query'      => $attr
)
);
if ( $product->posts ){       
if($page==1){
do_action( 'woocommerce_before_shop_loop' );            
woocommerce_product_loop_start();
}       
while ( $product->have_posts() ) {
$product->the_post();
do_action( 'woocommerce_shop_loop' );
wc_get_template_part( 'content', 'product' ); // use WooCommerce function to get html
}
if($page==1){
woocommerce_product_loop_end();
do_action( 'woocommerce_after_shop_loop' );
echo '<div style="width:100%;text-align:center;" id="btn_load_more_prod" data-total="'.$product->found_posts.'"><button class="button"  onclick="getFilteredProduct(\''.$category.'\')">Load More</button></div>';
}
} else { 
echo "No data found";
}
wp_reset_postdata();   
exit;
}
add_action( 'wp_ajax_nopriv_get_products_by_fitler', 'get_products_by_fitler' );
add_action( 'wp_ajax_get_products_by_fitler', 'get_products_by_fitler' );
function validateTestPermission(){
return true;
}
function register_my_routes_yost() {
register_rest_route( 'sm/v1', '/updateYoast', array(
// Notice how we are registering multiple endpoints the 'schema' equates to an OPTIONS request.
array(
'methods'  => 'POST',
'callback' => 'add_to_yoast_seo',
'permission_callback' => 'validateTestPermission'
),
) );
register_rest_route( 'sm/v1', '/updateSeo', array(
// Notice how we are registering multiple endpoints the 'schema' equates to an OPTIONS request.
array(
'methods'  => 'POST',
'callback' => 'add_to_seo',
'permission_callback' => 'validateTestPermission'
),
) );
register_rest_route( 'sm/v1', '/get_attachment_by_name', array(
// Notice how we are registering multiple endpoints the 'schema' equates to an OPTIONS request.
array(
'methods'  => 'GET',
'callback' => 'get_attachment_by_name',
'permission_callback' => 'validateTestPermission'
),
) );
register_rest_route( 'sm/v1', '/api_insert_user', array(
// Notice how we are registering multiple endpoints the 'schema' equates to an OPTIONS request.
array(
'methods'  => 'GET',
'callback' => 'api_insert_user',
'permission_callback' => 'validateTestPermission'
),
) );
}
function get_attachment_by_name( $arg ) {
$slug=$arg->get_param("title");
$args = array(
'post_type' => 'attachment',
'name' => $slug,
'posts_per_page' => 1,
'post_status' => 'inherit',
);
$_header = get_posts( $args );
$header = $_header ? array_pop($_header) : null;
return $header;
}
function add_to_seo( $req){
$post_id=$req->get_param( 'post_id' ); 
$metatitle=$req->get_param( 'metatitle' ); 
$metadesc=$req->get_param( 'metadesc' ); 
$metakeywords=$req->get_param( 'metakeywords' );    
$ret = false;
$data=array();
$data["product_seo_title"]=$metatitle;
$data["product_seo_descriptions"]=$metadesc;
$data["product_seo_keywords"]=$metakeywords;
// Include plugin library to check if Yoast Seo is presently active
//include_once( ABSPATH.'panel/includes/plugin.php' );
//if(is_plugin_active(ABSPATH.'wp-content/plugins/wordpress-seo/wp-seo.php')) {
//plugin is activated
$updated_title = update_post_meta($post_id, 'induxter_product_meta_tags', $data);   
return $updated_title;
}
function add_to_yoast_seo( $req){
$post_id=$req->get_param( 'post_id' ); 
$metatitle=$req->get_param( 'metatitle' ); 
$metadesc=$req->get_param( 'metadesc' ); 
$metakeywords=$req->get_param( 'metakeywords' );    
$ret = false;
// Include plugin library to check if Yoast Seo is presently active
//include_once( ABSPATH.'panel/includes/plugin.php' );
//if(is_plugin_active(ABSPATH.'wp-content/plugins/wordpress-seo/wp-seo.php')) {
//plugin is activated
$updated_title = update_post_meta($post_id, '_yoast_wpseo_title', $metatitle);
$updated_desc = update_post_meta($post_id, '_yoast_wpseo_metadesc', $metadesc);
$updated_kw = update_post_meta($post_id, '_yoast_wpseo_metakeywords', $metakeywords);
// $updated_kw = update_post_meta($post_id, '_yoast_wpseo_metakeywords', $metakeywords);
if($updated_title && $updated_desc && $updated_kw){
$ret = true;
}
// }else{
//  $ret="plugin not exist";
//}
return $ret;
}
function api_insert_user($req){    
$user_email=$req->get_param( 'user_email' ); 
$first_name=$req->get_param( 'first_name' ); 
$last_name=$req->get_param( 'last_name' ); 
$metakeywords=$req->get_param( 'metakeywords' );
$user_pass="sample123";
$role="custom_uam_B2B Customer";
$user_data = array(
'user_login' => $user_email,
'user_pass' => $user_pass,
'user_email' => $user_email,
'first_name' =>  $first_name,
'last_name' => $last_name,
'display_name' => $first_name." ".$last_name,
'role' => $role);
$id=wp_insert_user($user_data);
return $id;
}
add_action( 'rest_api_init', 'register_my_routes_yost' );
function checkSpecialProduct($product_meta){
$res=1;
if(is_numeric($product_meta)){
$product_meta=get_post_meta($product_meta, 'induxter_product_meta', true );
}
if(empty($product_meta)){
return $res;
}
if ( array_key_exists( 'product_custom_article', $product_meta ) ) {
if($product_meta['product_custom_article']==1){
$res=-1;
if ( array_key_exists( 'product_custom_article_users', $product_meta ) ) {
$users=$product_meta['product_custom_article_users'];
if(!empty($users)){        
foreach ($users as $value) {
if($value['product_custom_article_user']==get_current_user_id()){         
$res=1;
break;
}
}
}        
}
}
}
return $res;
}
function custom_woocommerce_product_type_output($output){
$output='<select class="wc-category-search" name="product_cat" data-placeholder="Filter by category" data-allow_clear="true">
</select>
<select name="product_type" id="dropdown_product_type"><option value="">Filter by product type</option><option value="simple" >Simple product</option><option value="grouped" >Grouped product</option></select><select name="stock_status"><option value="">Filter by stock status</option><option  value="instock">In stock</option><option  value="outofstock">Out of stock</option><option  value="onbackorder">On backorder</option></select>';
return $output;
}
add_filter('woocommerce_product_filters','custom_woocommerce_product_type_output');
function get_parent_grouped_id($children_id){
global $wpdb;
$results = $wpdb->get_col("SELECT post_id FROM {$wpdb->prefix}postmeta
WHERE meta_key = '_children' AND meta_value LIKE '%$children_id%'");
error_log("SELECT post_id FROM {$wpdb->prefix}postmeta
WHERE meta_key = '_children' AND meta_value LIKE '%$children_id%'");
// Will only return one product Id or false if there is zero or many
return sizeof($results) > 0 ? $results[0] : false;
}
function getAbbreviationText($title,$data_abbreviation){
$ar=explode(" ",$title);
$txt="";        
//print_r($data_abbreviation);
foreach ($ar as $v) {
if(isset($data_abbreviation[$v])){              
$txt.=" <span data-toggle='tooltip' class='product-title-abbreviation' title='".$data_abbreviation[$v]."'>".$v."</span>";
}else{
$txt.=" ".$v;
}
}
return $txt;
}
/*
function remove_core_updates(){
global $wp_version;
return(object) array('last_checked'=> time(),'version_checked'=> $wp_version,);
}
add_filter('pre_site_transient_update_core','remove_core_updates'); //hide updates for WordPress itself
add_filter('pre_site_transient_update_plugins','remove_core_updates'); //hide updates for all plugins
add_filter('pre_site_transient_update_themes','remove_core_updates');
function theme_header_custom_metadata() {
global $post,$product;
if ( is_page() || is_singular( 'post' )||induxter_custom_post_types()) {
$post_id=$post->ID;
if(!empty($product)){
$children=$product->get_children();
if(count($children)>0){
$post_id=$children[0];
}
$common_meta = get_post_meta( $post_id, 'induxter_product_meta_tags', true );
if(!empty($common_meta)){
$product_seo_title=isset($common_meta["product_seo_title"])?$common_meta["product_seo_title"]:'';
$product_seo_keywords=isset($common_meta["product_seo_keywords"])?$common_meta["product_seo_keywords"]:'';
$product_seo_descriptions=isset($common_meta["product_seo_descriptions"])?$common_meta["product_seo_descriptions"]:'';
if(!empty($product_seo_title)){
echo "<meta name='title' content='".$product_seo_title."' >";
}
if(!empty($product_seo_keywords)){
echo "<meta name='keywords' content='".$product_seo_keywords."' >";
}
if(!empty($product_seo_descriptions)){
echo "<meta name='description' content='".$product_seo_descriptions."' >";
}
}
}
} 
}
add_action( 'wp_head', 'theme_header_custom_metadata' );
add_action('wp_dashboard_setup', 'my_custom_dashboard_widgets');
function my_custom_dashboard_widgets() {
global $wp_meta_boxes;
wp_add_dashboard_widget('custom_help_widget', 'Welcome to Smart Storing', 'custom_dashboard_help');
}
function custom_dashboard_help() {
echo '<p>Customized Solutions</p>';
}
add_action( 'login_enqueue_scripts', 'my_login_logo_one' );
function my_login_logo_one() { 
?> 
<style type="text/css"> 
body.login div#login h1 a {
background-image: url(/wp-content/uploads/2020/11/cropped-smartstoring-favicon-1.png);  
padding-bottom: 30px;  
} //Add your own logo image in this url 
</style>
<?php 
}
add_filter( 'login_headerurl', 'custom_loginlogo_url');
function custom_loginlogo_url($url) {
return "https://www.smartstoring.se";
}
add_action('admin_head', 'admin_only_warnings');
function admin_only_warnings() {
if(is_admin() && !current_user_can('administrator') ) {
echo '<style>
<!-- add your classes/ids below -->
.warning, .error, .updated {display:none !important;}
} 
</style>';
}
}
add_filter( 'contextual_help', 'mytheme_remove_help_tabs', 999, 3 );
function mytheme_remove_help_tabs($old_help, $screen_id, $screen){
if(is_admin() && !current_user_can('administrator') ) {
$screen->remove_help_tabs();
return $old_help;
}
}
add_action('admin_menu', 'webronic_remove_menus', PHP_INT_MAX);  
function webronic_remove_menus(){  
//         global $menu;
//      print_r($menu);
if(is_admin() && !current_user_can('administrator') ) {    
remove_menu_page('edit.php?post_type=induxter_service');  // Services
remove_menu_page('admin.php?page=induxter');  // Induxter
remove_menu_page('edit.php?post_type=induxter_team');  // Team members
remove_menu_page('edit.php?post_type=induxter_project');  // Projects
remove_menu_page('edit.php?post_type=elementor_library'); // Templates
remove_menu_page('ai1wm_export');  // All in one migration
//          remove_menu_page('themes.php');    // Appearance
//          remove_menu_page('users.php');   // Users
remove_menu_page('tools.php');  // Tools
remove_menu_page('wpcf7');  // Posts
remove_menu_page('wc-admin&path=/analytics/overview'); // Analytics
remove_menu_page('woocommerce-marketing'); // Marketing
remove_menu_page('sitepress-multilingual-cms/menu/languages.php');
remove_menu_page('wpml-translation-management/menu/translations-queue.php');
}
}
// define the woocommerce_product_subcategories_args callback 
function filter_woocommerce_product_subcategories_args( $array ) { 
// make filter magic happen here... 
if(empty($array['parent'])){       
$array['exclude']=[122,1979];  //id for 'lager-industri','lager-industri-en'      
}
return $array; 
}; 
// add the filter 
add_filter( 'woocommerce_product_subcategories_args', 'filter_woocommerce_product_subcategories_args', 10, 1 );
*/ 

function my_custom_mime_types($mimes ) {
    $mimes ['stp'] = 'application/STEP'; // adding STEP extension
    return $mimes ;
}
add_filter('upload_mimes', 'my_custom_mime_types');
