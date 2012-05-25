<?php
/**
 * Language Class extends qTranslate Plugin
 *
 *
 */
class Flotheme_Lang
{
	public function filterLang($text, $lang = NULL)
	{
		if (!empty($lang) && function_exists('qtrans_use'))
			return qtrans_use($lang, $text);
		if (function_exists('qtrans_useCurrentLanguageIfNotFoundUseDefaultLanguage'))
			return qtrans_useCurrentLanguageIfNotFoundUseDefaultLanguage($text);
		return $text;
	}

	public function fl_lang($textEn, $textRo, $textRu)
	{
		_e('<!--:ro-->'.$textRo.'<!--:--><!--:en-->'.$textEn.'<!--:-->'.'<!--:ru-->'.$textRu.'<!--:-->');
	}

	public function url($url = '', $lang = '')
	{
		global $q_config;
		$lang = !$lang ? $q_config['language'] : '';
		return qtrans_convertURL($url, $lang);
	}

	public function get_fl_lang($textEn, $textRo, $textRu)
	{
		ob_start();
		$this->fl_lang($textEn, $textRo, $textRu);
		return ob_get_clean();
	}

	/**
	 * Function use config.php and 'lang' branch.
	 * In each template you have a couple of words which should be translated to current language. You can define this
	 * words of phrases in config by label and get relevant to current language translation.
	 * @todo Add visual interface to this self-made dictionary
	 * @param $label
	 * @return mixed
	 */
	public function label($label)
	{
		$config = flotheme_get_config();
		$langValues = array();
		list($langValues['en'], $langValues['ro'], $langValues['ru']) = explode('~',$config['lang'][$label]);
		return $langValues[$this->current()];
	}

	public function current()
	{
		return qtrans_getLanguage();
	}
}

/**
 * Get instance of a class
 * @usage flotheme_lang()->url($someUrl, 'en'); will process url with english language var.
 * @return Flotheme_Lang
 */
function flotheme_lang()
{
	static $obLang;
	if (!is_object($obLang))
		$obLang = new Flotheme_Lang();
	return $obLang;
}