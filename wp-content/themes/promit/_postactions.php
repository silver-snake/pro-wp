<div class="actions">
    <div class="share">
        <span>share this post</span>
        <a href="<?php flotheme_share('fb')?>" rel="external">Facebook</a>
        <a href="<?php flotheme_share('twi')?>" rel="external">Twitter</a>
    </div>
    <?php if (comments_open()) : ?>
        <a href="<?php the_permalink()?>#add-comment" class="leave-comment" rel="nofollow">Add a comment</a>
    <?php endif; ?>

    <?php the_tags('<div class="tags"><div>', '', '</div><span>tags</span></div>');?>
</div>