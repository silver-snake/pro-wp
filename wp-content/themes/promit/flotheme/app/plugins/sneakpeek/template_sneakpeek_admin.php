<div class="wrap flotheme" id="flotheme-sneak">
    <h2>Sneak Peek</h2>
    <div id="flotheme-sneak-error" class="error"><p></p></div>
    <form id="flotheme-sneak-form" enctype="multipart/form-data" method="post" action="">
        <?php wp_nonce_field('flotheme_sneakpeek', '_wp_nonce_sneakpeek')?>
        <input type="file" name="upload" id="sneak-upload" />
    </form>
    <form id="flotheme-sneak-photos-form" method="post" action="">
        <div id="flotheme-sneak-photos">
            <ul>
                <?php foreach ($images as $image) : ?>
                    <li id="sneak-photo-<?php echo $image->ID ?>">
                        <img src="<?php echo $image->guid?>" alt="" />
                        <div class="content">
                            <label>Title</label>
                            <input name="title[<?php echo $image->ID ?>]" type="text" value="<?php echo $image->post_title ?>" />
                            <label>Description</label>
                            <textarea name="description[<?php echo $image->ID ?>]"><?php echo $image->post_content?></textarea>
                        </div>
                        <div class="clear"></div>
                        <a href="#" class="delete" rel="<?php echo $image->ID?>">delete</a>
                    </li>
                <?php endforeach; ?>
            </ul>
            <input type="button" id="flotheme-sneak-photos-save" value="Save Changes" class="button-primary" />
        </div>
    </form>
</div>
<script type="text/javascript">
    jQuery(function($){
        var photosContainer = $('#flotheme-sneak-photos');
        var photosContainerUl = photosContainer.find('ul');

        var error = $('#flotheme-sneak-error');
        var errorP = error.find('p');

        photosContainerUl.sortable({
            placeholder: "highlight",
            stop:function() {
                var serialized = $(this).sortable('serialize');
                serialized += '&act=sort&action=flotheme_sneakpeek';
                $.post(ajaxurl, serialized, function(){

                });
            }
        });

        $('#flotheme-sneak-photos-save').click(function(){
            var form = $(this).parents('form');
            var values = form.serialize();

            $.post(ajaxurl, values + '&act=save&action=flotheme_sneakpeek', function(data){
                if (data.success) {
                    photosContainerUl.highlight();
                }
            }, 'json');
        });

        photosContainerUl.find('li a.delete').live('click', function(e){
            e.preventDefault();
            if (confirm('Are you sure?'))
            var fn = $(this);
            $.post(ajaxurl, {
                act:'delete',
                action:'flotheme_sneakpeek',
                image_id:fn.attr('rel')
            }, function(){
                fn.parent().fadeOut('fast', function(){
                    $(this).remove();
                    photosContainerUl.highlight();
                });
            });
        });
        
        $('#sneak-upload').uploadify({
            'uploader'  : '<?php echo FLOTHEME_3RDPARTY_URL?>/uploadify/uploadify.swf',
            'cancelImg' : '<?php echo FLOTHEME_3RDPARTY_URL?>/uploadify/cancel.png',
            'auto'      : true,
            'script'    : ajaxurl,
            'multi'     : true,
            'scriptData': {
                'act'   :'upload',
                'action':'flotheme_sneakpeek',
                'nonce':$('#_wp_nonce_sneakpeek').val()
            },
            'onAllComplete':function(){

            },
            'onError': function (){
                
            },
            'onComplete':function(event, id, file_obj, response, data)
            {
                response = eval('(' + response + ')');
                if (response.error == 1) {
                    errorP.html(response.msg);
                    error.fadeIn('fast');

                    setTimeout(function(){
                        error.fadeOut('fast');
                        errorP.text('');
                    }, 20000);

                    return;
                }
                var html = '<li id="sneak-photo-' + response.id + '"><img src="' + response.url + '" alt="" /><div class="content"><label>Title</label><input type="text" value="' + response.title + '" name="title[' + response.id +  ']" /><label>Description</label><textarea name="description[' + response.id +  ']">' + response.title + '</textarea></div><a href="#" class="delete" rel="' + response.id + '">delete</a></li>';
                photosContainerUl.append(html);
                photosContainerUl.highlight();
            }
        });

        $.fn.highlight = function()
        {
            $(this).animate({backgroundColor:'#FFFABF'}, 300)
                   .animate({backgroundColor:'#FFFFFF'}, 300)
        }
    });
</script>