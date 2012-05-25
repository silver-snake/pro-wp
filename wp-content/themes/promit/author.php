<?php get_header(); ?>
<div id="posts">
    <div id="posts-title" class="wrapper">
        <h2><?php printf( __( 'Author Archives: %s', 'johndoe' ), "<span class='vcard'><a class='url fn n' href='" . get_author_posts_url( get_the_author_meta( 'ID' ) ) . "' title='" . esc_attr( get_the_author() ) . "' rel='me'>" . get_the_author() . "</a></span>" ); ?></h2>
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