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

 // for search in description also 
//  add_filter('dgwt/wcas/search_query/args', 'customize_search_args', 10, 1);

//  function customize_search_args($args) {
//      // Remove the support filter argument
//      unset($args['suppress_filters']);
 
//      // You can also make other modifications to $args if needed
 
//      return $args;
//  }

add_filter( 'rank_math/sitemap/enable_caching', '__return_false');

//Translation

function google_translate_language_selector_shortcode() {
    ob_start();
    ?>
    <div id="google_translate_element" class="hihello"></div>

    <script type="text/javascript">
    // Global flag to prevent duplicate initialization
    window.googleTranslateInitialized = false;
    
    function googleTranslateElementInit1() {
        // Check if already initialized
        if (window.googleTranslateInitialized) {
            console.log('Google Translate already initialized, skipping...');
            return;
        }
        
        const urlParams = new URLSearchParams(window.location.search);

        if (urlParams.has('xdomain_data')) {
            // Enhanced cookie deletion function
            function deleteAllCookies(name) {
                const hostParts = location.hostname.split('.');
                const domains = [
                    location.hostname,
                    '.' + location.hostname,
                    hostParts.length > 2 ? '.' + hostParts.slice(1).join('.') : null
                ].filter(Boolean);
                
                const paths = ['/', '/path1', '/path2']; // Add any specific paths used
                
                domains.forEach(domain => {
                    paths.forEach(path => {
                        document.cookie = `${name}=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=${path}; domain=${domain};`;
                    });
                    // Also try without path
                    document.cookie = `${name}=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/; domain=${domain};`;
                });
                
                // Delete without domain specification
                paths.forEach(path => {
                    document.cookie = `${name}=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=${path};`;
                });
                document.cookie = `${name}=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;`;
            }

            deleteAllCookies('googtrans');
            console.log('xdomain_data detected - all googtrans cookies cleared');
        }

        // Mark as initialized before creating the element
        window.googleTranslateInitialized = true;
        
        new google.translate.TranslateElement({
            pageLanguage: 'en',
            includedLanguages: 'de,fr,es,it,pl,fi,et,hu,nl,pt',
            layout: google.translate.TranslateElement.InlineLayout.VERTICAL,
            autoDisplay: false // Prevent automatic language display
        }, 'google_translate_element');
    }
    
    // Ensure the script is only loaded once
    if (!document.querySelector('script[src*="translate.google.com"]')) {
        const script = document.createElement('script');
        script.src = '//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit1';
        script.async = true;
        document.head.appendChild(script);
    }
    </script>

    <style>
        #google_translate_element #language-selector {
            display: block !important;
        }
        /* Hide duplicate elements that might appear */
        .goog-te-banner-frame, .goog-te-menu-frame {
            display: none !important;
        }
    </style>

    <?php
    return ob_get_clean();
}
add_shortcode('google_translate_menu', 'google_translate_language_selector_shortcode');



add_filter('wp_nav_menu_items', function($items, $args) {
    // Check if this is the menu with ID 17
    if (isset($args->menu) && $args->menu->term_id == 17) {
        // Append your shortcode output as a new <li> item at the end
        $translate_selector = do_shortcode('[google_translate_menu]');
        
        // Wrap the output inside a menu item <li> with a unique class for styling
        $items .= '<li class="menu-item menu-item-google-translate">' . $translate_selector . '</li>';
    }
    return $items;
}, 10, 2);





//Ends Translation








add_filter( 'woocommerce_order_formatted_billing_address', 'customize_order_billing_address', 10, 2 );
function customize_order_billing_address( $address, $order ) {
    // Ensure the country is included in the address format
    $address['country'] = WC()->countries->countries[ $order->get_billing_country() ];
    return $address;
}


add_filter('w3tc_pgcache_flush_post', '__return_false'); // Prevent post-based cache flush
add_filter('w3tc_pgcache_flush', '__return_false'); // Prevent full cache flush

add_filter( 'woocommerce_add_to_cart_redirect', 'wp_get_referer', 100 );
function my_scripts_method()
{
    wp_enqueue_script(
        'custom-script',
        get_stylesheet_directory_uri() . '/js/custom_script.js',
        array('jquery')
    );
}
//Image Big size allowed
add_filter( 'big_image_size_threshold', '__return_false' );
//Image Big size allowed end
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
                            'title' => 'Spare Parts',
                            'fields' => array(
                                array(
                                    'id' => 'SparepartsArticle',
                                    'type' => 'repeater',
                                    'title' => 'Product SpartParts',
                                    'fields' => array(
                                        array(
                                            'id' => 'spare_article',
                                            'type' => 'select',
                                            'title' => 'Art No',
                                            'chosen' => true,
                                            'ajax' => true,
                                            'multiple' => false,
                                            'options' => 'searchArticle',
                                        ),
                                        array(
                                            'id' => 'min_qty',
                                            'type' => 'number',
                                            'placeholder' => '0',
                                            'title' => 'Min Qty',
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
                                    'id' => 'product_datasheetpage',
                                    'type' => 'switcher',
                                    'title' => esc_html__('Choose Datasheet Page', 'induxter'),
                                    'default' => true,
                                    'text_on' => esc_html__('2 Pg', 'induxter'),
                                    'text_off' => esc_html__('1 Pg', 'induxter'),
                                    'desc' => esc_html__('Choose 1 Page or 2 Page Datasheet', 'induxter'),
                                ),
                                 array(
                                    'id' => 'product_enable_dual_images_in_datasheet',
                                    'type' => 'switcher',
                                    'title' => esc_html__('Show Dual Images in Datasheet', 'induxter'),
                                    'default' => false,
                                    'text_on' => esc_html__('Yes', 'induxter'),
                                    'text_off' => esc_html__('No', 'induxter'),
                                    'desc' => esc_html__('By enable this while download datasheet it will show 2 images', 'induxter'),
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
                                    'title' => esc_html__('Diagram File 1', 'induxter'),
                                   
                                    'desc' => esc_html__('Select product diagram file 1', 'induxter'),
                                ),
                                array(
                                    'id' => 'product_diagram_file2',
                                    'type' => 'media',
                                    'title' => esc_html__('Diagram File 2', 'induxter'),
                                   
                                    'desc' => esc_html__('Select product diagram file 2', 'induxter'),
                                ),
                                array(
                                    'id' => 'product_diagram_file3',
                                    'type' => 'media',
                                    'title' => esc_html__('Diagram File 3', 'induxter'),
                                   
                                    'desc' => esc_html__('Select product diagram file 3', 'induxter'),
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
$lang=getSiteCurrentLang();

    $currency = get_woocommerce_currency_symbol();
    $price = str_replace($currency,"",$price);
    $price = str_replace(",","",$price);
    $price = str_replace(".","",$price);
    $price = str_replace("0","",$price);
if($lang=='en'){
    if(preg_match('#[1-9]#', $price)) {
        return "Buy";
    }else {
        return "Quote";
       // icl_register_string('default', 'Quote', 'Quote');
		//echo __('Quote','default');
     //   $r=__('Quote','default');
     //   return $r;
       // return __('Quote','default');
}}elseif($lang=='sv'){
        if(preg_match('#[1-9]#', $price)) {
            return "Köp";
        }else {
            return "Offert";
        }

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
function custom_user_meta_update($user_id) {
$current_user = wp_get_current_user();
        $user_roles = $current_user->roles;

 if (class_exists('WooCommerce')&& $_POST['save_account_details']) {
    if (current_user_can('edit_user', $user_id) && (in_array('custom_uam_reseller_eur', $user_roles) || in_array("custom_uam_reseller_sek", $user_roles))) {
        $account_company_name = empty($_POST['account_company_name']) ? '' : sanitize_text_field($_POST['account_company_name']);
        $account_company_website = empty($_POST['account_company_website']) ? '' : sanitize_text_field($_POST['account_company_website']);
        $account_company_theme = empty($_POST['account_company_theme']) ? '#00FFFFFF' : sanitize_text_field($_POST['account_company_theme']);
        $account_company_logo_trash = empty($_POST['account_company_logo_trash']) ? '' : sanitize_text_field($_POST['account_company_logo_trash']);

        // Check if the file was uploaded without errors
        if (isset($_FILES['account_company_logo']) && $_FILES['account_company_logo']['error'] == 0) {
            $upload_dir = wp_upload_dir(); // Get the WordPress uploads directory
            $target_dir = $upload_dir['path'];
            $target_file = $target_dir . '/' . basename($_FILES['account_company_logo']['name']);


            // Move the uploaded file to the target directory
            if (isset($_FILES['account_company_logo']) && move_uploaded_file($_FILES['account_company_logo']['tmp_name'], $target_file)) {
                // File uploaded successfully, now store information in the database
                $logo_url = $upload_dir['url'] . '/' . basename($_FILES['account_company_logo']['name']);
                update_user_meta($user_id, 'account_company_logo', $logo_url);

            }
        }
        if($account_company_logo_trash !== "1"){
            update_user_meta($user_id, 'account_company_logo', "");

                }
        
        $required_fieldsa = [];
        if((in_array('custom_uam_reseller_eur', $user_roles) || in_array("custom_uam_reseller_sek", $user_roles)) ){
            $required_fieldsa['account_company_name'] = __( 'Company name', 'woocommerce' );
            $required_fieldsa['account_company_website'] = __( 'Company website', 'woocommerce' );
                        
        }

        foreach ( $required_fieldsa as $field_key => $field_name ) {
            if ( empty( $_POST[ $field_key ] ) ) {
                /* translators: %s: Field name. */
                wc_add_notice( sprintf( __( '%s is a required field.', 'woocommerce' ), '<strong>' . esc_html( $field_name ) . '</strong>' ), 'error', array( 'id' => $field_key ) );


            }
                      
        }
        if ( wc_notice_count( 'error' ) !== 0 ) {

                wp_safe_redirect( wc_get_endpoint_url( 'edit-account', '', wc_get_page_permalink( 'myaccount' ) ) );
                exit;
                    
}
        if ( $account_company_theme === "#FFFFFF" || $account_company_theme === "#ffffff" ) {
            $account_company_theme = "";
                }

        update_user_meta($user_id, 'account_company_name', $account_company_name);
        update_user_meta($user_id, 'account_company_website', $account_company_website);
        update_user_meta($user_id, 'account_company_theme', $account_company_theme);

    }
}
}

add_action('profile_update', 'custom_user_meta_update');

function validate_user_password_callback() {
    $response = array();

    // Get the entered password from the AJAX request
    $entered_password = isset($_POST['entered_password']) ? sanitize_text_field($_POST['entered_password']) : '';

    // Get the current user ID
    $user_id = get_current_user_id();

    // Get the hashed password from the database
    global $wpdb;
    $correct_password_hash = $wpdb->get_var($wpdb->prepare("SELECT user_pass FROM {$wpdb->users} WHERE ID = %d", $user_id));

    // Check if the entered password matches the correct hashed password
    $password_match = wp_check_password($entered_password, $correct_password_hash);

    if ($password_match) {
        $response['error'] = false;
        $response['message'] = 'Password is correct.';
    } else {
        $response['error'] = true;
        $response['message'] = 'Incorrect password. Please try again.';
    }

    // Send JSON response back to the JavaScript
    wp_send_json($response);
}

// Hook to handle the AJAX action
add_action('wp_ajax_validate_user_password', 'validate_user_password_callback');
add_action('wp_ajax_nopriv_validate_user_password', 'validate_user_password_callback');

function enqueue_custom_script_for_admin_page() {
    // Get the current screen object
    $current_screen = get_current_screen();

    // Check if the current screen is the desired admin page
    if ($current_screen && $current_screen->id === 'product') {
        // Enqueue your script
        wp_enqueue_script(
            'custom-admin-script',
            get_stylesheet_directory_uri() . '/js/custom-admin-script.js',
            array('jquery')
        );
    }
}
add_action('admin_enqueue_scripts', 'enqueue_custom_script_for_admin_page');

function custom_formatted_woocommerce_price($price_html, $product) {

    
    // Check user roles
    $current_user = wp_get_current_user();
    $user_roles = $current_user->roles;
    $negative = $product->get_price() < 0;

    // Define the default currency symbol
    $default_currency_symbol = ' kr';




    // Check if the user has the 'custom_uam_reseller_eur' role
    if (in_array('custom_uam_reseller_eur', $user_roles) || in_array('custom_uam_sale_eur', $user_roles)) {
        // If a custom_uam_reseller_eur, set the currency symbol to 'EUR'
        $currency_symbol = ' EUR';
    } else {
        $currency_symbol = $default_currency_symbol;
    }

    // Format the price using the chosen currency symbol
    $price_html = ($negative ? '-' : '') . sprintf('<span class="woocommerce-Price-currencySymbol">%s</span>%s',$product->get_price(), $currency_symbol );

    return $price_html;
}

function custom_change_currency_symbol($currency_symbol, $currency) {
    $current_user = wp_get_current_user();
    $user_roles = $current_user->roles;

    // Define the default currency symbol
    $default_currency_symbol = ' kr';

    // Check if the user has the 'custom_uam_reseller_eur' role
    if (in_array('custom_uam_reseller_eur', $user_roles) || in_array('custom_uam_sale_eur', $user_roles)) {
        // If a custom_uam_reseller_eur, set the currency symbol to 'EUR'
        $currency_symbol = ' EUR';
    } else {
        $currency_symbol = $default_currency_symbol;
    }

    return $currency_symbol;
}

// Hook the function to the filter
add_filter('woocommerce_currency_symbol', 'custom_change_currency_symbol', 10, 2);

// Add AJAX action for getting article title
add_action('wp_ajax_get_article_title', 'get_article_title_callback');

function get_article_title_callback() {
    // Get the art_no from the AJAX request
    $art_no = isset($_POST['art_no']) ? sanitize_text_field($_POST['art_no']) : '';

    // Perform the database query to get the title based on art_no
    $title = get_title_from_database($art_no);

    // Return the title as the AJAX response
    echo $title;die();
}

// Add your database query function to get the title based on art_no
function get_title_from_database($art_no ) {
    global $wpdb; // Globalize $wpdb
    $current_language = get_locale();

// Extract the two-character language code
$lang = substr($current_language, 0, 2);


    $title_art = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT `title` FROM `taw_article_title` WHERE `art_no` = %s AND `lang` = %s",
            $art_no,
            $lang
        )
    );


    return $title_art;
}




function get_restricted_art_nos($userid, $user_role) {

    global $wpdb;

    // Fetch the customerno for the current user
    $customerno_query = $wpdb->prepare("SELECT meta_value FROM tsm_usermeta WHERE user_id = %d AND meta_key LIKE %s", $userid, '%customer_no%');
    $customerno = $wpdb->get_var($customerno_query);
    
    if ($customerno) {
        // Fetch all user IDs with the same customerno
        $user_ids_query = $wpdb->prepare("SELECT user_id FROM tsm_usermeta WHERE meta_value = %s", $customerno);
        $related_user_ids = $wpdb->get_col($user_ids_query);
    } else {
        $related_user_ids = [$userid];
    }
    
    $roleid_list = implode(',', array_map('intval', $related_user_ids));
    
    // Fetch restricted products for users
    $restrictuserout = "
        SELECT art_no, roleid, 'user' as Type
        FROM taw_restrict_product 
        WHERE roleid NOT IN ($roleid_list) 
        AND Type='user'
    ";
    
    $restrictuseroutres = $wpdb->get_results($restrictuserout, ARRAY_A);
    
    $restrictuserin = "
        SELECT art_no, roleid, 'user' as Type
        FROM taw_restrict_product 
        WHERE roleid IN (" . implode(',', array_map('intval', $related_user_ids)) . ") 
        AND Type='user'
    ";
    $restrictuserinres = $wpdb->get_results($restrictuserin, ARRAY_A);
    
    // Fetch restricted products for roles
    $restrictroleout = "
        SELECT art_no, roleid, 'role' as Type
        FROM taw_restrict_product 
        WHERE roleid != '$user_role' 
        AND Type='role';
    ";
    $restrictroleoutres = $wpdb->get_results($restrictroleout, ARRAY_A);
    
    $restrictrolein = "
        SELECT art_no, roleid, 'role' as Type
        FROM taw_restrict_product 
        WHERE roleid = '$user_role' 
        AND Type='role';
    ";
    $restrictroleinres = $wpdb->get_results($restrictrolein, ARRAY_A);

    // Merge results
    $mergedout_results = array_merge($restrictroleoutres, $restrictuseroutres);
    $mergedin_results = array_merge($restrictroleinres, $restrictuserinres);

    // Create a lookup for in_results by art_no for quick access
    $in_result_lookup = [];
    foreach ($mergedin_results as $in_art_no_obj) {
        $in_result_lookup[$in_art_no_obj['art_no']] = $in_art_no_obj['Type'];
    }
    
    // Initialize arrays
    $final_restrict_art_nos = [];
    
    // Loop through the out_results to determine what to add to the final_restrict_art_nos
    foreach ($mergedout_results as $out_art_no_obj) {
        $out_art_no = $out_art_no_obj['art_no'];
        $out_art_type = $out_art_no_obj['Type'];
    
        if (isset($in_result_lookup[$out_art_no])) {
            // The same art_no exists in both in_result and out_result
            $in_art_type = $in_result_lookup[$out_art_no];
    
            if ($in_art_type !== 'user' && $out_art_type === 'user') {
                // If the in_result's Type is not 'user' and out_result's Type is 'user', add to final_restrict_art_nos
                $final_restrict_art_nos[] = $out_art_no;
            }
        } else {
            // The art_no does not exist in in_result, so add to final_restrict_art_nos
            $final_restrict_art_nos[] = $out_art_no;
        }
    }

    return $final_restrict_art_nos;
}
function exclude_restricted_and_category_for_non_logged_in_users($args) {
    global $wpdb;
    // Check if the user is logged in
    if (!is_user_logged_in()) {
        // Exclude products with category ID 4837
        $user_role = 'custom_uam_guest';
        $user_id = 'guest';
    }
    else {
        // // Get the current user and their role
        // $current_user = wp_get_current_user();
        // $user_roles = $current_user->roles;
        // $user_role = !empty($user_roles) ? $user_roles[0] : '';
        // $user_id = $current_user->ID;
        $current_user = wp_get_current_user();
        $user_roles = $current_user->roles;
        $current_user_role = isset($user_roles[0]) ? $user_roles[0] : '';
    
        // Step 2: Fetch the serialized roles data from the tsm_options table
        $option_name = 'tsm_user_roles'; // Replace with the actual option name where roles are stored
        $serialized_roles_data = $wpdb->get_var($wpdb->prepare("SELECT option_value FROM {$wpdb->prefix}options WHERE option_name = %s", $option_name));
    
        $roles_data = unserialize($serialized_roles_data);
        // Step 3: Check if the current user's role is a subrole and get the main role
        $main_role = null;
    
        foreach ($user_roles as $role) {
            if (isset($roles_data[$role]['roleissubrole']) && $roles_data[$role]['roleissubrole'] == '1') {
                // If the role is a subrole, get the corresponding main role
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
        // Get restricted article numbers
        $restricted_art_nos = get_restricted_art_nos($userid, $user_role);

        // Add meta query to exclude products with restricted article numbers
        $args['meta_query'][] = array(
            'key'     => '_sku',
            'value'   => $restricted_art_nos,
            'compare' => 'NOT IN',
        );



    return $args;
}

add_filter('dgwt/wcas/search_query/args', 'exclude_restricted_and_category_for_non_logged_in_users', 10, 1);


// hide Sparepart in search end
function enqueue_custom_script_reseller_for_edit_account() {
    // Check if the current page is the Edit Account page
    if ( is_account_page()) {
        // Enqueue your custom script
        wp_enqueue_script('custom-script-reseller-account', get_stylesheet_directory_uri() . '/js/custom-script-reseller-account.js', array('jquery'), null, true);
    }
}

// Hook into the wp_enqueue_scripts action
add_action('wp_enqueue_scripts', 'enqueue_custom_script_reseller_for_edit_account');


function custom_authenticate_user($user, $username, $password) {
    // Check if the user is logging in with an email address
    if (is_email($username)) {
        // Get all users with the provided email
        $matching_users = get_users(array('search' => $username, 'search_columns' => array('user_email')));

        // If there are matching users, check the password for each one
        foreach ($matching_users as $matching_user) {
            if (wp_check_password($password, $matching_user->user_pass, $matching_user->ID)) {
                return $matching_user; // Return the first matching user object to allow login
            }
        }
    }

    // Return the original $user object if no match or validation fails
    return $user;
}

// Hook the custom authentication function
add_filter('authenticate', 'custom_authenticate_user', 10, 3);

add_filter('pre_user_email', 'skip_email_exist');
function skip_email_exist($user_email){
    define( 'WP_IMPORTING', 'SKIP_EMAIL_EXIST' );
    return $user_email;
}










add_filter( 'wc_add_to_cart_message_html', '__return_false' );
function save_custom_user_meta($user_id) {
    if (isset($_POST['role'])) {
        $selected_role = $_POST['role'];
        
        // Define reseller roles
        $reseller_roles = ['custom_uam_reseller_eur', 'custom_uam_reseller_sek', 'custom_uam_b2b'];
        
        // Check if the selected role is one of the reseller roles
        if (in_array($selected_role, $reseller_roles)) {
            // Save the customer number
            if (isset($_POST['customer_no'])) {
                $customer_number = sanitize_text_field($_POST['customer_no']);
                update_user_meta($user_id, 'customer_no', $customer_number);
            }
        } else {
            // Clear the customer number if the role is not a reseller role
            delete_user_meta($user_id, 'customer_no');
        }

        // Check if the selected role requires a subcustomer number
        $role_data = get_option('tsm_user_roles');
        
        // Check if the selected role is a subrole of any reseller role
        $is_subrole = false;
        foreach ($reseller_roles as $reseller_role) {
            if (isset($role_data[$reseller_role]['subroles'])) {
                $subroles = $role_data[$reseller_role]['subroles'];
                $subrole_array = array_values($subroles);
                
                if (in_array($selected_role, $subrole_array)) {
                    $is_subrole = true;
                    break;
                }
            }
        }
        
        // Save or clear the subcustomer number based on whether the selected role is a subrole
        if ($is_subrole) {
            if (isset($_POST['subcustomer_no'])) {
                $subcustomer_number = sanitize_text_field($_POST['subcustomer_no']);
                update_user_meta($user_id, 'subcustomer_no', $subcustomer_number);
            }
        } else {
            // If the selected role doesn't require a subcustomer number, hide or clear it
            delete_user_meta($user_id, 'subcustomer_no');
        }
    }
}
add_action('user_register', 'save_custom_user_meta');
add_action('profile_update', 'save_custom_user_meta');

function validate_customer_number_before_save() {
    if (isset($_POST['customer_no'])) {
        global $wpdb;
        $customer_number = sanitize_text_field($_POST['customer_no']);

        $customer_exists = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM tsm_usermeta WHERE meta_key = 'customer_no' AND meta_value = %s",
            $customer_number
        ));

        if ($customer_exists > 0) {
            $response = array(
                'status' => 'error',
                'message' => 'Customer number already exists.'
            );
            wp_send_json_error($response);
        } else {
            wp_send_json_success();
        }
    }
}
add_action('wp_ajax_validate_customer_number', 'validate_customer_number_before_save');
add_action('wp_ajax_nopriv_validate_customer_number', 'validate_customer_number_before_save');

// Function to add custom columns to edit user form
global $user_profile_errors;

function add_custom_columns_to_edit_user_form($user) {
    global $wpdb;

    // // Retrieve the user roles and their corresponding subroles from the database
    $option_query = "SELECT option_value FROM tsm_options WHERE option_name = 'tsm_user_roles'";
    $option_results = $wpdb->get_results($option_query, ARRAY_A);
    $data = unserialize($option_results[0]['option_value']);
    $customer_no_value = esc_attr(get_user_meta($user->ID, 'customer_no', true));
    ?>
    <table class="form-table">
        <?php  //if ((in_array('custom_uam_reseller_eur', $user->roles)) || (in_array('custom_uam_b2b', $user->roles)) || (in_array('custom_uam_reseller_sek', $user->roles))) { ?>
            <tr id="customer_no">
                <th><label for="customer_no"><?php _e('Customer Number (required)', 'text-domain'); ?></label></th>
                <td>
                    <input type="text" name="customer_no" id="customer_no" class="regular-text" value="<?php echo $customer_no_value; ?>" <?php echo $customer_no_value ? 'readonly' : ''; ?> required>
                    <?php if ($customer_no_value) : ?>
                        <input type="hidden" name="original_customer_no" value="<?php echo $customer_no_value; ?>">
                    <?php endif; ?>
                </td>
            </tr>
        <?php //} ?>
        <?php //if (metadata_exists('user', $user->ID, 'subcustomer_no')) : ?>
            <tr id="subcustomer_no">
                <th><label id="subcustomer_no_label" for="subcustomer_no"><?php _e('Link', 'text-domain'); ?></label></th>
                <td>
                    <select name="subcustomer_no" id="subcustomer_no" style="width: 400px;">
                        <option value="" disabled selected>Select the customer no</option>
                        <?php
                        global $wpdb;
                            $customerno_query = "
                                SELECT DISTINCT um.meta_value AS customer_no, r.meta_value AS role 
                                FROM tsm_usermeta um JOIN tsm_usermeta r ON um.user_id = r.user_id
                                WHERE um.meta_key = 'customer_no' AND r.meta_key = 'tsm_capabilities'
                            ";
                            $customerno_results = $wpdb->get_results($customerno_query);

                            $saved_subcustomer_no = get_user_meta($user->ID, 'subcustomer_no', true);

                        foreach ($customerno_results as $result) {
                                $role_name = extract_role($result->role);
                                $selected = ($saved_subcustomer_no == $result->customer_no) ? 'selected' : '';
                                echo '<option value="' . $result->customer_no . '" data-role="' . $role_name . '" ' . $selected . '>' . $result->customer_no . '</option>';
                        }
                        ?>
                    </select>
                </td>
            </tr>
        <?php //endif; ?> 
    </table>
    <script>
    jQuery(document).ready(function($) {
        $('#adduser-role, #role').on('change', function() {
            var selectedRole = $(this).val();
            console.log("selectedRole",selectedRole);
            var resellerRoles = ['custom_uam_reseller_eur', 'custom_uam_reseller_sek','custom_uam_b2b'];
            var correspondingResellerRoleName = null;
            let correspondingResellerRole = null;
            if (resellerRoles.includes(selectedRole)) {
                $('#customer_no').show();
            } else {
                $('#customer_no').hide();
            }
            var roleData = <?php echo json_encode($data); ?>;

            // Check if selectedRole is a subrole of any specified reseller role
            let isSubrole = false;
            resellerRoles.forEach(function(resellerRole) {
                    // Check if the roleData[resellerRole] and its subroles are defined
                    if (roleData[resellerRole] && roleData[resellerRole].subroles) {
                    var subroles = roleData[resellerRole].subroles;
                    console.log("subroles::", subroles);
                    
                    // Convert subroles object to an array
                    var subroleArray = Object.values(subroles);
                    if (subroleArray.includes(selectedRole)) {
                        isSubrole = true;
                        correspondingResellerRoleName = roleData[resellerRole].name;
                        correspondingResellerRole = resellerRole;
                    }
                } else {
                    console.log("No subroles found for role:", resellerRole);
                }
            });

            // Show or hide subcustomer number row based on whether selectedRole is a subrole
            if (isSubrole) {
                $('#subcustomer_no').show();
            } else {
                $('#subcustomer_no').hide();
            }
            //console.log("Corresponding Reseller Role Name:", correspondingResellerRoleName);
            //console.log("correspondingResellerRole:", correspondingResellerRole);

            // Update the label text with the corresponding reseller role name
            if (correspondingResellerRoleName) {
                $('#subcustomer_no_label').html('Link ' + correspondingResellerRoleName + ' <span class="description" style="color: red">*</span>');
            } else {
                $('#subcustomer_no_label').html('Link <span class="description" style="color: red">*</span>'); // Default text if no corresponding role name is found
            }

            // Filter the options in the subcustomer_no select dropdown
            $('#subcustomer_no option').each(function() {
                var optionRole = $(this).data('role');
                console.log('optionRole', optionRole);

                if (correspondingResellerRole === optionRole) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });

            // If there are no visible options, select the default placeholder option
            if ($('#subcustomer_no option:visible').length > 1) {
                $('#subcustomer_no').val($('#subcustomer_no option:visible:first').val());
            } else {
                $('#subcustomer_no').val('');
            }

            });
        
        // Trigger change event initially to set initial visibility and options
        $('#adduser-role, #role').trigger('change');
    });
</script>
    <?php
}
// for photoswipe issue blackscreen
add_filter('woocommerce_gallery_image_html_attachment_image_params', function($imageParams, $attachment_id, $image_size, $main_image){ if($imageParams['data-large_image_width'] == 0 || $imageParams['data-large_image_height'] == 0){ list( $width, $height ) = @getimagesize( $imageParams['data-src'] ); $imageParams['data-large_image_width'] = $width > 0 ? $width: 1000; $imageParams['data-large_image_height'] = $height > 0 ? $height: 1000; } return $imageParams; }, 10, 4);

function validate_custom_user_profile_fields($user_id) {
    global $wpdb, $user_profile_errors;
    $user_profile_errors = new WP_Error();

    $user = get_userdata($user_id);
    $role =isset($_POST['role']) ? $_POST['role'] :  $user->roles;
    $reseller_roles = ['custom_uam_reseller_eur', 'custom_uam_b2b', 'custom_uam_reseller_sek'];
    if (in_array($role, $reseller_roles)) {
    if (empty($_POST['customer_no'])) {
            $user_profile_errors->add('customer_no_error', '<strong>' . __('Error:', 'text-domain') . '</strong> ' . __('Customer Number is required.', 'text-domain'));
        } else {
            $customer_no = sanitize_text_field($_POST['customer_no']);
            $existing_customer_no = $wpdb->get_var($wpdb->prepare(
                "SELECT user_id FROM {$wpdb->usermeta} WHERE meta_key = 'customer_no' AND meta_value = %s AND user_id != %d",
                $customer_no, $user_id
            ));
            if ($existing_customer_no) {
                $user_profile_errors->add('customer_no_error', '<strong>' . __('Error:', 'text-domain') . '</strong> ' . __('Customer Number already exists.', 'text-domain'));
            }
        }
    }

    $option_query = "SELECT option_value FROM tsm_options WHERE option_name = 'tsm_user_roles'";
    $option_results = $wpdb->get_results($option_query, ARRAY_A);
    $data = unserialize($option_results[0]['option_value']);

    $subcustomer_no_required = false;
    foreach ($reseller_roles as $reseller_role) {
        if (isset($data[$reseller_role]['subroles'])) {
            $subroles = $data[$reseller_role]['subroles'];
            if (in_array($role, $subroles)) {
                $subcustomer_no_required = true;
                break;
            }
        }
    }

    if ($subcustomer_no_required && empty($_POST['subcustomer_no'])) {
        $user_profile_errors->add('subcustomer_no_error', '<strong>' . __('Error:', 'text-domain') . '</strong> ' . __('Link Reseller is required.', 'text-domain'));
    }

    if (!empty($user_profile_errors->get_error_messages())) {
        add_filter('user_profile_update_errors', function($errors) use ($user_profile_errors) {
            foreach ($user_profile_errors->get_error_messages() as $error) {
                $errors->add('custom_error', $error);
            }
        });
    }
}
add_action('show_user_profile', 'add_custom_columns_to_edit_user_form');
add_action('edit_user_profile', 'add_custom_columns_to_edit_user_form');
add_action('personal_options_update', 'validate_custom_user_profile_fields');
add_action('edit_user_profile_update', 'validate_custom_user_profile_fields');

// Save custom data when a user profile is updated
function save_custom_columns_data_on_edit_user($user_id) {
    global $user_profile_errors;

    if (!empty($user_profile_errors->get_error_messages())) {
        return; // Stop execution if there are validation errors
    }

    if (isset($_POST['customer_no']) && !empty($_POST['customer_no'])) {
    $user = get_userdata($user_id);
    $role =isset($_POST['role']) ? $_POST['role'] :  $user->roles;
    $reseller_roles = ['custom_uam_reseller_eur', 'custom_uam_b2b', 'custom_uam_reseller_sek'];

    if (current_user_can('edit_user', $user_id)) {
        if (in_array($role, $reseller_roles)) {
            $customer_no = sanitize_text_field($_POST['customer_no']);
            update_user_meta($user_id, 'customer_no', $customer_no);
        }
   
    // Retrieve the roleData from the options data
    global $wpdb;
    $option_query = "SELECT option_value FROM tsm_options WHERE option_name = 'tsm_user_roles'";
    $option_results = $wpdb->get_results($option_query, ARRAY_A);
    $data = unserialize($option_results[0]['option_value']);
    
    // Check if the role is a subrole of any specified reseller role
    $isSubrole = false;
    foreach ($reseller_roles as $resellerRole) {
        if (isset($data[$resellerRole]['subroles'])) {
            $subroles = $data[$resellerRole]['subroles'];
        if (in_array($role, $subroles)) {
                $isSubrole = true;
                break;
            }
        }
    }
    
    // Save or delete the subcustomer_no based on whether the role is a subrole
    if ($isSubrole) {
        if (isset($_POST['subcustomer_no']) && !empty($_POST['subcustomer_no'])) {
                update_user_meta($user_id, 'subcustomer_no', sanitize_text_field($_POST['subcustomer_no']));
            }
        } else {
            delete_user_meta($user_id, 'subcustomer_no');
        }
        }
        }
}
add_action('personal_options_update', 'save_custom_columns_data_on_edit_user');
add_action('edit_user_profile_update', 'save_custom_columns_data_on_edit_user');

add_action('admin_enqueue_scripts', 'override_show_notice');

function override_show_notice() {
    // Enqueue your custom JavaScript file
    wp_enqueue_script('custom-admin-script', get_stylesheet_directory_uri() . '/js/custom-admin.js', array('jquery'), null, true);

    // Localize the script to make the confirm message available
    wp_localize_script('custom-admin-script', 'admin_notice_vars', array(
        'confirm_message' => __('You are about to permanently delete these items from your site.\nThis action cannot be undone.\n\'Cancel\' to stop, \'OK\' to delete.')
    ));
}

// Add action for handling AJAX request to delete data from taw_filter_setting table
add_action('wp_ajax_delete_filter_setting', 'delete_filter_setting_callback');

function delete_filter_setting_callback() {
    global $wpdb;
    
    // Check if tag_ID is provided in the AJAX request
    if ( isset( $_POST['tag_ID'] ) ) {
        $tag_ID = intval( $_POST['tag_ID'] );

        // Retrieve the term name from the database
        $term_name = $wpdb->get_var(
            $wpdb->prepare("SELECT tsm_terms.name FROM tsm_terms WHERE tsm_terms.term_id = %d", $tag_ID)
        );

        // If term name is retrieved successfully, proceed with deletion
        if ($term_name !== null) {
            $term_name = sanitize_text_field( $term_name );

            // Delete rows from taw_filter_setting table
            $table_name = 'taw_filter_setting';
            $where_condition = array( 'cate_no' => $term_name );
            $wpdb->delete($table_name, $where_condition, '%s');

            $segmenttable_name = 'taw_attribute_segment';
            $where_condition = array( 'cate_no' => $term_name );
            $wpdb->delete($segmenttable_name, $where_condition, '%s');

            // Delete from tsm_term_taxonomy and tsm_terms tables
            $delete_sql = $wpdb->prepare(
                "DELETE t, tt
                 FROM tsm_terms AS t
                 INNER JOIN tsm_term_taxonomy AS tt ON t.term_id = tt.term_id
                 WHERE tt.taxonomy = 'category' 
                 AND t.name = %s",
                $term_name
            );

            $result = $wpdb->query($delete_sql);


            // Return success response
            wp_send_json_success('Data deleted successfully');
        } else {
            // Return error response if term name is not found
            wp_send_json_error('Failed to retrieve term name');
        }
    } else {
        // Return error response if tag_ID is not provided
        wp_send_json_error('Tag ID not provided');
    }
}
function my_updated_category_function_oldcat($term_id, $tt_id, $taxonomy) {
    if ($taxonomy === 'product_cat') {
        // Start or resume session
        if (!session_id()) {
            session_start();
        }
        
        // Get the old category name and store it in a session variable
        $oldcategoryname = get_term_field('name', $term_id);
        $_SESSION['oldcategoryname'] = $oldcategoryname;

        $oldcategoryslug = get_term_field('slug', $term_id);
        $_SESSION['oldcategoryslug'] = $oldcategoryslug;

    }
}
add_action('edit_term', 'my_updated_category_function_oldcat', 10, 3);

function my_updated_category_update_function($term_id, $tt_id, $taxonomy) {
    if ($taxonomy === 'product_cat') {
        // Get the new category name
        $catename = isset($_POST['name']) ? wp_kses_post($_POST['name']) : '';
        $cateslug = isset($_POST['slug']) ? wp_kses_post($_POST['slug']) : '';

        // Retrieve the old category name from the session
        if (isset($_SESSION['oldcategoryname'])) {
            $oldcategoryname = $_SESSION['oldcategoryname'];
            unset($_SESSION['oldcategoryname']); // Remove the session variable
        } else {
            // If session variable doesn't exist, fallback to getting it again
            $oldcategoryname = get_term_field('name', $term_id);
        }

        if (isset($_SESSION['oldcategoryslug'])) {
            $oldcategoryslug = $_SESSION['oldcategoryslug'];
            unset($_SESSION['oldcategoryslug']); // Remove the session variable
        } else {
            // If session variable doesn't exist, fallback to getting it again
            $oldcategoryslug = get_term_field('slug', $term_id);
        }
      
        global $wpdb;

        if($oldcategoryname != $catename){
            $table_name = 'taw_filter_setting';

            $data = array(
                'cate_no' => $catename
            );

            $where = array(
                'cate_no' => $oldcategoryname
            );

            $result = $wpdb->update( $table_name, $data, $where );

            $table_segment = 'taw_attribute_segment';

            $segmentdata = array(
                'cate_no' => $catename
            );

            $where = array(
                'cate_no' => $oldcategoryname
            );

            $segmentresult = $wpdb->update( $table_segment, $segmentdata, $where );


            $sql = $wpdb->prepare(
                "UPDATE `tsm_terms` AS t
                 JOIN `tsm_term_taxonomy` AS tt ON t.`term_id` = tt.`term_id`
                 SET t.`name` = %s
                 WHERE tt.`taxonomy` = 'category' 
                 AND t.`name` = %s",
                $catename,
                $oldcategoryname);
            $result = $wpdb->query($sql);
        }


        if($oldcategoryslug != $cateslug)
        {
            $slugsql = $wpdb->prepare(
                "UPDATE `tsm_terms` AS t
                 JOIN `tsm_term_taxonomy` AS tt ON t.`term_id` = tt.`term_id`
                 SET t.`slug` = %s
                 WHERE tt.`taxonomy` = 'category' 
                 AND t.`slug` = %s",
                $cateslug,
                $oldcategoryslug);
            $result = $wpdb->query($slugsql);
        }
        
    }
}
add_action('edited_term', 'my_updated_category_update_function', 10, 3);

function my_new_category_add_function($term_id, $tt_id, $taxonomy) {
    if ($taxonomy === 'product_cat') {
        // Retrieve the term name directly from the term ID
        $term = get_term($term_id); // Get the term object
        $catename = $term ? $term->name : '';
        $cateslug = $term ? $term->slug : '';
        $catedescription = $term ? $term->description : '';

        if (!$catename || !$cateslug) {
            return;
        }

        global $wpdb;

        $table_seg = 'tsm_terms';

        $segmentda = array(
            'name' => $catename, // New category name
            'slug' => $cateslug // Existing attribute_id
        );
        $wpdb->insert($table_seg, $segmentda);

        // Retrieve the inserted term ID
        $inserted_term_id = $wpdb->insert_id;

        if ($inserted_term_id) {
            // Insert the term into tsm_term_taxonomy
            $table_taxonomy = 'tsm_term_taxonomy';
            $taxonomy_data = array(
                'term_taxonomy_id' => $inserted_term_id,
                'term_id' => $inserted_term_id,  // The ID of the inserted term
                'taxonomy' => 'category',       // Taxonomy type
                'description' => $catedescription       // Description as the original term ID
            );
            $wpdb->insert($table_taxonomy, $taxonomy_data);
        }

        // Retrieve all attribute_ids from taw_attribute_heading table
        $attribute_ids = $wpdb->get_col("SELECT id FROM taw_attribute_heading");

        if (!empty($attribute_ids)) {
            // Insert new records into taw_attribute_segment for each attribute_id
            $table_segment = 'taw_attribute_segment';

            foreach ($attribute_ids as $attribute_id) {
                // Check if the record already exists in taw_attribute_segment
                $existing_record = $wpdb->get_var(
                    $wpdb->prepare(
                        "SELECT COUNT(*) FROM $table_segment WHERE cate_no = %s AND attribute_id = %d",
                        $catename,
                        $attribute_id
                    )
                );

                if ($existing_record == 0) {
                    // Insert new record if it doesn't exist
                    $segmentdata = array(
                        'cate_no' => $catename,        // New category name
                        'attribute_id' => $attribute_id // Existing attribute_id
                    );
                    $wpdb->insert($table_segment, $segmentdata);
                }
            }
        }
    }
}

// Hook the function to run when a new category is added
add_action('create_term', 'my_new_category_add_function', 10, 3);
/**
 * Filter the subject of the customer processing order email.
 *
 * @param string $subject Default subject.
 * @param WC_Order $order Order object.
 * @return string
 */
function custom_processing_order_email_subject( $subject, $order ) {
    if (is_user_logged_in()) {   
        $current_user = wp_get_current_user();
        $user_roles = $current_user->roles;
        $order_items           = $order->get_items( apply_filters( 'woocommerce_purchase_order_item_types', 'line_item' ) );
    $has_zero_subtotal = false;
    
    $has_subtotal =  false;
    foreach ( $order_items as $item_id => $item ) {
        $price_of_prod = product_price($order->get_formatted_order_total($item));
    
        $product = $item->get_product();
        $price_of_prod = $product->get_price();
    
    
        if ($price_of_prod == 0 || $price_of_prod === '' || $price_of_prod === null) {
            $has_zero_subtotal = true; 
        }else{
            $has_subtotal =  true;
        }
    }
    
    $is_b2b = false;
    
    if (is_user_logged_in()) {   
        $current_user = wp_get_current_user();
        $user_roles = $current_user->roles;
    
        $is_b2b = in_array('custom_uam_b2b', $user_roles);
    }
    // die();
    
        // Check if the user has the "custom_uam_reseller_eur" role
        if ((in_array('custom_uam_reseller_eur', $user_roles)) || (in_array('custom_uam_reseller_sek', $user_roles)) || (in_array('custom_uam_b2b', $user_roles))){
            //$email_heading='Smart Storing has registered your request.';
            if($has_zero_subtotal && $has_subtotal){
                $email_heading = __( 'Smart Storing has registered your request', 'TAW_TEXT_DOMAIN');
            }
            if($has_subtotal && !$has_zero_subtotal){
            $email_heading = __('Smart Storing has registered your order','woocommerce');
            }
            if($has_zero_subtotal && !$has_subtotal){
                $email_heading = __('Smart Storing has registered your request for quotation','TAW_TEXT_DOMAIN');
            }
        }
    
        if($is_b2b){
            
                $email_heading = __('Smart Storing has registered your request', 'TAW_TEXT_DOMAIN');
    
        }
        $subject = $email_heading;
    }

   



 
    
    return $subject;
}
add_filter( 'woocommerce_email_subject_customer_processing_order', 'custom_processing_order_email_subject', 21, 2 );

add_filter('woocommerce_single_product_image_thumbnail_html', 'replace_product_gallery_link', 10, 2);

function replace_product_gallery_link($html, $attachment_id) {
    // Find the existing href attribute
    $start_pos = strpos($html, 'href="');
    if ($start_pos !== false) {
        $end_pos = strpos($html, '"', $start_pos + 6);
        if ($end_pos !== false) {
            // Replace the href attribute with #
            $html = substr_replace($html, 'href="#"', $start_pos, $end_pos - $start_pos + 1);
        }
    }
    return $html;
}
add_action('wp_ajax_get_role_data', 'get_role_data');
add_action('wp_ajax_nopriv_get_role_data', 'get_role_data');

function get_role_data() {
    if (isset($_POST['role_key'])) {
        $role_key = sanitize_text_field($_POST['role_key']);
        $role = get_role($role_key);
        if ($role) {
            wp_send_json_success($role);
        } else {
            wp_send_json_error('Role not found');
        }
    } else {
        wp_send_json_error('Invalid request');
    }
}

function extract_role($serialized_role) {
    $role_array = unserialize($serialized_role);
    if (is_array($role_array)) {
        $role_keys = array_keys($role_array);
        return $role_keys[0]; // Return the first key which is the role name
    }
    return null;
}
function delete_sku_from_taw_restrict_product($post_id) {
   
    // Check if the post type is 'product'
    if (get_post_type($post_id) == 'product') {
        
        // Get the SKU number
        $sku = get_post_meta($post_id, '_sku', true);

        if ($sku) {
            global $wpdb;
            // Delete the SKU from the 'taw_restrict_product' table
            $wpdb->delete(
                'taw_restrict_product',
                array('art_no' => $sku),
                array('%s') // Placeholder for string value
            );

            $wpdb->delete(
                'tsm_product_stack_pricing',
                array('art_no' => $sku),
                array('%s') // Placeholder for string value
            );
        }
    }
}

// Hook into the product deletion process
add_action('before_delete_post', 'delete_sku_from_taw_restrict_product');

add_filter('sanitize_title', 'preserve_utf8_characters_in_slug', 10, 3);
function preserve_utf8_characters_in_slug($title, $raw_title, $context) {
    if ($context === 'save' || $context === 'query') {
        // Convert the title to UTF-8 and handle special characters directly
        $title = mb_strtolower($raw_title, 'UTF-8');
        $title = utf8_uri_encode($title);
        
        // Replace spaces with hyphens and remove any characters that aren't letters, numbers, or hyphens
        $title = preg_replace('/[^%a-z0-9 _-]/', '', $title);
        $title = preg_replace('/\s+/', '-', $title);
        $title = trim($title, '-');
    }
    
    return $title;
}

// Add Notes field to Add Category form
add_action('product_cat_add_form_fields', 'add_notes_field_to_category_form');
function add_notes_field_to_category_form() {
    ?>
    <div class="form-field term-notes-wrap">
        <label for="product_cat_notes"><?php _e('Content', 'woocommerce'); ?></label>
        <textarea name="product_cat_notes" id="product_cat_notes" rows="5" cols="40"></textarea>
        <p><?php _e('Add any additional notes about this category.', 'woocommerce'); ?></p>
    </div>
    <?php
}

// Save Notes field value for new category
add_action('created_product_cat', 'save_product_cat_notes_field', 10, 2);

// Save Notes field value when editing an existing category
add_action('edited_product_cat', 'save_product_cat_notes_field', 10, 2);
function save_product_cat_notes_field($term_id, $tt_id) {
    if (isset($_POST['product_cat_notes'])) {
        $notes = sanitize_textarea_field($_POST['product_cat_notes']);
        
        if ('' === $notes) {
            // Delete the term meta if the content is empty
            delete_term_meta($term_id, 'product_cat_notes');
        } else {
            // Otherwise, update the term meta with the new value
            update_term_meta($term_id, 'product_cat_notes', $notes);
        }
    }
}

// Add Notes field to Edit Category form
add_action('product_cat_edit_form_fields', 'edit_notes_field_to_category_form', 10, 2);
function edit_notes_field_to_category_form($term, $taxonomy) {
    $notes = get_term_meta($term->term_id, 'product_cat_notes', true);
    ?>
    <tr class="form-field term-notes-wrap">
        <th scope="row"><label for="product_cat_notes"><?php _e('Content', 'woocommerce'); ?></label></th>
        <td>
            <textarea name="product_cat_notes" id="product_cat_notes" rows="5" cols="50"><?php echo esc_textarea($notes); ?></textarea>
            <p class="description"><?php _e('Add any additional notes about this category.', 'woocommerce'); ?></p>
        </td>
    </tr>
    <?php
}

// Add Notes column to categories table
add_filter('manage_edit-product_cat_columns', 'add_notes_column_to_product_cat_table');
function add_notes_column_to_product_cat_table($columns) {
    $columns['product_cat_notes'] = __('Content', 'woocommerce');
    return $columns;
}

// Display Notes in the new column
add_filter('manage_product_cat_custom_column', 'display_notes_column_in_product_cat_table', 10, 3);
function display_notes_column_in_product_cat_table($columns, $column, $term_id) {
    if ($column === 'product_cat_notes') {
        $notes = get_term_meta($term_id, 'product_cat_notes', true);
        $columns .= esc_html($notes);
    }
    return $columns;
}

/*
add_action('init', function () {
    // Get all published post IDs
    $args = [
        'post_type'      => 'post', // Fetch only posts
        'post_status'    => 'publish', // Only published posts
        'posts_per_page' => -1, // Retrieve all posts
        'fields'         => 'ids', // Only return IDs
    ];

    $query = new WP_Query($args);

    $lang=getSiteCurrentLang();

    if ($query->have_posts()) {
        foreach ($query->posts as $post_id) {
            // Fetch the post object to get the slug
            $post = get_post($post_id);

            if ($post) {
                $slug = $post->post_name; // Get the slug of the post
            if($lang =='sv'){
                // Add rewrite rule for new URL structure
                add_rewrite_rule(
                    "^nyheter/$slug/?$", // Custom slug pattern
                    "index.php?p=$post_id", // Redirect to the corresponding post ID
                    'top'
                );
            }else{
                // Add rewrite rule for new URL structure
                add_rewrite_rule(
                    "^news/$slug/?$", // Custom slug pattern
                    "index.php?p=$post_id", // Redirect to the corresponding post ID
                    'top'
                );

            }
               
            }
        }
    }

    // Flush rewrite rules (only during development; remove this line after deployment)
    flush_rewrite_rules(false);
});

add_action('template_redirect', function () {
    // Prevent redirect loop
    if (is_singular('post')) {
        global $post;

        if ($post) {
            $slug = $post->post_name;
            $current_url = trim($_SERVER['REQUEST_URI'], '/'); // Get the current URL without domain

            $lang=getSiteCurrentLang();
            if($lang =='sv'){
                // Check if the current URL already contains "news/"
                if (!preg_match('/^nyheter\//', $current_url)) {
                    // Redirect to the new URL with "news/" prepended
                    wp_redirect(home_url("/nyheter/$slug/"), 301);
                    exit;
                }
            }else{
                 // Check if the current URL already contains "news/"
                 if (!preg_match('/^news\//', $current_url)) {
                    // Redirect to the new URL with "news/" prepended
                    wp_redirect(home_url("/news/$slug/"), 301);
                    exit;
                }
            }
        }
    }
});

*/
// start global session for saving the referer url
function start_session() {
    if(!session_id()) {
        session_start();
    }
}
add_action('init', 'start_session', 1);

// get  referer url and save it 
function redirect_url() {
    if (! is_user_logged_in()) {
        $_SESSION['referer_url'] = wp_get_referer();
    } else {
        session_destroy();
    }
}
add_action( 'template_redirect', 'redirect_url' );

//login redirect 
function login_redirect() {
    if (isset($_SESSION['referer_url'])) {
        wp_redirect($_SESSION['referer_url']);
    } else {
        wp_redirect(home_url());
    }
}
add_filter('woocommerce_login_redirect', 'login_redirect', 1100, 2);


// Redirect all /produkt/page/X/ and /produkt-kategori/*/page/X/ to their base URL
add_action( 'template_redirect', 'disable_woocommerce_pagination' );
function disable_woocommerce_pagination() {
    global $wp;
    
    // Check if the current URL is a paginated product or category page
    if ( 
        is_paged() && 
        ( 
            strpos( $_SERVER['REQUEST_URI'], '/produkt/page/' ) !== false || 
            strpos( $_SERVER['REQUEST_URI'], '/produkt-kategori/' ) !== false 
        )
    ) {
        // Get the base URL (remove /page/X/)
        $base_url = home_url( $wp->request );
        $base_url = preg_replace( '/\/page\/[0-9]+\/?$/', '/', $base_url );
        echo '<meta name="robots" content="noindex, nofollow">';
        // 301 redirect to the non-paginated URL
        wp_redirect( $base_url, 301 );
        exit;
    }
    
 
}
// Add the modified date column
add_filter('manage_edit-product_columns', 'add_modified_date_column');
function add_modified_date_column($columns) {
    $new_columns = [];
    
    foreach ($columns as $key => $title) {
        $new_columns[$key] = $title;
        if ($key === 'date') {
            $new_columns['modified_date'] = __('Last Modified', 'woocommerce');
        }
    }
    
    if (!isset($new_columns['modified_date'])) {
        $new_columns['modified_date'] = __('Last Modified', 'woocommerce');
    }
    
    return $new_columns;
}

// Display the column content
add_action('manage_product_posts_custom_column', 'show_modified_date_column', 10, 2);
function show_modified_date_column($column, $post_id) {
    if ($column == 'modified_date') {
        $modified = get_the_modified_time('U', $post_id);
        echo '<span title="' . esc_attr(get_the_modified_date('', $post_id)) . '">';
        echo esc_html(human_time_diff($modified, current_time('U')) . ' ago');
        echo '</span>';
    }
}

// Add admin CSS
add_action('admin_head', 'add_admin_modified_date_styles');
function add_admin_modified_date_styles() {
    echo '<style>
        .wp-list-table .column-modified_date {
            width: 120px !important;
            min-width: 120px !important;
            max-width: 120px !important;
        }
        .column-modified_date span {
            display: inline-block;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 100%;
        }
    </style>';
}

// Make the column sortable
add_filter('manage_edit-product_sortable_columns', 'make_modified_date_column_sortable');
function make_modified_date_column_sortable($columns) {
    $columns['modified_date'] = 'modified_date';
    return $columns;
}


function add_custom_flipbook_script_inline() {
    $current_path = $_SERVER['REQUEST_URI'];
    $allowed_paths = array(
        '/3d-flip-book/magasin-sv/',
        '/3d-flip-book/magazine-eng/'
    );

    if (in_array($current_path, $allowed_paths)) {
        ?>
        <script>
        document.addEventListener("DOMContentLoaded", function() {
        // Only run on allowed paths
        const allowedPaths = [
            '/3d-flip-book/magasin-sv/',
            '/3d-flip-book/magazine-eng/'
        ];
        
        if (!allowedPaths.includes(window.location.pathname)) {
            return;
        }

        let observer;
        let buttonAdded = false;
        let flipBookElement = null;

        function createButton() {
            // Create an anchor tag (<a>)
            const anchorTag = document.createElement('a');
            anchorTag.href = '/magazine-request-form/';
            const btn_text = window.location.pathname === '/3d-flip-book/magazine-eng/' 
                ? "Request Printed Magazine" 
                : "Jag vill ha Magazinet skickat till mig";
            
            // Create a button element
            const button = document.createElement('button');
            button.textContent = btn_text;
            button.className = '_3d-flip-book_btn';
            button.id = '_3d-flip-book_btn';

            anchorTag.appendChild(button);
            return anchorTag;
        }

        function handleFlipbookElement(element) {
            if (!element) return;
            
            element.style.position = 'relative';
            
            // Check if button already exists
            const existingButton = element.querySelector('._3d-flip-book_btn');
            if (!existingButton) {
                element.appendChild(createButton());
                buttonAdded = true;
            }
        }

        function startObserving() {
            // First try to find immediately
            flipBookElement = document.querySelector('._3d-flip-book');
            if (flipBookElement) {
                handleFlipbookElement(flipBookElement);
            }

            // Set up observer to watch for changes
            observer = new MutationObserver(function(mutations) {
                // Check if our button was removed
                if (buttonAdded && flipBookElement && !flipBookElement.querySelector('._3d-flip-book_btn')) {
                    handleFlipbookElement(flipBookElement);
                }
                
                // Check for new flipbook elements (in case of dynamic loading)
                const currentFlipbook = document.querySelector('._3d-flip-book');
                if (currentFlipbook && currentFlipbook !== flipBookElement) {
                    flipBookElement = currentFlipbook;
                    handleFlipbookElement(flipBookElement);
                }
            });

            // Start observing the document body for changes
            observer.observe(document.body, {
                childList: true,
                subtree: true
            });
        }

        startObserving();

        // Clean up observer when leaving the page
        window.addEventListener('beforeunload', function() {
            if (observer) {
                observer.disconnect();
            }
        });
    });
        </script>
        <?php
    }
}
add_action('wp_footer', 'add_custom_flipbook_script_inline');

add_action('wp', 'remove_product_image_zoom');
function remove_product_image_zoom() {
    if (is_product()) {
        remove_theme_support('wc-product-gallery-zoom');
    }
}

// Remove special characters from product slugs (permalink)
add_filter('sanitize_title', 'custom_sanitize_product_slug', 10, 3);

function custom_sanitize_product_slug($slug, $raw_title = '', $context = 'display') {
    // Only run for products
    if (get_post_type() === 'product') {
        // Remove % and any other special characters except hyphens and underscores
        $slug = preg_replace('/[^A-Za-z0-9-_]/', '', $slug);
    }
    return $slug;
}
