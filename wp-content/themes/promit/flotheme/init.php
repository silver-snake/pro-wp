<?php
# load theme data
$theme_data = get_theme_data(get_stylesheet_uri());

# define app constants
define('FLOTHEME_THEME_VERSION', '0.2');
define('FLOTHEME_THEME_PREFIX', get_template().'_');
define('FLOTHEME_OPTIONS_KEY', 'flotheme_' . FLOTHEME_THEME_PREFIX . 'options');
define('FLOTHEME_PATH', TEMPLATEPATH);
define('FLOTHEME_ROOT', FLOTHEME_PATH . '/flotheme');
define('FLOTHEME_APP', FLOTHEME_ROOT . '/app');
define('FLOTHEME_TEMPLATES', FLOTHEME_APP . '/templates');
define('FLOTHEME_PLUGINS', FLOTHEME_APP . '/plugins');
define('FLOTHEME_WIDGETS', FLOTHEME_APP . '/widgets');
define('FLOTHEME_LIB', FLOTHEME_APP . '/lib');
define('FLOTHEME_HOOKS', FLOTHEME_APP . '/hooks');
define('FLOTHEME_CONFIGS', FLOTHEME_APP);
define('FLOTHEME_VERSION', $theme_data['Version']);

define('FLOTHEME_ROOT_URL', get_template_directory_uri() . '/flotheme');
define('FLOTHEME_ASSETS_URL', FLOTHEME_ROOT_URL . '/assets');
define('FLOTHEME_3RDPARTY_URL', FLOTHEME_ROOT_URL . '/thirdparty');

define('FLOTHEME_IS_MOBILE', preg_match('~(iPad|iPod|iPhone|Blackberry|Android)~si', $_SERVER['HTTP_USER_AGENT']));

# requiring all files from libs and configs
foreach(array('functions', 'lib', 'hooks', 'widgets') as $folder)
{
	foreach (glob(FLOTHEME_APP . '/' .  $folder . '/*.php') as $filename)
		require_once $filename;
}

# require current theme customized files: hooks (actions), functions, app
foreach (glob(FLOTHEME_ROOT.'/*.php') as $filename)
	require_once $filename;
$flotheme = new Flotheme_App();
