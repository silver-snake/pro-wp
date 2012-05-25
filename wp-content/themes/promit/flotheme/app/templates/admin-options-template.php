<div id="flotheme" class="theme-options wrap flotheme">
    <h2>Theme Options</h2>
    <div class="sidebar" style="width:280px;margin-right:20px;float:right;">
        <div class="box postbox">
            <h3>Theme</h3>
            <div class="content">
                <ul class="list">
                    <?php $theme_data = get_theme_data(get_stylesheet_uri()); ?>
                    <li>Name: <strong><?php echo $theme_data['Name']?></strong></li>
                    <li>Version: <?php echo $theme_data['Version']?></li>
                    <li>Folder: <code>/themes/<?php echo get_template(); ?></code></li>
                </ul>
            </div>
        </div>
        <div class="box postbox">
            <h3>About</h3>
            <div class="content">
                <ul class="list">
                    <li><?php echo $theme_data['Description']?></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="options" style="margin-right:320px;">
        <form id="options" method="post" action="<?php echo admin_url('/admin-ajax.php')?>" enctype="multipart/form-data">
            <?php $first = true;?>
            <?php foreach ($config['boxes'] as $settingsKey => $settings) : ?>
                <?php
                    $options = array();
                    foreach ($settings['options'] as $opt) {
                        $options[$opt] = $config['fields'][$opt];
                    }
                ?>
                <div class="box postbox collapsible <?php echo $first ? '' : 'collapsed'; $first = false; ?> flotheme-box-<?php echo $settingsKey?>">
                    <h3><?php echo $settings['label']; ?></h3>
                    <div class="content">
                        <?php foreach($options as $opt => $box):?>
                            <?php echo $control->render($opt, $box, $values[$opt]);?>
                        <?php endforeach;?>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php settings_fields('template-parameters'); ?>
            <p class="submit">
                <input type="submit" value="Save changes" class="button-primary"/>
                <span class="loading"></span>
            </p>
        </form>
        <div id="options-message"></div>
    </div>
</div>
<script type="text/javascript">
jQuery(function($){
    var flotheme_toggle_box = function(val, box)
    {
        if (val == 1) {
            box.slideDown(400);
        } else {
            box.slideUp(400);
        }
    }
    
    <?php foreach ($config['boxes_toggle'] as $field => $box):?>
        $('#flothemes_option_<?php echo $field?>').change(function(){
            flotheme_toggle_box($(this).val(), $('#options div.flotheme-box-<?php echo $box?>'));
        }).trigger('change');
    <?php endforeach;?>


    $('#options a.delete').live('click', function(e){
        e.preventDefault();
        var fn = $(this);
        if (!confirm('Are you sure?')) {
            return;
        }

        var parent = fn.parent();

        $.get(ajaxurl, {
            'action':'flotheme_delete_image',
            'image':fn.attr('rel')
        }, function(data){
            data = eval('(' + data + ')');
            if (data.error) {
                
            } else {
                parent.html(data.html);
            }
            flotheme_upload_handler(parent.find('input[type=file]'));
        });
    });

    var flotheme_upload_handler = function(file)
    {
        file.uploadify({
            'uploader'  : '<?php echo FLOTHEME_3RDPARTY_URL?>/uploadify/uploadify.swf',
            'script'    : '<?php echo FLOTHEME_3RDPARTY_URL?>/uploadify/uploadify.php',
            'cancelImg' : '<?php echo FLOTHEME_3RDPARTY_URL?>/uploadify/cancel.png',
            'auto'      : true,
            'script'    : ajaxurl,
            'multi'     : false,
            'buttonText': 'Select File',
            'scriptData': {
                'action' :'flotheme_upload_image',
                'image' : file.attr('title')
            },
            'onError': function () {
                
            },
            'onComplete':function(event, id, file_obj, response, data)
            {
                response = eval('(' + response + ')');
                
                if (response.error) {
                    alert(response.message);
                    return;
                } else {
                    var parent = file.parent();
                    parent.html(response.html);
                }
            }
        });
    }
    
    $('#options input[type=file]').each(function(){
        flotheme_upload_handler($(this));
    });
    
    var message = $('#options-message');
    var options_submit_loader = $('#options span.loading');
    var options_submit = $('#options input[type=submit]');
    $('#options').ajaxForm({
        'beforeSubmit':function(){
            options_submit_loader.show();
            options_submit.removeClass('button-primary').addClass('button-disabled');
            message.hide().removeClass('error').removeClass('error');
        },
        'success':function(response){
            options_submit_loader.hide();
            options_submit.removeClass('button-disabled').addClass('button-primary');
            if (response.error) {
                message.addClass('error').text('Some errors occured. Please, reload the page and try again.');
            } else {
                message.addClass('updated').text('Options saved successfully.');
            }
            message.slideDown('fast');
        },
        'data':{
            'action':'flotheme_options_save'
        },
        'dataType':'json'
    });
});
</script>