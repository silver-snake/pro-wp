<?php get_header(); ?>
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <div class="page">
            <h2><?php the_title();?></h2>
            <div class="story">
                <?php the_content();?>
            </div>
        </div>
     <?php endwhile; else: ?>
        <?php get_template_part('_notfound', 'single')?>
     <?php endif; ?>
<?php get_footer(); ?>