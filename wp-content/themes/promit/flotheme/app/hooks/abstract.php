<?php
abstract class Flotheme_Hooks_Abstract
{
    const ACTION = 'Action';
    
    const ACTION_AJAX = 'ActionAjax';
    
    protected $_reflection;
    
    protected $_actions;
    
    /**
     * Defines hook type: action, filter, shortcode, etc
     * @var string
     */
    protected $_type = 'action';
    
    public function __construct() {}
    
    /**
     * Init hooks
     */
    public function init()
    {
        $this->_reflection = new ReflectionClass(get_class($this));
        
        foreach ($this->_reflection->getMethods() as $method) {
            if (substr_count($method->name, '_' . $this->_type)) {
                $this->_actions[] = $method;
            }
        }
        
        $this->addHooks();
    }
    
    /**
     * Init parsed hooks
     */
    public function addHooks()
    {
        foreach ($this->_actions as $action) {
            $this->_addHook($action);
        }
    }
    
    /**
     * Parse comment to find actions and hooks
     * 
     * @param string $comment
     * @return array 
     */
    protected function _parseDocComment($comment)
    {
        $matches = array();
        preg_match_all('~\@([\d\w\-]+)\s*\:\s*([^\n]+)~sim', $comment, $matches);
        
        $actions = array();
        foreach ($matches[0] as $k => $m) {
            $actions[] = array(
                'action'    => $matches[1][$k],
                'hook'      => $matches[2][$k],
            );
        }
        
        
        return $actions;
    }
    
    protected abstract function _addHook($action);
}