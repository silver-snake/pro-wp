<?php
class Flotheme_Js_Merger
{
	protected $_checkSumm = '';
	protected $_filesList = array();
	protected $_directories = array();
	protected $_filePath = '';

	protected function _storeCheckSumm()
	{

	}

	protected function _getCheckSumm()
	{

	}

	protected function _validateCheckSumm()
	{
		
	}

	protected function _scanDirectory()
	{
		
	}

	protected function _getFilePath()
	{

	}


	protected function _fileExists()
	{

	}

	public function __construct()
	{
		$this->_checkSumm = $this->_getCheckSumm();
		$this->_filePath = $this->_getFilePath();
	}


	public function addDirectory($path)
	{
		if (!in_array($path, $this->_directories))
			$this->_directories[] = $path;
	}

	public function generate()
	{

	}
}