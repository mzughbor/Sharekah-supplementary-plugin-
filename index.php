<?php
/**
 * Plugin Name: Custom Page Widgets
 * Description: Supplement plugin for allowing widgets on specific pages like "About Us" and "Almomtaz" to work like homepage widgets.
 * Version: 1.0.3
 * Author: mzughbor
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Register custom widget areas for specific pages
function cpw_register_sidebar() {
    // About Us page widgets
    register_sidebar([
        'name'          => __('About Us Page', 'cpw'),
        'id'            => 'about-us-page',
        'description'   => __('Add widgets here to appear on About Us page.', 'cpw'),
        'before_widget' => '<section id="%1$s" class="widget group %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="section-title">',
        'after_title'   => '</h2>',
    ]);

    // Almomtaz page widgets
    register_sidebar([
        'name'          => __('Almomtaz Page', 'cpw'),
        'id'            => 'almomtaz-page',
        'description'   => __('Add widgets here to appear on Almomtaz page.', 'cpw'),
        'before_widget' => '<section id="%1$s" class="widget group %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="section-title">',
        'after_title'   => '</h2>',
    ]);
}
add_action('widgets_init', 'cpw_register_sidebar');

// Function to determine which widget area to display
function cpw_get_widget_area_id() {
    if (is_page('about-us')) {
        return 'about-us-page';
    } elseif (is_page('almomtaz')) {
        return 'almomtaz-page';
    }
    return false;
}

// Insert widgets into page content
function cpw_insert_widgets_into_content($content) {
    $widget_area_id = cpw_get_widget_area_id();
    if (!$widget_area_id || !is_main_query()) {
        return $content;
    }

    ob_start();
    
    // Open main container like theme does
    echo '<div class="widget-area-full">';
    echo '<div class="container">';
    echo '<div class="row">';
    echo '<div class="col-xs-12">';
    
    // Add widgets
    dynamic_sidebar($widget_area_id);
    
    // Close containers
    echo '</div>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
    
    $widget_content = ob_get_clean();

    // Add the widgets after the main content
    return $content . $widget_content;
}
add_filter('the_content', 'cpw_insert_widgets_into_content');

// Add theme-matching styles
function cpw_add_custom_styles() {
    if (is_page(['about-us', 'almomtaz'])) {
        ?>
        <style>
            /* Match theme's widget styling */
            .widget-area-full {
                padding: 40px 0;
                clear: both;
            }
            .widget-area-full .widget {
                margin-bottom: 40px;
            }
            .widget-area-full .widget:last-child {
                margin-bottom: 0;
            }
            .widget-area-full .section-title {
                font-size: 24px;
                margin-bottom: 25px;
                text-align: center;
                position: relative;
                line-height: 1.2;
            }
            .widget-area-full .widget.group {
                padding: 30px 0;
            }
            .widget-area-full .widget-content {
                margin: 0 auto;
                max-width: 1140px;
            }
            
            /* Responsive adjustments */
            @media (max-width: 767px) {
                .widget-area-full .section-title {
                    font-size: 20px;
                }
                .widget-area-full {
                    padding: 30px 0;
                }
            }
        </style>
        <?php
    }
}
add_action('wp_head', 'cpw_add_custom_styles', 20);

// Add widget area to fullwidth sidebars list
function cpw_add_fullwidth_sidebars($sidebars) {
    $sidebars[] = 'about-us-page';
    $sidebars[] = 'almomtaz-page';
    return $sidebars;
}
add_filter('sharekah_get_fullwidth_sidebars', 'cpw_add_fullwidth_sidebars');
