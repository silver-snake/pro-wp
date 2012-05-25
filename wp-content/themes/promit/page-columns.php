<?php 
/**
 * Template Name: Page Columns
 */
get_header(); ?>
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <div class="page-wrapper">
            <div class="page wrapper">
                <div class="page-title">
                    <h2><?php the_title();?></h2>
                </div>
                <div class="story columns">
                    <?php the_content();?>
                </div>
            </div>
        </div>

        <?php if (count(flotheme_get_recent_galleries())) : ?>
            <?php get_template_part('_recentgalleries')?>
        <?php endif; ?>

     <?php endwhile; else: ?>
        <?php get_template_part('_notfound', 'single')?>
     <?php endif; ?>
<?php get_template_part('_archives', 'single')?>
<?php get_footer(); ?>