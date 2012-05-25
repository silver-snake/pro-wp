<?php get_header(); ?>
<div id="post">
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <div <?php post_class()?>>
            <?php get_template_part( '_postpreview', 'single' );?>
                <div class="more">
                    <?php get_template_part( '_postcontent', 'single' );?>
                    <?php get_template_part( '_postactions', 'single' );?>
                    <?php
                        # force inserting comments in index
                        $withcomments = 1;
                        comments_template();
                    ?>
                </div>
        </div>
     <?php endwhile; else: ?>
        <?php get_template_part('_notfound', 'single')?>
     <?php endif; ?>
</div>
<?php get_footer(); ?>