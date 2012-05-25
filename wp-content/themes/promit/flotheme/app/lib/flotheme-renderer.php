<?php

class Flotheme_Renderer
{
    /**
     * Static method to render simple template
     * 
     * @param string $filename
     * @param array $args
     * @return string 
     */
    public static function render($filename, $args = array())
    {
        if (is_file($filename)) {
            ob_start();
            extract($args);
            include($filename);
            return ob_get_clean();
        }
        trigger_error('Invalid filepath [' . $filename . ']');
        return false;
    }
    
    /**
     * Render admin template
     * 
     * @param string $template
     * @param array $args
     * @return string 
     */
    public static function render_template($template, $args)
    {
        return self::render(FLOTHEME_TEMPLATES . '/' . $template . '.php', $args);
    }
}