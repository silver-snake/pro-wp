<?php
class Flotheme_CustomPostType
{
	public function init($postTypeId, $postName = NULL, $postPluralName = NULL, array $supports = NULL, array $taxonomies = array())
	{
		$postName = is_null($postName) ? ucfirst($postTypeId) : $postName;
		$postPluralName = is_null($postPluralName) ? $postName.'s' : $postPluralName;
		$supports = !is_array($supports) ? array("title','editor','page-attributes") : $supports;
		$this->_registerPostType($postTypeId,
			array(
				'labels' => array(
					'name'					=> __($postName),
					'singular_name'			=> __($postName),
					'not_found'				=> __("No $postPluralName found"),
					'not_found_in_trash'	=> __("No $postPluralName found in Trash"),
					'edit_item'				=> __("Edit $postName"),
					'search_items'			=> __("Search $postPluralName"),
					'view_item'				=> __("View $postName"),
					'new_item'				=> __("New $postName"),
					'add_new'				=> _x("Add New", $postName),
					'add_new_item'			=> __("Add New $postName"),
				),
				'public'				=> true,
				'has_archive'			=> true,
				'exclude_from_search'	=> true,
				'menu_position'			=> 20,
				'taxonomies'			=> $taxonomies,
				'supports'				=> $supports,
				'show_in_nav_menus'		=> false,
			)
		);
	}

	public function initByArray($postTypeId, array $params)
	{
		$this->_registerPostType($postTypeId, $params);
	}

	protected function _registerPostType($postTypeId, array $params)
	{
		$obHelper = new Flotheme_CustomPostTypeHelper($postTypeId, $params);
		add_action('init', array($obHelper, 'registerPostType'));
	}
}

class Flotheme_CustomPostTypeHelper
{
	protected $_postTypeId;
	protected $_params;

	public function __construct($postTypeId, $params)
	{
		$this->_postTypeId = $postTypeId;
		$this->_params = $params;

	}

	public function registerPostType()
	{
		register_post_type($this->_postTypeId, $this->_params);
	}
}