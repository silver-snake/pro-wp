<?php
class Flotheme_Plugin_Contact
{
    protected $_config;

    protected $_path;
    
    public function __construct() 
    {
        $this->_config = Flotheme_Config::get();

        $this->_path = realpath(dirname(__FILE__));
    }
    
    public function init()
    {
        add_shortcode('contact', array($this, 'generate'));
        
        add_action('wp_ajax_flotheme_submit_contact', array($this, 'send'));
        add_action('wp_ajax_nopriv_flotheme_submit_contact', array($this, 'send'));
    }
    
    /**
     * Form shortcode callback
     */
    public function generate()
    {
        $boxes = $this->_config['contact']['boxes'];
        $fields = $this->_config['contact']['fields'];
        
        // generate fields html
        foreach ($fields as $field => $config) {
            $fields[$field]['html'] = $this->generateField($field, $config);
        }
        
        echo Flotheme_Renderer::render($this->_path . '/template_contact_form.php', array(
            'boxes'     => $boxes,
            'fields'    => $fields,
        ));
    }
    
    /**
     * Generate single form field
     * 
     * @param string $field
     * @param array $config
     * @return string 
     */
    function generateField($field, $config)
    {
        $html = '<p>';
        //$html .= '<label>' . $config['label'] . '</label>';

        $class = '';
        if (isset($config['required']) && $config['required']) {
            $class .= ' required';
        }
        if (isset($config['email']) && $config['email']) {
            $class .= ' email';
        }

        switch ($config['type']) {
            case 'text':
                $html .= '<input placeholder="'. $config['label'] .'" type="text" name="' . $field . '" class="' . $class . '" value="" />';
                break;
            case 'textarea':
                $html .= '<textarea placeholder="'. $config['label'] .'" name="' . $field . '" class="' . $class . '"></textarea>';
                break;
            default:
                break;
        }
        $html .= '</p>';
        return $html;
    }
    
    /**
     * Send contact through AJAX
     */
    function send()
    {
        try {
            $fields = $this->_config['contact']['fields'];
            
            $values = array();
            $errors = array();
            foreach ($fields as $field => $config) {
                $values[$field] = $this->filterValue($_POST[$field]);
                if (isset($config['required']) && $config['required'] && !$values[$field]) {
                    throw new Exception('Please enter your ' . $field);
                }
            }
            
            if (!isset($values['subject'])) {
                $values['subject'] = 'New message from ' . $values['name'];
            }
            
            $body = Flotheme_Renderer::render($this->_path . '/template_contact_mail.php', array(
                'values'     => $values,
            ));
            
            wp_mail(get_option('admin_email'), $values['subject'], $body, "From {$values['name']} <{$values['email']}>");
            
            $data = array(
                'success' => 1,
                'msg'     => 'Thank you for your message!',
            );
        } catch (Exception $e) {
            $data = array(
                'error' => 1,
                'msg'   => $e->getMessage(),
            );
        }

        echo json_encode($data);
        exit;
    }
    
    /**
     * Filter submitted value before sending through mail
     * 
     * @param string $value
     * @return string 
     */
    public function filterValue($value)
    {
        return wp_filter_nohtml_kses(trim($value));
    }
}
