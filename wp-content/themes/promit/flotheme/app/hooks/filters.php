<?php
class Flotheme_Filters extends Flotheme_Hooks_Abstract
{
	protected $_type = 'filter';

	/**
	 * Method to add every parsed hook.
	 *
	 * @param string $action
	 */
	protected function _addHook($action)
	{
		$comment = $action->getDocComment();

		$actions = $this->_parseDocComment($comment);
		foreach ($actions as $act) {
			if (self::ACTION == $act['action']) {
				add_filter($act['hook'], array($this, $action->name));
			}
		}
	}

	/**
	 * This filter adds query for post search only.
	 *
	 * @param object $query
	 * @return object
	 * @Action: pre_get_posts
	 */
	public function _filter_exclude_search_pages($query)
	{
		if ($query->is_search) {
			$query->set('post_type', 'post');
		}

		return $query;
	}

	/**
	 * Remove Wordpress decoration for galleries
	 *
	 * @return string
	 * @Action: gallery_style
	 */
	public function _filter_remove_gallery_style()
	{
		vardump('here');
		return "<div class='gallery'>";
	}
	/**
	 * Change wp-login url
	 *
	 * @return string
	 * @Action: login_headerurl
	 */
	public function _filter_change_wp_login_url()
	{
		echo home_url('/');
	}

	/**
	 * Change wp-login title
	 *
	 * @return string
	 * @Action: login_headertitle
	 */
	public function _filter_change_wp_login_title()
	{
		echo get_option('blogname');
	}

	/**
	 * Change footer
	 *
	 * @return string
	 * @Action: admin_footer_text
	 */
	public function _filter_remove_footer_admin()
	{
		echo '<span id="footer-thankyou">Developed by <a href="http://www.flosites.com" target="_blank">Flosites</a></span>';
	}
}