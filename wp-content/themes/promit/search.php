<?php get_header(); ?>
<div id="posts">
    <div id="posts-title" class="wrapper">
        <h2><?php printf( __( 'Search Results for: %s', 'johndoe' ), '<span>' . get_search_query() . '</span>' ); ?></h2>
    </div>
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <?php get_template_part( '_postheader', 'index' );?>
            <?php get_template_part( '_postpreview', 'index' );?>
            <div class="more-wrapper">
                <div class="more wrapper"></div>
            </div>
        <?php get_template_part( '_postfooter', 'index' );?>
     <?php endwhile; else: ?>
        <div class="page-wrapper">
            <div class="page wrapper">
                <h3>Sorry, no posts matched your criteria.</h3>
            </div>
        </div>
     <?php endif; ?>
</div>
<?php get_template_part('_archives', 'single')?>
<?php get_footer(); ?>