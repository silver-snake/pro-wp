<?php
$flotheme_config = array(
	'labels' => array(//Block for naming everything in admin panel.
		'menu-main'		=> 'Promit',
		'menu-options'	=> 'Options'
	),
	'fields'    => array(

        'image'   => array(
            'label'         => 'Logo URL',
            'type'          => 'image',
            'width'         => 215,
            'height'        => 200,
            'default'       => '',
            'description'   => 'Leave this field blank if you want to use the default images/logo.png file.',
        ),
		'copyright'    => array(
			'label'         => 'Copyright',
			'type'          => 'text',
			'default'       => '',
		),
		'ajax_comments' => array(
			'label'         => 'Enable AJAX Comments',
			'type'          => 'checkbox',
			'default'       => 1,
		),
		'ajax_posts'    => array(
			'label'         => 'Enable AJAX Posts',
			'type'          => 'checkbox',
			'default'       => 1,
		),
		'ajax_open_single'  => array(
			'label'         => 'Load single post on page',
			'type'          => 'checkbox',
			'default'       => 1,
		),
		'welcome_active'    => array(
			'label'     => 'Enable Welcome Area',
			'type'          => 'checkbox',
			'default'       => 1,
			'toggle'        => 'welcome',
		),
		'welcome_title'   => array(
			'label'         => 'Title',
			'type'          => 'text',
			'default'       => '',
		),
		'welcome_text'   => array(
			'label'         => 'Text',
			'type'          => 'textarea',
			'default'       => '',
		),
		'twitter'   => array(
			'label'         => 'Twitter',
			'description'   => 'Enter your full twitter profile url (will not show if empty)',
			'type'          => 'text',
			'default'       => '',
		),
		'facebook'  => array(
			'label'         => 'Facebook',
			'description'   => 'Enter your full facebook profile url (will not show if empty)',
			'type'          => 'text',
			'default'       => '',
		),
		'vimeo'  => array(
			'label'         => 'Vimeo',
			'description'   => 'Enter your full vimeo url (will not show if empty)',
			'type'          => 'text',
			'default'       => '',
		),
		'youtube'  => array(
			'label'         => 'Youtube',
			'description'   => 'Enter your full youtube url (will not show if empty)',
			'type'          => 'text',
			'default'       => '',
		),
		'flickr'  => array(
			'label'         => 'Flickr',
			'description'   => 'Enter your full flickr url (will not show if empty)',
			'type'          => 'text',
			'default'       => '',
		),
		'linkedin'  => array(
			'label'         => 'LinkedIn',
			'description'   => 'Enter your full linkedin url (will not show if empty)',
			'type'          => 'text',
			'default'       => '',
		),


		'twitter_feed'      => array(
			'label'         => 'Display Latest Tweet',
			'type'          => 'checkbox',
			'default'       => 0,
		),



		'tracking_code_enabled'   => array(
			'label'         => 'Enable Tracking Code',
			'type'          => 'checkbox',
			'default'       => 0,
			'toggle'        => 'analytics',
		),
		'tracking_code'   => array(
			'label'         => 'Tracking Code',
			'description'   => 'Enter your google analytics code here',
			'type'          => 'textarea',
			'default'       => '',
		),
	),

	'boxes_toggle'  => array(
		'welcome_active'        => 'welcome',
		'simple_menu'           => 'navigation',
		'tracking_code_enabled' => 'analytics'
	),

	'boxes'    => array(
		'general'   => array(
			'label' => 'General',
			'options'   => array(
				'rss_url', 'copyright', 'welcome_active', 'tracking_code_enabled', 'twitter_feed',
			),
		),
		'welcome' => array(
			'label' => 'Welcome Area',
			'options' => array('welcome_title', 'welcome_text'),
		),
		'links'   => array(
			'label'     => 'Links',
			'options'     => array(
				'twitter', 'facebook', 'youtube',
			),
		),
		'ajax'  => array(
			'label'     => 'AJAX',
			'options'   => array(
				'ajax_comments', 'ajax_posts', 'ajax_open_single'
			),
		),
		'analytics'  => array(
			'label'     => 'Tracking Code',
			'options'   => array(
				'tracking_code',
			),
		),
	),

	'sneakpeek' => array(
		'width'     => 600,
		'height'    => 400,
	),

	'contact'   => array(
		'fields'    => array(
			'name'  => array(
				'label' => 'Name',
				'type'  => 'text',
				'required'  => true,
			),
			'email'  => array(
				'label' => 'Email',
				'email' => true,
				'required'  => true,
				'type'  => 'text',
			),
			'subject'  => array(
				'label' => 'Subject',
				'type'  => 'text',
				'required' => true
			),
			'message'  => array(
				'label' => 'Message',
				'type'  => 'textarea',
				'required' => 'true',
			),
		),
		'boxes' => array(
			'inputs'    => array('name', 'email', 'subject'),
			'area'      => array('message'),
		)
	),
	'lang'	=> array(
		/*'example_label'     => 'english~romanian~russian' //all translations are separated by '~'. Order is defined in
		lang function file*/
	),
);