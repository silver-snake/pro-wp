$(function(){
    var _post = $('#post');
    var _posts = $('article.post');
    var _is_mobile = parseInt(flotheme.is_mobile);
    
    

    // open external links in new window
    $("a[rel=external]").each(function(){
        $(this).attr('target', '_blank');
    });
    
    // animate navigation
    $('header nav ul').superfish({
        'hoverClass':'hover',
        'delay':100,
        'autoArrows':false,
        'dropShadows':false
    }).find('a[href=#]').click(function(e){
        e.preventDefault();
    });
    
    // launch sneak peek
    $('#sneak-peek').dxSlider();
    
    $.fn.initPosts = function(ajax_loaded)
    {
        if (typeof(ajax_loaded) == 'undefined') {
            ajax_loaded = false;
        }
        
        var _validate_options = {
            rules:{
                author:{required:true},
                email:{required:true, email:true},
                comment:{required:true}
            }
        }
        
        var _add_comment_observer = function(e)
        {
            e.preventDefault();
            var fn = $(this);
            var form = fn.parents('.post').find('div.comment-form');
            if (!form.length) {
                return;
            }
            form.scrollTo('fast')
                .find('input[type=text], textarea').filter(':first').focus();
        }

        var _open_post = function(e, _opts) {
            e.preventDefault();
            var data  = {
                fn:$(this),
                scroll:false
            };
            var opts = $.extend({
                comments:false,
                hide:false,
                add_comment:false
            }, _opts);

            data.post = data.fn.parents('.post');
            data.more = data.post.find('.more');

            if (opts.comments || opts.add_comment) {
                data.scroll = $('.share', data.post);
                _init_post(data);
                return;
            }

            if (data.more.is(':visible')) {
                if (opts.hide == true) {
                    data.more.hide();
                } else {
                    data.more.slideUp(400);
                }
                data.fn.text('Open post');
                data.post.removeClass('open-post');
            } else {
                if (typeof(e.originalEvent) != 'undefined' ) {
                    data.scroll = data.post;
                }
                _init_post(data);
            }
        }

        // init open post: show full content, slide to comments, etc.
        var _init_post = function (data) {
            // close other posts
            data.post.siblings('.open-post').find('a.toggle').trigger('click', {
                hide:true
            });

            var loading = data.post.find('span.loading');

            if (data.more.text() == '') {
                data.post.addClass('post-loading');

                loading.css('visibility', 'visible');

                data.more.load(flotheme.ajax_load_url, {
                    'action':'flotheme_load_post',
                    'id':data.post.attr('id').replace('post-', '')
                }, function(){
                    loading.remove();;
                    data.more.slideDown(400, function(){
                        data.post.addClass('open-post');
                        data.fn.text('Close post');
                        if (data.scroll) {
                            data.scroll.scrollTo('fast');
                        }
                    });
                    data.more.find('div.actions a.leave-comment').click(_add_comment_observer);
                    if (flotheme.ajax_comments) {
                        data.more.find('div.comment-form form').validate(_validate_options);
                    }
                });

            } else {
                data.more.slideDown(400, function(){
                    data.post.addClass('open-post');
                    data.fn.text('Close post');
                    if (data.scroll) {
                        data.scroll.scrollTo('fast');
                    }
                });
            }
        }
        
        $(this).each(function(){
            var post = $(this);

            // init gallery
            $('div.preview div.gallery', post).dxSlider();

            if (!_is_mobile ) {
                $('div.actions a.leave-comment', post).click(_add_comment_observer);

                // ajax open posts
                if (flotheme.ajax_posts) {
                    $('a.toggle', post).click(_open_post);
                    $('.preview .image', post).click(function(){
                        $(this).parents('div.post').find('a.toggle').trigger('click')
                    });
                }

                if (flotheme.ajax_comments) {
                    _validate_options.submitHandler = function(_form)
                    {
                        var form = $(_form);
                        var comments = post.find('div.comments');

                        var submitContainer = form.find('div.submit');
                        var messages = post.find('div.messages');

                        form.ajaxSubmit({
                            beforeSubmit:function(){
                                submitContainer.hide();
                            },
                            success:function(){
                                form.clearForm();
                                submitContainer.fadeIn();
                                comments.find('ol').load(flotheme.ajax_load_url, {
                                    'id':form.find('input[name=comment_post_ID]').val(),
                                    'action':'flotheme_load_comments'
                                }, function(){
                                    messages.hide().html('Thank you, kindly.  We love hearing what you have to say!').slideDown('fast');
                                    setTimeout(function(){
                                        messages.slideUp('fast');
                                    }, 10000);
                                });
                            },
                            error:function(request){
                                var error = request.responseText.match(/<p>([^<]+)<\/p>/)[1];
                                messages.hide().html(error).slideDown('fast');
                                submitContainer.show();
                            }
                        });
                    }
                }
                $('div.comment-form form', post).each(function(key, form){
                    $(form).validate(_validate_options);
                });
            }

            if (!ajax_loaded && flotheme.ajax_open_single && !_post.length && _posts.length == 1) {
                post.find('a.toggle').trigger('click');
            }
        });
    }
    _posts.initPosts();


    var load_more_stopper = false;
    var _load_more_posts_observer = function(e)
    {
        e.preventDefault();
        if (load_more_stopper) {
            return;
        }
        load_more_stopper = true;
        var fn = $(this);
        var load_params_input = $('#load-more-params');
        var params = load_params_input.val();
        
        // close open posts
        _posts.filter('.open-post').find('a.toggle').trigger('click');

        var container = $('<div></div>').addClass('container').hide();
        $('#posts').append(container);

        fn.addClass('loading');
        $.get(flotheme.ajax_load_url, params + '&action=flotheme_load_more_posts', function(data){
            data = eval('(' + data + ')');
            container.html(data.html).slideDown('fast', function(){
                fn.removeClass('loading');
                load_more_stopper = false;
            }).scrollTo('fast');

            container.find('div.post').initPosts(true)

            delete data.html;

            var string = '';
            $.each(data, function(k, v) {
                string += k + '=' + v + '&';
            });
            load_params_input.val(string);
            
            if (data.nextpage == -1) {
                fn.parent().fadeOut('fast', function(){
                    $(this).remove();
                })
            }
        });
    }

    if (flotheme.ajax_posts && !_is_mobile) {
        $('#load-more a').click(_load_more_posts_observer);
    }


    $.fn.initContact = function()
    {
        $(this).each(function(){
            var fn = $(this);

            var form = fn.find('form');
            var messages = fn.find('div.messages');

            form.validate({
                submitHandler: function(_form) {
                    $.post(flotheme.ajax_load_url, form.serialize() + '&action=flotheme_submit_contact', function(data){
                        if (data.error) {
                            alert(data.msg);
                            return;
                        }
                        form.clearForm();
                        messages.hide().text(data.msg).slideDown('fast');
                    }, 'json');

                }
            });
        });
    }

    $('div.page div.contact').initContact();

    $.fn.initVote = function() {
        $(this).each(function(){
            var fn = $(this);

            var post_id = fn.prop('id').replace('post-', '');

            var vote_up = fn.find('.vote-up a');
            var vote_down = fn.find('.vote-down a');

            var vote = function(e) {
                e.stopPropagation();

                var value;
                var parent = $(this).parent().parent();
                if(parent.hasClass('vote-up')) {
                    value = '1';
                }
                else if (parent.hasClass('vote-down')) {
                    value = '2';
                }

                function animateVote(val, obj){
                    var main, second, main_res, second_res, top, left;

                    if(val=='1'){
                        main = vote_up;
                        second = vote_down;

                        main_res = '+'+obj.results.positive;
                        second_res = '-'+obj.results.negative;

                        top = '-100px';
                        left = '0px';

                    } else if(val=='2') {
                        main = vote_down;
                        second = vote_up;

                        main_res = '-'+obj.results.negative;
                        second_res = '+'+obj.results.positive;

                        top =  '-100px';
                        left = '38px';
                    }

                    main.find('.thumb')
                        .animate({
                            top: top,
                            left: left,
                            opacity : 0

                        }, 400, function(){
//                            $(this).fadeOut(1000, function() {
//                            });
                            main.parent().find('.result').text(main_res);
                            second.find('.thumb').fadeOut(300, function(){
                                second.find('.result').text(second_res);
                            });

                        });
                }

                $.post(flotheme.ajax_load_url, '&post_id='+post_id+'&value='+value+'&action=flotheme_submit_vote', function(data){

                    if (data.error) {
                        // TODO: Error handler
                        alert(data.results);
                        return;
                    }

                    animateVote(value, data);

                    fn.find('.vote').addClass('voted');

                    //console.log(data);
                }, 'json');
            }

           vote_up.find('.thumb').click(vote);
           vote_down.find('.thumb').click(vote);



        });
    }
    $('.post').initVote();

    // Init sneak peek
    $('#slider').flexslider({
        animation: "slide",
        controlNav: false,
        animationLoop: true,
        slideshow: true,
        start: function() {
            var slide = $('#slider li img').eq(1);

            slide.click( function(e){
                $(document).click(function(){
                    // hide drop down clicking anywhere on page:
                    $("#insert-promise").slideUp(400, function() {
                        $(this).hide();
                    });
                });

                $('#insert-promise').fadeIn();
                e.stopPropagation();
            }).css({cursor: 'pointer'});
        }

    });

    // Init sidebar sliders
    $('.side-slider').each(function(){
        $(this).flexslider({
            animation: "slide",
            controlNav: false,
            animationLoop: true,
            slideshow: false
        });
    });

    // Add promise form
    $.fn.initPromiseForm = function() {

        var form = $(this);

        form.click( function(e) {
            e.stopPropagation();
        });

        form.find("input.date").datepicker({
            beforeShow: function(input, inst) {
                $(inst).click( function(e) {
                    e.stopPropagation();
                });
            }
        });

        form.find('.ui-datepicker')
            .css({position: 'fixed'})
            .click( function(e) {
                e.stopPropagation();
            });

        form.find("#who").autocomplete({
            source: function( request, response ) {
                $.ajax({
                    url: flotheme.ajax_load_url+"?action=flotheme_load_politics",
                    dataType: "jsonp",
                    data: {
                        limit: 10,
                        q: request.term
                    },
                    success: function( data ) {

                        $('.ui-autocomplete')
                            .css({position: 'fixed'})
                            .click( function(e) {
                                e.stopPropagation();
                            });
                        response( $.map( data, function( item ) {
                            return {
                                label: item,
                                value: item
                            }
                        }));

                    }
                });
            },
            minLength: 2,
        });

    }
    $('#insert-promise').initPromiseForm();


    // Subscribe
    $('.xrange .follow').click(function(){
        alert('Adauga email-ul pentru a urmari promisiunea');
    });


    // !! REMOVE THIS !!
    $('#insert-promise .button').click(function(){
        $("#insert-promise").slideUp(400, function() {
            $(this).hide();
        });
    });


});
