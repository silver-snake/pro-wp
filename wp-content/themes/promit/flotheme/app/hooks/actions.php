<?php
class Flotheme_Actions extends Flotheme_Hooks_Abstract
{
	protected $_type = 'action';

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
				add_action($act['hook'], array($this, $action->name));
			} elseif (self::ACTION_AJAX == $act['action']) {
				add_action('wp_ajax_' . $act['hook'], array($this, $action->name));
				add_action('wp_ajax_nopriv_' . $act['hook'], array($this, $action->name));
			}
		}
	}

	/**
	 * Change default login logo
	 * @Action: admin_head
	 */
	public function _action_admin_logo() {
		echo '<style type="text/css">#header-logo { background-image: url(' . get_template_directory_uri() . '/images/admin-icon.png) !important; }</style>';
	}

	/**
	 * Change default login logo
	 * @Action: wp_dashboard_setup
	 */
	function _action_remove_dashboard_widgets() {// Globalize the metaboxes array, this holds all the widgets for wp-admin
		global $wp_meta_boxes;
		unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
		unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);
		unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
	}

	/**
	 * Hide settings for galleries in admin panel
	 * @Action: admin_head_media_upload_gallery_form
	 */
	public function _action_remove_gallery_setting_div()
	{
		echo '<style type="text/css">#gallery-settings *{display:none;}</style>';
	}

    /**
     * Load Post AJAX Hook
     * @ActionAjax: flotheme_load_politics
     */
    public function _action_load_politics()
    {
        $args = array( 'post_type' => 'politician', 'posts_per_page' => isset($_GET['max_rows']) ? $_GET['max_rows'] : 10 );

        if (!empty($_GET['q']))
        {
            add_filter( 'posts_where', function($where) {

                $q = mysql_real_escape_string($_GET['q']);
                $where .= " AND post_title LIKE '%{$q}%'";
                return $where;

            });
        }

        $loop = new WP_Query( $args );
        remove_filter( 'posts_where', 'filter_where' );

        $arr = array();
        while ( $loop->have_posts() ) : $loop->the_post();
            ob_start();
            the_title();
            $arr[] = ob_get_contents();
            ob_end_clean();
        endwhile;

        echo $_GET['callback'].'('.json_encode($arr).');';

    }

	/**
	 * Load Post AJAX Hook
	 * @ActionAjax: flotheme_load_post
	 */
	public function _action_load_post()
	{
		global $withcomments;
		$answer = array();
		$query = new WP_Query(array(
				'post_type'     => 'post',
				'p'             => (int) $_POST['id'],
				'post_status'   => 'publish',
			));
		while($query->have_posts()){
			$query->the_post();
			ob_start();
				get_template_part( '_post-text');
			$answer['left'] = ob_get_clean();
			ob_start();
				get_template_part( '_post-pictures');
			$answer['right'] = ob_get_clean();
			ob_start();
				$withcomments = 1;
				comments_template();
			$answer['comments'] = ob_get_clean();
		}
		echo json_encode($answer);
		exit;
	}

	/**
	 * Load Comments AJAX Hook
	 * @ActionAjax: flotheme_load_comments
	 */
	public function _action_flotheme_load_comments()
	{
		$id = (int) $_POST['id'];
		$query = new WP_Query(array(
				'post_type'     => 'post',
				'p'             => $id,
				'post_status'   => 'publish',
			));

		while($query->have_posts() ) : $query->the_post();
									   $comments = get_comments( array(
											   'post_id' => get_the_ID(),
											   'orderby' => 'comment_date_gmt',
											   'order'   => 'ASC',
											   'status' => 'approve',
										   ));
									   wp_list_comments(array('callback' => 'flotheme_comment'), $comments);
		endwhile;
		exit;
	}

	/**
	 * Load More Posts AJAX Hook
	 * @ActionAjax: flotheme_load_more_posts
	 */
	public function _action_flotheme_load_more_posts()
	{
		global $withcomments, $paged, $wp_query;

		$params = array(
			'monthnum'  => (int) $_REQUEST['monthnum'],
			'year'      => (int) $_REQUEST['year'],
			'cat'       => (int) $_REQUEST['cat'],
			'tag_id'    => (int) $_REQUEST['tag_id'],
			'paged'     => (int) $_REQUEST['nextpage'],
			's'         => (string) $_REQUEST['s'],
		);


		$query = new WP_Query($params);
		ob_start();
		while( $query->have_posts() ) : $query->the_post();
										get_template_part( '_postheader');
										get_template_part( '_postpreview');
										echo '<div class="more-wrapper"><div class="more wrapper">';
										echo '</div></div>';
										get_template_part( '_postfooter');
		endwhile;
		$html = ob_get_contents();
		ob_end_clean();

		$response = $params;
		$response['nextpage'] = $response['paged'] + 1;
		if ($query->max_num_pages < $response['nextpage']) {
			$response['nextpage'] = -1;
		}
		$response['html'] = $html;

		$data = json_encode($response);
		echo $data;exit;
	}

	/**
	 * Add variables to JS
	 * @Action: template_redirect
	 */
	public function _action_flotheme_init_js_vars()
	{
		global $wp_query;
		$max = $wp_query->max_num_pages;
		$paged = ( get_query_var('paged') > 1 ) ? get_query_var('paged') : 1;

		$arguments = array(
			'template_dir'      => get_template_directory_uri(),
			'ajax_load_url'     => site_url('/wp-admin/admin-ajax.php'),
			'ajax_comments'     => (int) flotheme_get_option('ajax_comments'),
			'ajax_posts'        => (int) flotheme_get_option('ajax_posts'),
			'ajax_open_single'  => (int) flotheme_get_option('ajax_open_single'),
			'is_mobile'         => (int) FLOTHEME_IS_MOBILE,
            'site_url'          => site_url('/'),
		);

		// add js variables
		wp_localize_script(
			'flotheme_screen',
			'flotheme',
			$arguments
		);
	}

	/**
	 * Change default login logo
	 * @Action: login_head
	 */
    public function _action_login_logo()
    {
        $imageUrl = flotheme_get_option('logo_url')
            ? flotheme_get_option('logo_url') . '?' . time()
            : get_bloginfo('template_directory') . '/images/logo.png';
        $imageInfo = @getimagesize($imageUrl);
        if (!empty($imageInfo))
        {
            $marginLeft = $imageInfo[0] > 320 ? ($imageInfo[0]-320)/2 : null ;
            $width = $imageInfo[0] > 320 ? $imageInfo[0] : '326px';
            echo '<style  type="text/css">
                body { background-color: #e7e7e7 !important }
				h1 a { background-image: url('.$imageUrl.')  !important;
				margin-bottom:1em;
				height: '.$imageInfo[1].'px;
				width: '.$width.'px;
				'.($marginLeft ? 'margin-left: -'.$marginLeft.'px' : '').
                '} </style>';
        }
    }
}