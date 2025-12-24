<?php

/**
 * Plugin Name: Categorywise FAQ's
 * Description: Frequently Asked Question's container with search filter in live mode
 * Author: Things at Web
 * Version: 1.0.0
 * Author URI: https://github.com/Vk2401
 *
 * Text Domain: Categorywise-FAQ
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class CategoryFAQ
{
    public function __construct()
    {
        // Create custom post type
        add_action('init', array($this, 'createCustomPostType'));

        // Add assets (js, css, etc)
        add_action('wp_enqueue_scripts', array($this, 'loadAssets'));

        // Add shortcodes
        add_shortcode('CategoryFAQ', array($this, 'loadShortcode'));
        add_action('add_meta_boxes', array($this, 'addCategoryMetaBox'));

        // Register deactivation hook
        register_deactivation_hook(__FILE__, array($this, 'deactivatePlugin'));
    }

    public function createCustomPostType()
    {
        $args = array(
            'public' => true,
            'has_archive' => true,
            'supports' => array('title', 'editor', 'author', 'page-attributes'),
            'hierarchical' => true, 
            'exclude_from_search' => false,
            'taxonomies' => array('category'), // Link default WordPress categories
            'publicly_queryable' => true,
            'capability_type' => 'post',
            'labels' => array(
                'name' => 'CategoryFAQ',
                'singular_name' => 'Category FAQ',
            ),
            'menu_icon' => 'dashicons-format-chat',
            'show_ui' => true,
            'show_in_menu' => true,
        );

        register_post_type('CategoryFAQ', $args);
    }

    public function loadAssets()
    {
        wp_enqueue_style(
            'CategoryFAQ',
            plugin_dir_url(__FILE__) . 'css/faq.css',
            array(),
            1,
            'all'
        );

        wp_enqueue_script(
            'CategoryFAQ',
            plugin_dir_url(__FILE__) . 'js/main.js',
            array(),
            1,
            'all'
        );
    }

    public function loadShortcode($atts)
    {
        global $wpdb;

        $query = $wpdb->prepare(
            "SELECT t.slug FROM tsm_terms t
             JOIN tsm_term_taxonomy tt ON t.term_id = tt.term_id
             WHERE t.name = %s AND tt.taxonomy = 'category'",
            $atts['product_category']
        );
        $result = $wpdb->get_row($query);

        // Extract the shortcode attributes
        $atts = shortcode_atts(array(
            'product_category' => '', // Accept a WordPress category slug or name
        ), $atts, 'CategoryFAQ');
    
        $lang = getSiteCurrentLang();
    
        // Initialize the translated category ID
        $translated_category_id = null;
        if (!empty($atts['product_category'])) {

            if($lang =='en'){
                $term = get_term_by('slug', $atts['product_category'], 'category');
            }else if($lang =='sv'){
                $term = get_term_by('slug',$result->slug, 'category');
            }
    
            // If term is found, proceed to get the translated ID
            if ($term) {
                // Fetch the translated term ID
                $translated_category_id = apply_filters('wpml_object_id', $term->term_id, 'category', true);
               
            }
        }
    
        // Build the query arguments
    /*   $args = array(
            'post_type' => 'CategoryFAQ',
            'posts_per_page' => -1,
            'tax_query' => array(
                array(
                    'taxonomy' => 'category',
                    'field'    => 'term_id',
                    'terms'    => $translated_category_id,
                    'operator' => 'IN',
                ),
            ),
        ); */

        $args = array(
    'post_type'      => 'CategoryFAQ',
    'posts_per_page' => -1,
    'orderby'        => array('menu_order' => 'ASC', 'date' => 'DESC'), // Primary: menu_order, Secondary: date
    'order'          => 'ASC',
    'tax_query'      => array(
        array(
            'taxonomy' => 'category',
            'field'    => 'term_id',
            'terms'    => $translated_category_id,
            'operator' => 'IN',
        ),
    ),
);
    
        // Query posts
        $faq_query = new WP_Query($args);
        $output = '';
        if ($faq_query->have_posts()) {
            $output .= '<div class="vf-main-container">';
            if($lang =='sv'){
                $output .= '<h2 class="vf-title">Vanliga frågor</h2>';
            }else{ $output .= '<h2 class="vf-title">Frequently Asked Questions</h2>'; }
            $output .= '<div>';
            $output .= '<div class="vf-search-container">';
            $output .= '<span class="vf-search-icon"><img src="/wp-content/plugins/Categorywise-FQA/img/ic_search_active.svg" alt="Search"></span>';
            $output .= '<input type="text" class="vf-text-input" id="vf-filter" placeholder="Search your question here" />';
            $output .= '</div>';
            $output .= '</div>';
            $output .= '<ul class="vfqanda">';
            while ($faq_query->have_posts()) {
                $faq_query->the_post();
                $output .= '<li>';
                $output .= '<strong class="vfquestion">' . get_the_title() . '<span class="vf-toggle-icon fas fa-chevron-up"></span></strong>';
                $output .= '<span class="vfanswer">' . get_the_content() . '</span>';
                $output .= '</li>';
            }
            $output .= '</ul>';
            $output .= '</div>';
        }
    
        // Reset post data
        wp_reset_postdata();
    
        return $output;
    }

    public function addCategoryMetaBox()
    {
        add_meta_box(
            'categorydiv',
            __('Categories', 'text-domain'),
            'post_categories_meta_box',
            'CategoryFAQ', // Post type
            'side',
            'default',
            array('taxonomy' => 'category') // Default taxonomy
        );
    }

    public function deactivatePlugin()
    {

        // Delete all posts of the custom post type
        $args = array(
            'post_type' => 'CategoryFAQ',
            'posts_per_page' => -1,
            'post_status' => 'any', // Include posts with any status
        );
        $faq_query = new WP_Query($args);

        if ($faq_query->have_posts()) {
            while ($faq_query->have_posts()) {
                $faq_query->the_post();
                wp_delete_post(get_the_ID(), true); // True parameter forces deletion of media attachments
            }
        }

        // Unregister the custom post type
        unregister_post_type('CategoryFAQ');

        // You can also remove other registered components, such as scripts and styles.
        wp_deregister_script('CategoryFAQ');
        wp_deregister_style('CategoryFAQ');

        // Flush rewrite rules to remove the custom post type's URL structure from the database.
        flush_rewrite_rules();
    }
}

new CategoryFAQ();