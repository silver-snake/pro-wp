<?php
/**
 * The template for displaying Comments.
 */
?>

<a name="comments"></a>
<div class="comments">
<?php if ( post_password_required() ) : ?>
    <p class="nopassword"><?php _e( 'This post is password protected. Enter the password to view any comments.', 'twentyten' ); ?></p>
</div><!-- #comments -->
<?php
        /* Stop the rest of comments.php from being processed,
         * but don't kill the script entirely -- we still have
         * to fully load the template.
         */
        return;
endif;
?>

<?php
	// You can start editing here -- including this comment!
?>

<?php if ( have_comments() ) : ?>
    <h4 class="caption"><?php comments_number('no comments', 'one comment')?></h4>

    <?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through? ?>
        <div class="navigation">
                <div class="nav-previous"><?php previous_comments_link('<span class="meta-nav">&larr;</span> Older Comments'); ?></div>
                <div class="nav-next"><?php next_comments_link('Newer Comments <span class="meta-nav">&rarr;</span>'); ?></div>
        </div> <!-- .navigation -->
    <?php endif; // check for comment navigation ?>

    <ol class="commentlist">
        <?php
            /* Loop through and list the comments. Tell wp_list_comments()
             * to use twentyten_comment() to format the comments.
             * If you want to overload this in a child theme then you can
             * define twentyten_comment() and that will be used instead.
             * See twentyten_comment() in twentyten/functions.php for more.
             */
            wp_list_comments( array( 'callback' => 'flotheme_comment' ) );
        ?>
    </ol>

    <?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through? ?>
        <div class="navigation">
                <div class="nav-previous"><?php previous_comments_link('<span class="meta-nav">&larr;</span> Older Comments'); ?></div>
                <div class="nav-next"><?php next_comments_link('Newer Comments <span class="meta-nav">&rarr;</span>'); ?></div>
        </div><!-- .navigation -->
    <?php endif; // check for comment navigation ?>

<?php else : // or, if we don't have comments:

    /* If there are no comments and comments are closed,
     * let's leave a little note, shall we?
     */
    if ( ! comments_open() ) :
?>
	<p class="comments-closed">Comments are closed.</p>
    <?php endif; // end ! comments_open() ?>

<?php endif; // end have_comments() ?>

<?php if (comments_open()) : ?>
    <div class="messages"></div>

    <a name="add-comment"></a>
    <div class="comment-form">
        <h4 class="caption">Add Comment</h4>
        <?php if ( get_option( 'comment_registration' ) && !is_user_logged_in() ) : ?>
            <p class="must-log-in">You must be <a href="<?php echo wp_login_url(get_permalink())?>">logged in</a> to post a comment</p>
        <?php else : ?>
                <form action="<?php echo site_url( '/wp-comments-post.php' ); ?>" method="post">
                        <?php if ( is_user_logged_in() ) : ?>
                            <p class="logged-in-as">Logged in as <a href="%1$s"><?php echo $user_identity; ?></a>. <a href="<?php echo wp_logout_url(get_permalink())?>" title="Log out of this account">Log out?</a></p>
                        <?php else : ?>
                        <div class="inputs">
                            <span>
                                <label>name*</label>
                                <input type="text" name="author" value="" />
                            </span>
                            <span>
                                <label>email*</label>
                                <input type="text" name="email" value="" />
                            </span>
                            <span class="last">
                                <label>website</label>
                                <input type="text" name="url" value="" />
                            </span>
                        </div>
                        <?php endif; ?>
                        <div class="area">
                            <label>message</label>
                            <textarea class="comment" name="comment" cols="45" rows="8"></textarea>
                        </div>
                        <div class="submit">
                            <input name="submit" type="submit" value="Send" />
                            <?php comment_id_fields(); ?>
                        </div>
                </form>
        <?php endif; ?>
    </div><!-- #respond -->

<?php endif; ?>


</div><!-- #comments -->
