<?php
/**
 * Plugin Name: Custom Page Widgets
 * Description: Supplment plugin for allowing widgets on specific pages like "About Us" and "Almomtaz" to work like home page widgets idea.
 * Version: 1.0.1
 * Author: mzughbor
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Register a custom widget area
function cpw_register_sidebar() {
    register_sidebar([
        'name'          => __('Custom Page Widgets', 'cpw'),
        'id'            => 'custom-page-widgets',
        'description'   => __('Widgets added here will only show on specific pages.', 'cpw'),
        'before_widget' => '<div class="custom-widget">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ]);
}
add_action('widgets_init', 'cpw_register_sidebar');

// Shortcode to insert widgets inside content
function cpw_shortcode() {
    ob_start();
    if (is_page(['about-us', 'almomtaz'])) { // Change slugs as needed
        dynamic_sidebar('custom-page-widgets');
    }
    return ob_get_clean();
}
add_shortcode('custom_page_widgets', 'cpw_shortcode');

// Automatically insert widgets into page content
function cpw_insert_widgets_into_content($content) {
    if (is_page(['about-us', 'almomtaz'])) {
        $widget_content = cpw_shortcode();
        $content .= $widget_content; // Append widgets to content
    }
    return $content;
}
add_filter('the_content', 'cpw_insert_widgets_into_content');
