<?php

/**
 * Get word form for used number. Like 1 comment 2 comments no comments.
 * @param int $number
 * @param array $titles Numerical array of forms [0] - for 0, [1] for 1 and [2] for more then one
 * @return string
 */
function flotheme_get_num_words($number, array $titles)
{
	if ($number == 0)
		return $titles[0];
	elseif ($number == 1)
		return $titles[1];
	else
		return $titles[2];
}

/**
 * Returns content part from post_content. Can return "more" or "excerpt".
 * Also it includes image logic. If post has no thumbnail, it will cut first image from post_content before any other operations.
 * @param string $type more | anons
 * @param null $id Will take current global $post if null.
 * @return string
 */
function flotheme_get_content ($type, $id = NULL)
{
	if (!$id)
	{
		global $post;
		$hasThumbnail = has_post_thumbnail();
		$allPost = $post->post_content;
	}
	else
	{
		$hasThumbnail = has_post_thumbnail($id);
		$allPost = get_post($id)->post_content;
	}
	$allPost = $hasThumbnail || flotheme_has_annotation($id) ? $allPost : flotheme_remove_first_image($allPost);
	$explodeResult = explode('<!--more-->', $allPost);
	$annotation = $explodeResult[0];
	$more = empty($explodeResult[1]) ? '' : $explodeResult[1];
	if ($type == 'more')
		return $more;
	return $annotation;
}

/**
 * Alias for flotheme_get_content with applying the_content filters.
 * Will return anons if there is <!--more--> in the post_content
 * @param null $id Will take current global $post if null.
 * @return string
 */
function flotheme_get_post_annotation ($id = NULL)
{
	if (flotheme_get_content('more', $id))
		return apply_filters('the_content', flotheme_get_content('annotation', $id));
	else
		return;
}

/**
 * Alias for flotheme_get_content.
 * Will return full post if there is no <!--more--> in the post_content
 * @param null $id Will take current global $post if null.
 * @return string
 */
function flotheme_get_post_more($id = NULL)
{
	if (flotheme_get_content('more', $id))
		return flotheme_get_content('more', $id);
	else
		return flotheme_get_content('annotation', $id);
}

/**
 * Alias for flotheme_get_post_annotation.
 * @alias flotheme_get_post_annotation
 * @param null $id Will take current global $post if null.
 * @return string
 */
function flotheme_post_annotation($id = NULL)
{
	echo flotheme_get_post_annotation($id);
}

/**
 * Alias for flotheme_get_post_more.
 * @alias flotheme_get_post_more
 * @param null $id Will take current global $post if null.
 * @return string
 */
function flotheme_post_more ($id = NULL)
{
	echo flotheme_get_post_more($id);
}

/**
 * Checkes if there is <!--more--> separator in post_content
 * @param null $id Will take current global $post if null.
 * @return bool
 */
function flotheme_has_annotation($id = NULL)
{
	if (!$id)
		global $post;
	else
		$post = get_post($id);
	return stripos($post->post_content, '<!--more-->');
}

/**
 * Remove two line-breaks one-by-one.
 * @param string $content
 * @return string
 */
function flotheme_remove_gap($content)
{
	return str_ireplace("\n\n", '\n\n', str_ireplace('&nbsp;', '', $content));
}


/**
 * Truncates string with specified length.
 *
 * @param string $string
 * @param int $length
 * @param string $etc
 * @param bool $break_words
 * @param bool $middle
 * @return string
 */
function flotheme_truncate($string, $length = 80, $etc = '&#133;', $break_words = false, $middle = false) {
    if ($length == 0)
        return '';

    if (strlen($string) > $length) {
        $length -= min($length, strlen($etc));
        if (!$break_words && !$middle) {
            $string = preg_replace('/\s+?(\S+)?$/', '', substr($string, 0, $length+1));
        }
        if(!$middle) {
            return substr($string, 0, $length) . $etc;
        } else {
            return substr($string, 0, $length/2) . $etc . substr($string, -$length/2);
        }
    } else {
        return $string;
    }
}