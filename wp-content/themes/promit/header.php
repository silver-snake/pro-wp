<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <meta http-equiv="X-UA-Compatible" content="IE=9; IE=8" />
    <meta http-equiv="Page-Enter" content="blendTrans(Duration=0.1)" />
    <meta http-equiv="Page-Exit" content="blendTrans(Duration=0.1)" />
    <title><?php
        global $page, $paged;
        wp_title( '|', true, 'right' );
        bloginfo( 'name' );
        $site_description = get_bloginfo( 'description', 'display' );
        if ( $site_description && ( is_home() || is_front_page() ) )
            echo " | $site_description";
        if ( $paged >= 2 || $page >= 2 )
            echo ' | ' . sprintf('Page %s', max( $paged, $page ));
    ?></title>
    <link rel="profile" href="http://gmpg.org/xfn/11" />
    <link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo get_template_directory_uri(); ?>/favicon.ico" />
    <?php wp_head();?>
    <script src="https://platform.twitter.com/widgets.js" type="text/javascript"></script>

    <script type="text/javascript">
        (function() {
            var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
            po.src = 'https://apis.google.com/js/plusone.js';
            var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
        })();
    </script>


    <!--[if lt IE 8]>
        <link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/ie7.css" />
    <![endif]-->
    <!--[if lt IE 9]>
        <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
</head>
<body>
<div id="fb-root"></div>
<script>
    window.fbAsyncInit = function() {
        FB.init({
            appId      : '458550207493231', // App ID
            channelUrl : '', // Channel File
            status     : true, // check login status
            cookie     : true, // enable cookies to allow the server to access the session
            xfbml      : true  // parse XFBML
        });

        // Additional initialization code here
    };

    // Load the SDK Asynchronously
    (function(d){
        var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
        if (d.getElementById(id)) {return;}
        js = d.createElement('script'); js.id = id; js.async = true;
        js.src = "//connect.facebook.net/en_US/all.js";
        ref.parentNode.insertBefore(js, ref);
    }(document));
</script>
    <div id="wrapper" class="wrapper">
        <!--[if lt IE 8]><p class="chromeframe">Your browser is <em>ancient!</em> <a href="http://browsehappy.com/">Upgrade to a different browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to experience this site.</p><![endif]-->
        <a name="top" id="top"></a>
        <div class="top">
            <ul>
                <li class="first"></li>
                <li class="second"></li>
                <li class="third"></li>
                <li class="fourth"></li>
                <li class="fifth"></li>
            </ul>
        </div>
    </div>
        <header id="header">
           <!--div class="search"><?php get_search_form(); ?></div-->
            <div class="logo">
                <a href="<?php echo site_url('/');?>"><img src="<?php echo get_template_directory_uri();?>/images/logo.png" alt="promit.md" title="promit.md" /></a>
            </div>
            <nav id="nav">
                <?php get_template_part('_menu');?>
            </nav>

            <section id="slider" class="flexslider">
                <ul class="slides">
                    <li>
                        <img src="<?php echo get_template_directory_uri();?>/images/slide1.png" alt="Slide 1" title="Slide 1"/>
                    </li>
                    <li>
                        <img src="<?php echo get_template_directory_uri();?>/images/slide2.png" alt="Slide 2" title="Slide 2"/>
                    </li>
                    <li>
                        <img src="<?php echo get_template_directory_uri();?>/images/slide3.png" alt="Slide 3" title="Slide 3"/>
                    </li>
                </ul>
            </section>

            <div class="clearfix"></div>
        </header>
        <?php get_template_part('_addform');?>
        <section id="content">