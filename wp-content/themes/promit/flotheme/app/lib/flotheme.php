<?php
abstract class Flotheme
{
    protected $_options;
    
    protected $_actions;
    
    protected $_filters;

	protected $_config;

    public function  __construct()
    {
        $this->init();
		$this->_config = Flotheme_Config::get();
    }

    /**
     * Initialize 
     */
    public function init()
    {
        $this->_options = new Flotheme_Options();
        
        $this->_actions = new Flotheme_Actions();
        
        $this->_filters = new Flotheme_Filters();
        
        if (is_admin()) {
            $this->initAdmin();
        } else {
            $this->initFront();
        }
        
        $this->initPlugins();
        
        add_action('init', array($this, 'initMenus'));
        add_action('init', array($this, 'initPostTypes'));
        $this->initFilters();
        $this->initActions();
        
        $this->initThemeSupport();
        
        
    }
    
    /**
     * Initialize admin specific methods
     */
    public function initAdmin()
    {
        add_action('admin_menu', array($this, 'addThemePage'), 1);
        add_action('init', array($this, 'addThemeStyles'));
    }
    
    /**
     * Initialize front-end specific methods
     */
    public function initFront()
    {
        add_action('init', array($this, 'initFrontend'));
    }
    
    /**
     * Add theme main page to admin area
     */
    public function addThemePage()
    {
        add_menu_page('', $this->_config['labels']['menu-main'], 'administrator', 'flotheme', false, FLOTHEME_ASSETS_URL . '/admin/images/flotheme_icon_16.png', 3);
    }
    
    /**
     * Add admin theme styles
     */
    public function addThemeStyles()
    {
        wp_enqueue_script('jquery-ui-core');
        wp_enqueue_script('jquery-ui-sortable');

        wp_enqueue_style('flotheme_admin_css', FLOTHEME_ASSETS_URL . '/admin/css/admin.css', array(), FLOTHEME_THEME_VERSION);
        wp_enqueue_style('flotheme_uploadify', FLOTHEME_3RDPARTY_URL . '/uploadify/uploadify.css', array(), FLOTHEME_THEME_VERSION);
        
        wp_enqueue_script('flotheme_swfobject', FLOTHEME_3RDPARTY_URL . '/uploadify/swfobject.js', array(), FLOTHEME_THEME_VERSION);
        wp_enqueue_script('swfobject');
        wp_enqueue_script('flotheme_uploadify', FLOTHEME_3RDPARTY_URL . '/uploadify/jquery.uploadify.v2.1.4.min.js', array('jquery', 'flotheme_swfobject'), FLOTHEME_THEME_VERSION);
        
        wp_enqueue_script('flotheme_jquery_form', FLOTHEME_3RDPARTY_URL . '/jquery.form.js', array('jquery'), FLOTHEME_THEME_VERSION);
        
    }
    
    /**
     * Get options object
     * 
     * @return object
     */
    public function options()
    {
        return $this->_options;
    }
    
    /**
     * Add post type wrapper
     * 
     * @param string $name
     * @param array $config
     * @param string $singular
     * @param string $multiple
     * @return bool
     */
    protected function _addPostType($name, $config, $singular = 'Entry', $multiple = 'Entries')
    {
        if (!isset($config['labels'])) {
            $config['labels'] = array(
                'name' => __($multiple),
                'singular_name' => __($singular),
                'not_found'=> __('No ' . $multiple . ' Found'),
                'not_found_in_trash'=> __('No ' . $multiple . ' found in Trash'),
                'edit_item' => __('Edit ', $singular),
                'search_items' => __('Search ' . $multiple),
                'view_item' => __('View ', $singular),
                'new_item' => __('New ' . $singular),
                'add_new' => __('Add New'),
                'add_new_item' => __('Add New ' . $singular),
            );
        }
        
        register_post_type($name, $config);
        
        return true;
    }
    
    /**
     * Init plugin wrapper
     * 
     * @param string $name
     * @return object 
     */
    protected function _initPlugin($name)
    {
        $path = FLOTHEME_PLUGINS . '/' . $name . '/' . $name . '.php';
        $className = 'Flotheme_Plugin_' . ucfirst($name);
        
        require_once $path;
        $obj = new $className();
        $obj->init();
        
        return $obj;
    }
    
    public abstract function initMenus();
    
    public abstract function initPostTypes();
    
    public abstract function initFilters();

    public abstract function initFrontend();

    public abstract function initActions();

    public abstract function initThemeSupport();
    
    public abstract function initPlugins();
}
