<?php 
/**
 * Template Name: Page Sitemap
 */
get_header(); ?>
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <div class="page-wrapper">
            <div class="page wrapper">
                <div class="page-title">
                    <h2><?php the_title();?></h2>
                </div>
                <div class="story sitemap">

                    <div class="box">
                        <h3>Pages</h3>
                        <ul>
                            <?php wp_list_pages(array(
                                'depth' => 1,
                                'sort_column'   => 'menu_order',
                                'title_li'  => '',
                            )); ?>		
                        </ul>
                    </div>
                    
                    <div class="box">
                        <h3>Categories</h3>
                        <ul>
                        <?php wp_list_categories(array(
                            'title_li'      => '',
                            'hierarchical'  => 0,
                            'show_count'    => 1,
                        )); ?>	
                        </ul>	
                    </div>
                    
                    
			<?php
		
				$cats = get_categories();
				foreach ($cats as $cat) {
		
				query_posts('cat='.$cat->cat_ID);
	
			?>
			
                    <div class="box">
                        <h3><?php echo $cat->cat_name; ?></h3>
                        <ul>	
                            <?php while (have_posts()) : the_post(); ?>
                            <li><a href="<?php the_permalink() ?>"><?php the_title(); ?></a> - Posted on <?php the_time('j F Y') ?> - Comments (<?php echo $post->comment_count ?>)</li>
                            <?php endwhile;  ?>
                        </ul>
                    </div>
		
		<?php } ?>
                    
                    
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