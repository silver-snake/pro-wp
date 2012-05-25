<?php
class Flotheme_Plugin_Vote {

    protected $_wpdb;

    public $vote_table;

    public $vote_history_table;

    protected $_adminTemplate;

    protected $_url;

    protected $_path;

    public function __construct() {
        global $wpdb;

        $this->_wpdb = $wpdb;

        $this->_path = realpath(dirname(__FILE__));

        $this->_url = FLOTHEME_ROOT_URL . '/app/plugins/sneakpeek';

        $this->_adminTemplate = $this->_path . '/template_vote_admin.php';

        $this->vote_table = $wpdb->prefix.'vote';

        $this->vote_history_table = $wpdb->prefix.'vote_history';

    }

    public function init()
    {
        if (is_admin()) {
            add_action('admin_menu', array($this, 'adminAddPage'));
            add_action('admin_init', array($this, 'adminStyles'));

        }

        add_action('wp_ajax_flotheme_submit_vote', array($this, 'vote_ajax'));
        add_action('wp_ajax_nopriv_flotheme_submit_vote', array($this, 'vote_ajax'));
    }

    /**
     * Callback to add admin styles
     */
    public function adminStyles()
    {
        wp_enqueue_style('pro_admin_vote_css', $this->_url . '/admin.css', array(), FLOTHEME_THEME_VERSION);
    }

    /**
     * Callback to add page
     */
    public function adminAddPage()
    {
        add_submenu_page('flotheme', 'Voting', 'Voting', 'administrator', 'pro_vote', array($this, 'adminPage'));
    }

    /**
     * Page HTML Callback
     */
    public function adminPage()
    {
        if (!isset($_REQUEST['updated'])) {
            $_REQUEST['updated'] = false;
        }

        echo "VOTING PAGE";
    }

    /*
     * Get JSON array with user data
     */
    protected function get_user_data() {

        $user_data = array();

        $user = wp_get_current_user();

        $user_data['user_agent'] = $_SERVER['HTTP_USER_AGENT'];

        $user_data['user_ip'] = $_SERVER['REMOTE_ADDR'];

        if($user->ID != 0) {
            $user_data['user_email'] = $user->user_email;
        }

        $user_data = $user_data;

        return $user_data;
    }

    public function user_voted($post_id = null) {

        if(!$post_id) {
            return false;
        }

        $user_data = array();

        $current_user_data =  $this->get_user_data();

        // Get row according to the user data
        $data_query = "SELECT * FROM $this->vote_history_table
                                WHERE (post_id='$post_id' AND user_ip='$current_user_data[user_ip]')";
        // If user is logged in
        if($current_user_data['user_email']){
            $data_query .= " OR (post_id='$post_id' AND user_email='$current_user_data[user_email]')";
        }

        $user_data = $this->_wpdb->get_row($this->_wpdb->prepare($data_query), ARRAY_A);

        if(!empty($user_data)) {
            return true;
        }

        return false;

    }

    /*
     * Vote for post
     */
    public function vote($post_id = null, $value = null) {
        try{
            if( !$post_id || !$value ) {
                return array(
                    'error' => 1,
                    'results' => 'no_input_data'
                );
            }

            if(!$this->user_voted($post_id)) {

                $user_data = $this->get_user_data();

                $vote_value = ((int)$value == 1) ? 1 : 0;

                // Prepare arrays
                $vote_data = array(
                    'post_id' => $post_id,
                );



                $vote_history_data = array(
                    'post_id' => $post_id,
                    'user_ip' => $user_data['user_ip'],
                    'user_email' => $user_data['user_email'],
                    'user_data' => serialize($user_data),
                    'user_vote' => $vote_value
                );


                $this->_wpdb->insert($this->vote_history_table, $vote_history_data, array('%d', '%s', '%s', '%s', '%d'));

                // If column of this post exists
                $current_value = $this->get_votes($post_id);

                $results = array();

                if($current_value) {

                    if($vote_value == 1) {
                        $vote_data['vote_positive'] = $current_value->vote_positive+1;

                        $results['positive'] = $vote_data['vote_positive'];
                        $results['negative'] = (int)$current_value->vote_negative;
                    } else {
                        $vote_data['vote_negative'] = $current_value->vote_negative+1;

                        $results['positive'] = (int)$current_value->vote_positive;
                        $results['negative'] = $vote_data['vote_negative'];
                    }

                    $this->_wpdb->update($this->vote_table, $vote_data, array('post_id'=>$post_id), array('%d', '%d'), array('%d'));
                } else {
                    if($vote_value == 1) {
                        $vote_data['vote_positive'] = 1;
                        $vote_data['vote_negative'] = 0;

                        $results['positive'] = 1;
                        $results['negative'] = 0;
                    } else {
                        $results['positive'] = 0;
                        $results['negative'] = 1;
                    }
                    $this->_wpdb->insert($this->vote_table, $vote_data, array('%d', '%d', '%d') );
                }
                $data = array(
                    'success' => 'vote_approved',
                    'results' => $results
                );

            } else {
                $data = array(
                    'error' => 1,
                    'results' => 'user_already_voted'
                );
            }

        } catch (Exception $e) {
            return array(
            'error' => 1,
            'results'   => $e->getMessage(),
            );
        }

       return $data;

    }

    /*
     * Get Votes
     */
    public function get_votes($post_id = null) {

        $data = $this->_wpdb->get_row( $this->_wpdb->prepare("SELECT * FROM $this->vote_table WHERE post_id = $post_id;" ), OBJECT);

        return $data;
    }

    /*
     * Ajax callback
     */
    public function vote_ajax()
    {
        foreach($_POST as $key => $val) {
            $_POST[$key] = wp_filter_nohtml_kses(trim($val));
        }
        $data = $this->vote($_POST['post_id'], $_POST['value']);
        echo json_encode($data);
        exit;
    }


    public function debug() {

        echo "voting positive for post with id ???";
        varDump(array());

        echo "flushing results of last vote for post with id ???";
        varDump(array());

        echo "voting negative for post with id ???";
        varDump(array());

        echo "Get vote results of post with id ???";
        varDump(array());
    }


}