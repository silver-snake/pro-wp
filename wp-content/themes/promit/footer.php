        </section><!-- #content -->
        <footer id="footer">
            <div class="footer-main">
                <div class="wrapper">
                    <div class="social">
                        <div class="feed">
                            <div class="title">Twitter</div>
                            <div class="message"><?php flotheme_tweets(1);?><div class="arrow"></div></div>
                        </div>
                        <div class="links">
                            <?php if(flotheme_get_option('facebook')):?>
                            <div class="facebook"><a href="<?php flotheme_option('facebook');?>">Facebook</a></div>
                            <?php endif;?>
                            <?php if(flotheme_get_option('twitter')) :?>
                            <div class="twitter"><a href="<?php flotheme_option('twitter');?>">Twitter</a></div>
                            <?php endif;?>
                            <?php if(flotheme_get_option('youtube')):?>
                            <div class="youtube"><a href="<?php flotheme_option('youtube');?>"><span>You</span>tube</a></div>
                            <?php endif;?>
                        </div>
                    </div>

                    <div class="legenda">
                        <div class="title">Legenda</div>
                        <ul class="status">
                            <li class="progress active">
                                <div class="icon"></div>
                                <div class="desc">Promisiunea se afla in proces de implementare<div class="arrow"></div></div>
                            </li>
                            <li class="delim"></li>
                            <li class="success active">
                                <div class="icon"></div>
                                <div class="desc">Promisiunea indeplinita cu succes!<div class="arrow"></div></div>
                            </li>
                            <li class="delim"></li>
                            <li class="fail active">
                                <div class="icon"></div>
                                <div class="desc">Promisiunea este neindeplinita!<div class="arrow"></div></div>
                            </li>
                            <li class="delim"></li>
                            <li class="stopped active">
                                <div class="icon"></div>
                                <div class="desc">Promisiunea  este stopata!<div class="arrow"></div></div>
                            </li>
                            <li class="delim"></li>
                            <li class="superman active">
                                <div class="icon"><img src="<?php echo get_template_directory_uri();?>/images/superman.png" alt="superman" title="superman" /></div>
                                <div class="desc">Eroul nostru! <div class="arrow"></div></div>
                            </li>
                            <li class="delim"></li>
                            <li class="satir active">
                                <div class="icon"><img src="<?php echo get_template_directory_uri();?>/images/satir.png" alt="satir" title="satir" /></div>
                                <div class="desc">Nu se tine de promisiuni<div class="arrow"></div></div>
                            </li>
                            <li class="delim"></li>
                            <li class="baby active">
                                <div class="icon"><img src="<?php echo get_template_directory_uri();?>/images/baby.png" alt="satir" title="baby" /></div>
                                <div class="desc">Nou veniti!<div class="arrow"></div></div>
                            </li>
                            <li class="delim"></li>
                            <li class="cat active">
                                <div class="icon"><img src="<?php echo get_template_directory_uri();?>/images/cat.png" alt="satir" title="cat" /></div>
                                <div class="desc">Favoritul Publicului!<div class="arrow"></div></div>
                            </li>
                        </ul>

                        <div class="clearfix"></div>
                    </div>

                    <div class="partners">
                        <div class="title">Parteneri</div>
                        <div class="logos">
                            <a href="http://codd.md"><img src="<?php echo get_template_directory_uri();?>/images/oic.png" alt="Open Innovation Challenge" title="Open Innovation Challenge" /></a>
                        </div>
                    </div>

                </div><!--wrapper-->
            </div><!--footer-main-->
            <div class="footer-bottom wrapper">
                <nav id="footer-nav"><?php get_template_part('_menu');?></nav>
                <span class="copyright"><?php flotheme_option('copyright')?></span>

            </div>
        </footer>
    <?php wp_footer(); ?>
    <?php if (flotheme_get_option('tracking_code_enabled')) : ?>
        <?php flotheme_option('tracking_code');?>
    <?php endif; ?>
</body></html>