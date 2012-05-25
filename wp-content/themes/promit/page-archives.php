<?php 
/**
 * Template Name: Page Archives
 */
get_header(); ?>
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <div class="page-wrapper">
            <div class="page wrapper">
                <div class="page-title">
                    <h2><?php the_title();?></h2>
                </div>
                <div class="story archives">
                    <div class="latest">
                        <h3>The last 30 Posts</h3>
                        <ul>
                            <?php query_posts('showposts=30'); ?>
                            <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                                <li><a href="<?php the_permalink() ?>"><?php the_title(); ?></a> - Posted on <?php the_time('j F Y') ?> - Comments (<?php echo $post->comment_count ?>)</li>
                            <?php endwhile; endif; ?>	
                        </ul>
                    </div>	
                    <div class="by">
                        <div class="box">
                            <h4>Archives by Month:</h4>
                            <ul>
                                <?php wp_get_archives(array(
                                    'type'  => 'monthly'
                                )); ?>
                            </ul>
                        </div>
                        <div class="box">
                            <h4>Archives by Category:</h4>
                            <ul>
                                 <?php wp_list_categories(array(
                                     'title_li' => false,
                                 )); ?>
                            </ul>
                        </div>
                    </div>
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