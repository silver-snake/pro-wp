<?php get_header(); ?>
<a name="posts"></a>
<ul class="tabs">
    <li class="total active"><a href="">Vezi toate</a></li>
    <li class="in-progress"><a href="">In Progres</a></li>
    <li class="success"><a href="">Indeplinit</a></li>
    <li class="fail"><a href="">Neindeplinit</a></li>
    <li class="hero">Erou</li>
    <li class="tabs-shadow"></li>
</ul>
<div id="posts">
    <div class="posts-wrapper">
        <div class="left">
            <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
            <?php get_template_part( '_postheader', 'index' );?>
            <?php get_template_part( '_postpreview', 'index' );?>
            <?php get_template_part( '_postfooter', 'index' );?>
            <?php endwhile; else: ?>
            <?php get_template_part('_notfound', 'index')?>
            <?php endif; ?>
        </div>
        <?php get_sidebar();?>
    </div>
</div>
<?php get_footer(); ?>