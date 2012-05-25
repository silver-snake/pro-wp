<?php
function flotheme_get_config() {
    return Flotheme_Config::get();
}

/**
 * Get all options
 * 
 * @global object $flotheme
 * @return array 
 */
function flotheme_get_options() {
    global $flotheme;
    return $flotheme->options()->getValues();
}

/**
 * Display single option
 * 
 * @param string $option 
 */
function flotheme_option($option) {
    echo flotheme_get_option($option);
}
/**
 * Get single option
 * 
 * @global object $flotheme
 * @param string $option
 * @return mixed 
 */
function flotheme_get_option($option) {
    global $flotheme;
    return $flotheme->options()->getValue($option);
}

/**
 * Display permalink 
 * 
 * @param int|string $system
 * @param int $isCat 
 */
function flotheme_permalink($system, $isCat = false) {
    echo flotheme_get_permalink($system, $isCat);
}
/**
 * Get permalink for page, post or category
 * 
 * @param int|string $system
 * @param bool $isCat
 * @return string
 */
function flotheme_get_permalink($system, $isCat = 0)  {
    if ($isCat) {
        if (!is_numeric($system)) {
            $system = get_cat_ID($system);
        }
        return get_category_link($system);
    } else {
        $page = flotheme_get_page($system);
        
        return null === $page ? '' : get_permalink($page->ID);
    }
}

/**
 * Display custom excerpt
 */
function flotheme_excerpt() {
    echo flotheme_get_excerpt;
}
/**
 * Get only excerpt, without content.
 * 
 * @global object $post
 * @return string 
 */
function flotheme_get_excerpt() {
    global $post;
    return $post->post_excerpt ? apply_filters('the_content', $post->post_excerpt) : '';
}

/**
 * Comment list callback. Renders a comment.
 *
 * @param object $comment
 * @param array $args
 * @param int $depth
 */
function flotheme_comment($comment, $args, $depth)
{
	$GLOBALS['comment'] = $comment;
	get_template_part('_comment');
}

/**
 * Display first category link
 */
function flotheme_first_category() {
    $cat = flotheme_get_first_category();

    echo '<a href="' . flotheme_get_permalink($cat->cat_ID, true) . '">' . $cat->name . '</a>';
}
/**
 * Parse first post category
 */
function flotheme_get_first_category()  {
    $cats = get_the_category();
    return $cats[0];
}

/**
 * Get page by name, id or slug. 
 * @global object $wpdb
 * @param mixed $name
 * @return object 
 */
function flotheme_get_page($slug) {
    global $wpdb;
    
    if (is_numeric($slug)) {
        $page = get_page($slug);
    } else {
        $page = $wpdb->get_row($wpdb->prepare("SELECT DISTINCT * FROM $wpdb->posts WHERE post_name=%s AND post_status=%s", $slug, 'publish'));
    }
    
    return $page;
}

/**
 * Find all subpages for page
 * @param int $id
 * @return array
 */
function flotheme_get_subpages($id) {
    $query = new WP_Query(array(
        'post_type'         => 'page',
        'orderby'           => 'menu_order',
        'order'             => 'ASC',
        'posts_per_page'    -1,
        'post_parent'       => (int) $id,
    ));

    $entries = array();
    while ($query->have_posts()) : $query->the_post();
        $entry = array(
            'id' => get_the_ID(),
            'title' => get_the_title(),
            'link' => get_permalink(),
            'content' => get_the_content(),
        );
        $entries[] = $entry;
    endwhile;
    wp_reset_query();
    return $entries;
}
