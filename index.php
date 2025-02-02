<?php
/**
 * Plugin Name: Custom Page Widgets
 * Description: Supplement plugin for allowing widgets on specific pages like "About Us" and "Almomtaz" to work like homepage widgets.
 * Version: 1.0.8
 * Author: mzughbor
 */

if (!defined('ABSPATH')) {
    exit;
}

// Register custom widget areas for specific pages
function cpw_register_sidebar() {
    // About Us page widgets
    register_sidebar([
        'name'          => __('About Us Page', 'cpw'),
        'id'            => 'about-us-page',
        'description'   => __('Add widgets here to appear on About Us page.', 'cpw'),
        'before_widget' => '<div class="widget-area-full"><div class="container"><div class="row"><div class="col-xs-12"><section id="%1$s" class="widget group %2$s">',
        'after_widget'  => '</section></div></div></div></div>',
        'before_title'  => '<h2 class="section-title">',
        'after_title'   => '</h2>',
    ]);

    // Almomtaz page widgets
    register_sidebar([
        'name'          => __('Almomtaz Page', 'cpw'),
        'id'            => 'almomtaz-page',
        'description'   => __('Add widgets here to appear on Almomtaz page.', 'cpw'),
        'before_widget' => '<div class="widget-area-full"><div class="container"><div class="row"><div class="col-xs-12"><section id="%1$s" class="widget group %2$s">',
        'after_widget'  => '</section></div></div></div></div>',
        'before_title'  => '<h2 class="section-title">',
        'after_title'   => '</h2>',
    ]);
}
add_action('widgets_init', 'cpw_register_sidebar');

// Function to determine which widget area to display
function cpw_get_widget_area_id($page_id = null) {
    if (!$page_id) {
        $page_id = get_the_ID();
    }
    
    $slug = get_post_field('post_name', $page_id);
    
    if ($slug === 'about-us') {
        return 'about-us-page';
    } elseif ($slug === 'almomtaz') {
        return 'almomtaz-page';
    }
    return false;
}

// Insert widgets after the content
function cpw_add_widgets_after_content($content) {
    if (!is_page()) {
        return $content;
    }

    $widget_area_id = cpw_get_widget_area_id();
    if (!$widget_area_id || !is_active_sidebar($widget_area_id)) {
        return $content;
    }

    ob_start();
    dynamic_sidebar($widget_area_id);
    $widgets = ob_get_clean();

    return $content . $widgets;
}
add_filter('the_content', 'cpw_add_widgets_after_content');

// Add support for theme features
function cpw_after_setup_theme() {
    add_theme_support('sharekah-widgets');
    add_theme_support('wonderblocks');
}
add_action('after_setup_theme', 'cpw_after_setup_theme');

// Add YellowPencil support
function cpw_yellowpencil_support() {
    if (function_exists('yp_add_page_support')) {
        yp_add_page_support('about-us-page');
        yp_add_page_support('almomtaz-page');
    }
}
add_action('init', 'cpw_yellowpencil_support');

// Add custom widget classes for YellowPencil and theme compatibility
function cpw_widget_classes($params) {
    if (in_array($params[0]['id'], ['about-us-page', 'almomtaz-page'])) {
        $params[0]['before_widget'] = str_replace('class="', 'class="yp-widget sharekah-widget ', $params[0]['before_widget']);
    }
    return $params;
}
add_filter('dynamic_sidebar_params', 'cpw_widget_classes');

// Enqueue theme scripts and styles needed for widgets
function cpw_enqueue_scripts() {
    if (is_page() && cpw_get_widget_area_id()) {
        wp_enqueue_style('sharekah-widgets');
        wp_enqueue_script('sharekah-widgets');
    }
}
add_action('wp_enqueue_scripts', 'cpw_enqueue_scripts');

// Fix for services widget text display
function cpw_fix_services_widget($instance, $widget) {
    if ($widget->id_base === 'sharekah_services') {
        add_filter('widget_text', 'do_shortcode');
    }
    return $instance;
}
add_filter('widget_display_callback', 'cpw_fix_services_widget', 10, 2);

// Enqueue page-specific styles
function cpw_enqueue_page_styles() {
    if (is_page() && get_post_field('post_name', get_the_ID()) === 'about-us') {
        wp_enqueue_style(
            'cpw-about-us-styles',
            plugins_url('css/about-us.css', __FILE__),
            array(),
            '1.0.0'
        );
    }
}
add_action('wp_enqueue_scripts', 'cpw_enqueue_page_styles');