<?php get_header(); ?>
<div id="attachment">
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <div <?php post_class()?>>
            <div class="more-wrapper">
                <div class="more wrapper">
                    <h2><?php the_title();?></h2>
                    <div class="story">
                        <p>
                            <?php if (wp_attachment_is_image()) : ?>
                            
                                <?php echo wp_get_attachment_image(get_the_ID(), array(900, 600)); ?>
                            <?php else:?>
                                <a href="<?php echo wp_get_attachment_url(); ?>" title="<?php echo esc_attr( get_the_title() ); ?>" rel="attachment"><?php echo basename( get_permalink() ); ?></a>
                            <?php endif; ?>   
                        </p>
                    </div>   
                </div>
            </div>
        </div>
     <?php endwhile; else: ?>
        <?php get_template_part('_notfound', 'single')?>
     <?php endif; ?>
</div>
<?php get_template_part('_archives', 'single')?>
<?php get_footer(); ?>