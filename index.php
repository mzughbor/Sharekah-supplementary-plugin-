<?php
/**
 * Plugin Name: Custom Page Widgets
 * Description: Supplement plugin for allowing widgets on specific pages like "About Us" and "Almomtaz" to work like homepage widgets.
 * Version: 1.0.4
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

// Add custom page template
function cpw_add_page_template($templates) {
    $templates['template-fullwidth-widgets.php'] = __('Full Width Widgets Template', 'cpw');
    return $templates;
}
add_filter('theme_page_templates', 'cpw_add_page_template');

// Load template from plugin
function cpw_load_plugin_template($template) {
    if (is_page()) {
        $template_file = get_post_meta(get_the_ID(), '_wp_page_template', true);
        if ('template-fullwidth-widgets.php' === $template_file) {
            $template = plugin_dir_path(__FILE__) . 'templates/template-fullwidth-widgets.php';
        }
    }
    return $template;
}
add_filter('template_include', 'cpw_load_plugin_template');

// Add theme-matching styles
function cpw_add_custom_styles() {
    if (is_page(['about-us', 'almomtaz'])) {
        ?>
        <style>
            /* Match theme's widget styling */
            .widget-area-full {
                padding: 40px 0;
                clear: both;
                background: #fff;
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
