<?php
class Flotheme_Config
{
	/**
	 * Static method to get configuration file
	 * @return array
	 */
	public static function get()
	{
		include FLOTHEME_ROOT.'/config.php';
		return $flotheme_config;
	}
}