<?php
/**
 * Template Name: Full Width Widgets Template
 */

get_header(); 

get_template_part('part-hero');
?>

<main class="main">
    <?php while (have_posts()) : the_post(); ?>
        <?php if (get_the_content()) : ?>
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <article id="entry-<?php the_ID(); ?>" <?php post_class('entry'); ?>>
                            <div class="entry-content">
                                <?php the_content(); ?>
                            </div>
                        </article>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php 
        $widget_area_id = cpw_get_widget_area_id();
        if ($widget_area_id) : ?>
            <div class="widget-area-full">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12">
                            <?php dynamic_sidebar($widget_area_id); ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    <?php endwhile; ?>
</main>

<?php get_footer(); ?> 