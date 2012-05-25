<?php get_header(); ?>
<div id="posts">
    <div id="posts-title" class="wrapper">
        <h2><?php printf( __( 'Category Archives: %s', 'johndoe' ), '<span>' . single_cat_title( '', false ) . '</span>' ); ?></h2>
    </div>
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <?php get_template_part( '_postheader', 'index' );?>
            <?php get_template_part( '_postpreview', 'index' );?>
            <div class="more-wrapper">
                <div class="more wrapper"></div>
            </div>
        <?php get_template_part( '_postfooter', 'index' );?>
     <?php endwhile; else: ?>
        <?php get_template_part('_notfound', 'index')?>
     <?php endif; ?>
</div>
<?php get_template_part('_archives', 'single')?>
<?php get_footer(); ?>