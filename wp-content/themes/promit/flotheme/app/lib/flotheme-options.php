<?php
class Flotheme_Options
{
	/**
	 * Holds Flotheme Config
	 * @var array
	 */
	protected $_config;

	/**
	 * Holds all field options
	 * @var array
	 */
	protected $_values;

	public function __construct() {
		// add main admin page for flotheme
		add_action('admin_menu', array($this, 'addThemeSubPage'));

		// add save options action
		add_action('wp_ajax_flotheme_options_save', array($this, 'save'));

		// upload an image
		add_action('wp_ajax_nopriv_flotheme_upload_image', array($this, 'uploadImage'));
		add_action('wp_ajax_flotheme_upload_image', array($this, 'uploadImage'));

		// delete image
		add_action('wp_ajax_flotheme_delete_image', array($this, 'deleteImage'));

		$this->_config = Flotheme_Config::get();

		// fetch all values to object
		$this->_values = $this->_fetchValues();
	}

	/**
	 * Add Options Subpage
	 */
	public function addThemeSubPage()
	{
		add_submenu_page('flotheme', $this->_config['labels']['menu-options'], $this->_config['labels']['menu-options'], 'administrator', 'flotheme', array($this, 'render'));
	}

	/**
	 * Render Options Page
	 */
	public function render()
	{
		if (!isset($_REQUEST['updated'])) {
			$_REQUEST['updated'] = false;
		}

		echo Flotheme_Renderer::render_template('admin-options-template', array(
				'control'   => Flotheme_Control::getInstance(),
				'values'    => $this->getValues(),
				'config'    => $this->_config,
			));
	}


	/**
	 * Save Options via AJAX
	 *
	 * @return JSON
	 */
	public function save()
	{
		try {
			foreach ($this->validate($_REQUEST['flotheme']) as $key => $val) {
				update_option(FLOTHEME_OPTIONS_KEY . '_' . $key, $val);
			}
			// no errors occured, sending success
			$return = array(
				'error'     => 0,
			);
		} catch (Exception $e) {
			// catch errors and trigger an error
			$return = array(
				'error'     => 1,
				'message'   => $e->getMessage(),
			);
		}
		// send response
		echo json_encode($return);exit;
	}

	/**
	 * Filter saving values
	 *
	 * @param array $values
	 * @return array
	 */
	public function validate($values)
	{
		foreach ($values as $k => $v) {
			if (array_key_exists('validate', $this->_config['fields'][$k]))
			{
				if ($this->_config['fields'][$k]['validate'] != 'none' && function_exists($this->_config['fields'][$k]['validate']))
					$v = call_user_func($this->_config['fields'][$k]['validate'], $v);
			}
			else
				switch ($this->_config['fields'][$k]['type']) {
					case 'checkbox':
						$v = (int) $v;
						break;
					case 'text':
						$v = wp_filter_nohtml_kses($v);
						break;
					case 'textarea':
						$v = wp_filter_kses($v);
						break;
					case 'select':
						$v = $v;
						break;
					default:
						$v = wp_filter_nohtml_kses($v);
						break;
				}
			$values[$k] = $v;
		}

		return $values;
	}

	/**
	 * Save single value to DB.
	 *
	 * @param string $field
	 * @param mixed $value
	 */
	public function saveValue($field, $value)
	{
		update_option(FLOTHEME_OPTIONS_KEY . '_' . $field, $value);
	}

	/**
	 * Get all option values
	 *
	 * @return array
	 */
	public function getValues()
	{
		if (!$this->_values) {
			$this->_values = $this->_fetchValues();
		}
		return $this->_values;
	}

	/**
	 * Get single value for field
	 *
	 * @param string $key
	 * @return mixed
	 */
	public function getValue($key)
	{
		return isset($this->_values[$key]) ? $this->_values[$key] : $this->getDefault($key);
	}

	/**
	 * Get default value for key
	 * @param string $key
	 */
	public function getDefault($key)
	{
		return isset($this->_config['fields'][$key]['default']) ? $this->_config['fields'][$key]['default'] : null;
	}

	/**
	 * Load all values from DB.
	 *
	 * @return array
	 */
	public function _fetchValues()
	{
		$values = array();
		foreach (array_keys($this->_config['fields']) as $key) {
			$val = get_option(FLOTHEME_OPTIONS_KEY . '_' . $key);
			$values[$key] = false === $val ? $this->_config['fields'][$key]['default'] : $val;
		}

		return $values;
	}

	/**
	 * Upload image AJAX action
	 */
	public function uploadImage()
	{

		try {
			$field = (string) $_REQUEST['image'];

			if (!$field) {
				throw new Exception('no option found');
			}

			$uploadDir = wp_upload_dir();

			$targetDir = $uploadDir['basedir'] . '/flotheme';

			$tempFile = $_FILES['Filedata']['tmp_name'];
			$filename = $_FILES['Filedata']['name'];

			$imageData = getimagesize($tempFile);

			$fieldConfig = $this->_config['fields'][$field];

			if (!is_dir($targetDir)) {
				@mkdir($targetDir, 0777);
			}

			if (!is_dir($targetDir)) {
				throw new Exception('Directory was not created. Please check permissions on your <strong>uploads</strong> folder.');
			}

			switch ($imageData['mime']) {
				case 'image/png':
				case 'image/x-png':
					$ext = 'png';
					break;
				case 'image/jpeg':
					$ext = 'jpg';
					break;
				case 'image/gif':
					$ext = 'gif';
					break;
				default:
					$ext = 'png';
			}
			$resizedFilename = $field . '.' . $ext;

			$targetFile = $targetDir . '/' . $resizedFilename;

			$url = $uploadDir['baseurl'] . '/flotheme/' . $resizedFilename;

			if (!move_uploaded_file($tempFile, $targetFile)) {
				throw new Exception('File was not uploaded. Please check permissions on your <strong>uploads</strong> folder.');
			};

			$this->saveValue($field, $url);

			$return = array(
				'error'     => 0,
				'html'      => '<img src="' . $url . '?' . time() . '" alt="" /> <a href="#" class="delete" rel="' . $field . '">delete</a>',
			);

		} catch(Exception $e) {
			$return = array(
				'error'     => 1,
				'message'   => $e->getMessage(),
			);
		}

		echo json_encode($return);
		exit;
	}

	/**
	 * Remove image AJAX action.
	 */
	public function deleteImage()
	{
		try {

			$field = (string) $_REQUEST['image'];

			if (!$field) {
				throw new Exception('no option found');
			}

			$uploadDir = wp_upload_dir();

			$url = $this->getValue($field);

			$path = str_replace($uploadDir['baseurl'], $uploadDir['basedir'], $url);

			if (is_file($path)) {
				@unlink($path);
			}

			$this->saveValue($field, '');

			$html = '<input type="file" title="' . $field . '" name="flotheme[' . $field . ']" id="flothemes_option_' . $field . '" />';

			$data = array(
				'error'     => 0,
				'field'     => $field,
				'html'      => $html,
			);

		} catch(Exception $e) {
			$data = array(
				'error'     => 1,
				'message'   => $e->getMessage(),
			);
		}

		echo json_encode($data);
		exit;
	}

	/**
	 * Resize image and return TRUE|FALSE on end.
	 *
	 * @param string $src
	 * @param string $target
	 * @param int $newWidth
	 * @param int $newHeight
	 * @return bool
	 */
	protected function _resizeUploadedImage($src, $target, $newWidth, $newHeight)
	{
		if (!$newWidth || !$newHeight) {
			throw new Exception('Dimensions are not set.');
		}
		$str = file_get_contents($src);
		$source = imagecreatefromstring($str);
		$destination = imagecreatetruecolor( $newWidth, $newHeight );

		imagesavealpha($destination, true);
		imagealphablending($destination, true);

		//imagecolortransparent($destination);

		$width = imagesx($source);
		$height = imagesy($source);

		$left = $top = 0;
		if( $newWidth && $newHeight ) {
			$coeffX = $width / $newWidth;
			$coeffY = $height / $newHeight;
			$coeff = min( $coeffX, $coeffY );
			$left = ( $width - $newWidth*$coeff ) / 2;
			$top = ( $height - $newHeight*$coeff ) / 2 ;
			$width = $coeff * $newWidth;
			$height = $coeff * $newHeight;
		} else if( !$newWidth ) {
			$newWidth = round( $width / $height * $newHeight );
		} else if( !$newHeight ) {
			$newHeight = round( $height / $width * $newWidth );
		}

		if (imagecopyresampled($destination, $source, 0, 0, $left, $top, $newWidth, $newHeight, $width, $height)) {
			//imagejpeg($destination, $target, 100);
			imagepng($destination, $target, 9);
			return true;
		} else {
			return false;
		}
	}
}