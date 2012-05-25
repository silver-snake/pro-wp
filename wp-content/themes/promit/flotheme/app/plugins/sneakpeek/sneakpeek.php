<?php
class Flotheme_Plugin_Sneakpeek
{
    protected $_wpdb;

    protected $_config;

    protected $_uploadDir;

    protected $_targetDir;
    
    protected $_adminTemplate;
    
    protected $_path;

    public function __construct()
    {
        global $wpdb;
        
        $this->_wpdb = $wpdb;

        $this->_config = Flotheme_Config::get();

        $this->_uploadDir = wp_upload_dir();

        $this->_targetDir = $this->_uploadDir['basedir'] . '/sneak_peek';
        
        $this->_path = realpath(dirname(__FILE__));
        
        $this->_url = FLOTHEME_ROOT_URL . '/app/plugins/sneakpeek';
        
        $this->_adminTemplate = $this->_path . '/template_sneakpeek_admin.php';
    }
    
    public function init()
    {
        if (is_admin()) {
            add_action('admin_menu', array($this, 'adminAddPage'));
            add_action('admin_init', array($this, 'adminStyles'));
            add_action('wp_ajax_flotheme_sneakpeek', array($this, 'ajax'));
            add_action('wp_ajax_nopriv_flotheme_sneakpeek', array($this, 'ajax'));
        }
    }
    
    /**
     * Callback to add admin styles
     */
    public function adminStyles()
    {
        wp_enqueue_style('flotheme_admin_sneakpeek_css', $this->_url . '/admin.css', array(), FLOTHEME_THEME_VERSION);
    }
    
    /**
     * Callback to add page
     */
    public function adminAddPage()
    {
        add_submenu_page('flotheme', 'Sneak Peek', 'Sneak Peek', 'administrator', 'flotheme_sneakpeek', array($this, 'adminPage'));
    }
    
    /**
     * Page HTML Callback
     */
    public function adminPage()
    {
        if (!isset($_REQUEST['updated'])) {
            $_REQUEST['updated'] = false;
        }
        
        echo Flotheme_Renderer::render($this->_adminTemplate, array(
            'images'    => $this->getImages(),
        ));
    }

    /**
     * AJAX images sorting.
     * 
     * @param array $images
     * @return array
     */
    public function sort($images)
    {
        if (count($images)) {
            foreach ($images as $k => $id) {
                $id = (int) $id;
                $key = $k + 1;
                $this->_wpdb->query('UPDATE ' . $this->_wpdb->posts . ' SET menu_order=' . $key . ' WHERE ID=' . $id);
            }
        }

        return array(
            'success'   => 1,
        );
    }
    
    /**
     * AJAX save image data
     * 
     * @param array $titles
     * @param array $descriptions
     * @return array 
     */
    public function save($titles, $descriptions)
    {

        foreach ($titles as $id => $title) {

            $id = (int) $id;

            $description = $descriptions[$id];

            $title = wp_filter_nohtml_kses($title);
            $description = wp_filter_nohtml_kses($description);

            $this->_wpdb->query('UPDATE ' . $this->_wpdb->posts . ' SET post_title="' . $title . '", post_content="' . $description . '" WHERE ID=' . $id);
        }

        return array(
            'success'   => 1,
        );
    }

    /**
     * AJAX image upload
     * 
     * @return array
     */
    public function upload()
    {
        if (!empty($_FILES)) {
            try {
                $tempFile = $_FILES['Filedata']['tmp_name'];
                $filename = $_FILES['Filedata']['name'];

                $targetFile = $this->_targetDir . '/' . $filename;
                if (!is_dir($this->_targetDir)) {
                    @mkdir($this->_targetDir, 0777);
                }

                if (!is_dir($this->_targetDir)) {
                    throw new Exception('Directory was not created. Please check permissions on your <strong>uploads</strong> folder');
                }

                move_uploaded_file($tempFile, $targetFile);

                $resizedFilename = md5($filename . time()) . '.jpg';//preg_replace('~(\.([\w]{2,4}))$~si', '_r$1', $filename);

                if (!$this->_resize($targetFile, $this->_targetDir . '/' . $resizedFilename)) {
                    throw new Exception('Can\'t resize image. Please try again.');
                }

                @unlink($targetFile);

                $maxMenuOrder = (int) $this->_wpdb->get_var('SELECT MAX(menu_order) from ' . $this->_wpdb->posts . ' WHERE post_type="sneakpeek"');

                $image = array(
                    'post_type' => 'sneakpeek',
                    'post_title' => $resizedFilename,
                    'post_content' => '',
                    'post_status' => 'inherit',
                    'post_author' => 1,
                    'guid'          => $this->_uploadDir['baseurl'] . '/sneak_peek/' . $resizedFilename,
                    'menu_order'    => $maxMenuOrder + 1,
                );

                $imageId = wp_insert_post($image);

                return array(
                    'success'   => 1,
                    'url'       => $image['guid'],
                    'id'        => $imageId,
                );
            } catch (Exception $e) {
                return array(
                    'error' => 1,
                    'msg'   => $e->getMessage(),
                );
            }
        }
    }

    /**
     * 
     * 
     * @param type $id
     * @return type 
     */
    public function delete($id)
    {
        $id = (int) $id;
        $post = get_post($id);
        if ($post->ID) {
            $targetFile = $this->_targetDir . '/' . $post->post_title;
            if (is_file($targetFile)) {
                @unlink($targetFile);
            }
            $this->_wpdb->query('DELETE FROM ' . $this->_wpdb->posts . ' WHERE id=' . $post->ID . ' AND post_type="sneakpeek"');
        }
        return array(
            'success'   => 1,
        );
    }

    public function generateHtml()
    {
        return $this->_generateHtml();
    }

    protected function _generateHtml()
    {
        $args = array(
            'images'    => get_posts(array(
                'post_type'   => 'sneakpeek',
                'orderby'     => 'menu_order',
                'order'       => 'ASC',
                'post_status' => 'inerhit',
                'numberposts' => -1,
            )),
        );

        $html = $this->_template->render(FLOTHEME_TEMPLATES . '/sneakpeek_admin_template.php', $args);

        return $html;
    }

    protected function _resize($src, $target)
    {
        $newWidth = $this->_config['sneakpeek']['width'];
        $newHeight = $this->_config['sneakpeek']['height'];

        $str = file_get_contents($src);
        $source = imagecreatefromstring($str);
        $destination = imagecreatetruecolor( $newWidth, $newHeight );

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
            imagejpeg($destination, $target, 100);
            return true;
        } else {
            return false;
        }
    }

    public function ajax()
    {
        switch ($_REQUEST['act']) {
            case 'upload':
                $uploaded = $this->upload();
                echo json_encode($uploaded);
                break;
            case 'sort':
                if ($_POST['sneak-photo']) {
                    $sorted = $this->sort($_POST['sneak-photo']);
                    echo json_encode($sorted);
                }
                break;
            case 'save':
                if (count($_POST['title'])) {
                    $saved = $this->save($_POST['title'], $_POST['description']);
                    echo json_encode($saved);
                }
                break;
            case 'delete':
                if ($_POST['image_id']) {
                    $deleted = $this->delete($_POST['image_id']);
                    echo json_encode($deleted);
                }
                break;
        }
        
        exit;
    }
    
    
    public function getImages()
    {
        return get_posts(array(
            'post_type'   => 'sneakpeek',
            'orderby'     => 'menu_order',
            'order'       => 'ASC',
            'post_status' => 'inerhit',
            'numberposts' => -1,
        ));
    }
}

# show sneak peek
function flotheme_sneak_peek() {
    $images = get_posts(array(
        'post_type'   => 'sneakpeek',
        'orderby'     => 'menu_order',
        'order'       => 'ASC',
        'post_status' => 'inerhit',
        'numberposts' => -1,
    ));
    ?>
    <div class="wrap">
        <ul>
            <?php foreach ($images as $image):?>
                <li><img src="<?php echo $image->guid?>" alt="" title="<?php echo $image->post_title?>" /></li>
            <?php endforeach;?>
        </ul>
    </div>
    <?php
}