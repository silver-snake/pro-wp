<?php
class Flotheme_App extends Flotheme
{
    public function __construct() {
        parent::__construct();
    }

    /**
     * Add scripts, styles, etc. to frontend
     */
    public function initFrontend() {
        wp_deregister_script('jquery');
        wp_register_script('jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js');

        wp_enqueue_script('ui', get_template_directory_uri() . '/js/jquery-ui-1.8.20.custom.min.js', array('jquery'), FLOTHEME_VERSION);
        wp_enqueue_style('datepickers_css', get_template_directory_uri() . '/js/jquery-ui-1.8.20.custom.css', array());

        wp_enqueue_script('flotheme_libs', get_template_directory_uri() . '/js/libs.min.js', array('jquery'), FLOTHEME_VERSION);
        wp_enqueue_script('flotheme_screen',  get_template_directory_uri()  . '/js/screen.min.js', array('jquery', 'flotheme_libs'), FLOTHEME_VERSION);
        if (FLOTHEME_IS_MOBILE) {
            wp_enqueue_style('flotheme_mobile', get_template_directory_uri() . '/mobile.css', array());
        }
    }

    /**
     * Initialize menus
     * Leave empty, if no menus used
     */
    public function initMenus() {
        # register navigation
        if (function_exists('register_nav_menus')) {
            register_nav_menus(array(
                'header_menu'	=> 'Header Menu',
            ));
        }
    }

    /**
     * Init custom post types
     * Use _addPostType wrapper to add new
     * Leave empty if no custom post types used
     */
    public function initPostTypes()
    {
        $this->_addPostType('politician', array(
            'public' => true,
            'exclude_from_search' => true,
            'menu_position' => 11,
            'has_archive'   => true,
            'supports'=> array(
                'title',
                'editor',
                'page-attributes',
                'thumbnail'
            ),
            'show_in_nav_menus'=> false,
        ), 'Politician', 'Politicians');

        $this->_addPostType('party', array(
            'public' => true,
            'exclude_from_search' => true,
            'menu_position' => 12,
            'has_archive'   => true,
            'supports'=> array(
                'title',
                'editor',
                'page-attributes',
                'thumbnail'
            ),
            'show_in_nav_menus'=> false,
        ), 'Party', 'Parties');
    }

    /**
     * Init filters
     */
    public function initFilters() {
        $this->_filters->init();
    }

    /**
     * Init actions
     */
    public function initActions()
    {
        $this->_actions->init();
    }

    /**
     * Init plugins
     * Use _initPlugin wrapper to add new plugin
     */
    public function initPlugins()
    {
        $plugins = array('vote', 'contact', 'pagination');

        foreach ($plugins as $plugin) {
            $this->_initPlugin($plugin);
        }
    }

    /**
     * Everything that should be initialized through simple call
     */
    public function initThemeSupport()
    {
        # add theme support
        add_theme_support('post-thumbnails', array('post', 'politician', 'party'));
//        add_theme_support('post-formats', array('gallery'));

        # add image sizes for galleries
        add_image_size( 'gallery-thumbnail', 185, 100, true);
        add_image_size( 'gallery-large', 900, 600, false );
        add_image_size( 'politician-thumb', 128, 128, true);
        add_image_size( 'party-thumb', 21, 21, false);

        // add automatic feed links
        add_theme_support( 'automatic-feed-links' );
    }

    public function initAdmin(){
        parent::initAdmin();

        // TODO: Enqueue some styles and scripts
//        wp_deregister_script('jquery');
//        wp_register_script('jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js');
//        wp_enqueue_style('datepickers_css', get_template_directory_uri() . '/js/datepicker/jquery.datepick.css', array());
//        wp_enqueue_script('datepicker', get_template_directory_uri() . '/js/datepicker/jquery.datepick.js', array('jquery'), FLOTHEME_VERSION);
//        wp_enqueue_script('datepicker_init', get_template_directory_uri() . '/js/datepicker/init.js', array('jquery', 'datepicker'), FLOTHEME_VERSION);

        // Metaboxes for post
        $politicians = get_posts(array('post_type'=>'politician', 'posts_per_page'=>-1, 'orderby'=>'menu_order'));

        $politicians_array = array('empty' => '- empty-');
        foreach ($politicians as $politician) {
            $politicians_array[$politician->ID] = $politician->post_title;
        }

        $metabox_politician = new Flotheme_AddMetaBox();
        $metabox_politician->create('politicians_list', array(
            'destination' => array(
                'label'   => 'Select a Politician',
                'type'   => 'select',
                'default'  => '',
                'value'   => '',
                'options' => $politicians_array,
            )
        ), 'post', 'Link the promise with a Politician');


        $metabox_link = new Flotheme_AddMetaBox();
        $metabox_link->create('link', array(
            'video' => array(
                'label'   => 'Video (vimeo, youtube, smugmug, etc..)',
                'type'   => 'text',
                'default'  => '',
                'value'   => ''
            ),
            'proof' => array(
                'label'   => 'Proof page',
                'type'   => 'text',
                'default'  => '',
                'value'   => ''
            ),
        ), 'post', 'Proof link');

        $metabox_timelimit = new Flotheme_AddMetaBox();
        $metabox_timelimit->create('timelimit', array(
            'from' => array(
                'label'   => 'Starts from',
                'type'   => 'text',
                'default'  => '',
                'value'   => ''
            ),
            'by' => array(
                'label'   => 'Ends by',
                'type'   => 'text',
                'default'  => '',
                'value'   => ''
            ),
        ), 'post', 'Time-limit');


        // Metaboxes for politician

        // Get parties list
        $parties = get_posts(array('post_type'=>'party', 'posts_per_page'=>-1, 'orderby'=>'menu_order'));

        $parties_array = array('empty' => '- empty-');
        foreach ($parties as $party) {
            $parties_array[$party->ID] = $party->post_title;
        }

        $metabox_politician_info = new Flotheme_AddMetaBox();
        $metabox_politician_info->create('info', array(
            'place' => array(
                'label'   => 'Birth place',
                'type'   => 'text',
                'default'  => '',
                'value'   => ''
            ),
            'party' => array(
                'label'   => 'Party',
                'type'   => 'select',
                'default'  => '',
                'value'   => '',
                'options' => $parties_array,
            ),
            'function' => array(
                'label'   => 'Function',
                'type'   => 'text',
                'default'  => '',
                'value'   => ''
            ),
            'job' => array(
                'label'   => 'Currently working at',
                'type'   => 'text',
                'default'  => '',
                'value'   => ''
            ),

        ), 'politician', 'Personal info');

    }
}