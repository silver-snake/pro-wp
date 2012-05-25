<?php
/**
 * Remove links aroung images
 *
 * @param string $content
 * @return string
 */
function flotheme_clear_images($content)
{
	return preg_replace('/<a[^>]*>(<img[^>]*>)<\/a>/iu', '$1', $content);
}
/**
 * Get first post image. Used in filters.
 * 
 * @param string $content
 * @return string
 */
function flotheme_parse_first_image($content = null) 
{
    if (!$content) {
        $content = get_the_content();
    }
    preg_match('~(<img[^>]+>)~sim', trim($content), $matches);
    return $matches[1];
}

/**
 * Remove first image from post. Used in filters.
 * 
 * @param string $content
 * @return string
 */
function flotheme_remove_first_image($content = null) 
{
    if (!$content) {
        $content = get_the_content();
    }
    $content = trim(preg_replace('~(<img[^>]+>)~sim', '', $content, 1));
    return $content;
}

/**
 * Remove all post images.
 * 
 * @param string $content
 * @return string 
 */
function flotheme_remove_images($content = null) {
    if (!$content) {
        $content = get_the_content();
    }
    $content = trim(preg_replace('~(<a[^>]+>)?\s*(<img[^>]+>)\s*(</a>)?~sim', '', $content));
    return $content;
}

function flotheme_get_attachments($id = null)
{
    if (!$id) {
        $id = get_the_ID();
    }
    return get_children(array(
        'post_parent' => $id,
        'post_status' => 'inherit',
        'post_type' => 'attachment',
        'post_mime_type' => 'image',
        'order' => 'ASC',
        'numberposts' => -1,
        'orderby' => 'menu_order',
    ));
}






/**
 * Wrap all images with div
 * @param string $content
 * @return string
 */
function flotheme_wrap_images($content = null) 
{
    if (!$content) {
        $content = get_the_content();
    }
    $content = preg_replace('~(<img[^>]+>)~sim', '<div class="image">$1</div>', $content);
    return $content;
}

/**
 * Get all post images
 * @param string $content
 * @return string
 */
function flotheme_get_all_images($content = null) 
{
    if (!$content) {
        $content = get_the_content();
    }
    preg_match_all('~(<img[^>]+>)~sim', $content, $matches);
    return $matches[1];
}