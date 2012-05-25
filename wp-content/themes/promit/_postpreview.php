<div class="preview-wrapper">
    <div class="bottom"></div>
    <div class="preview <?php echo has_post_thumbnail() || has_post_format('gallery') ? '' : 'no-image'?>">

            <div class="politician">
                <?php $politician =  pro_get_politician();?>
                <?php if(has_post_thumbnail($politician->ID)): ?>
                <figure>
                    <?php echo get_the_post_thumbnail($politician->ID, 'politician-thumb');?>
                    <div class="mask"></div>
                </figure>
                <?php endif;?>
                <h4 class="name"><?php echo $politician->post_title;?></h4>
                <?php $party = pro_get_party($politician->ID);?>
                <div class="party">
                    <?php if(has_post_thumbnail($party->ID)):?>
                        <figure>
                            <?php echo get_the_post_thumbnail($party->ID, 'party-thumb');?>
                        </figure>
                    <?php endif;?>
                    <div>Membru partidului</div>
                    <div><?php echo $party->post_title;?></div>
                    <div class="vote">
                        <?php $id = get_the_ID();
                            $voted = pro_user_voted($id);

                            if($voted) {
                                $votes = pro_get_votes($id);

                            }
                        ?>
                        <span class="vote-up">
                            <a href="javascript:void(0);">
                                <?php if(!$voted):?>
                                <div class="thumb"></div>
                                <?php endif;?>
                                <div class="result"><?php if($voted) echo "+" . $votes->vote_positive;?></div>
                            </a>
                        </span>
                        <span class="vote-down">
                            <a href="javascript:void(0);">
                                <?php if(!$voted):?>
                                <div class="thumb"></div>
                                <?php endif;?>
                                <div class="result"><?php if($voted) echo "-" . $votes->vote_negative;?></div>
                            </a>
                        </span>
                    </div>
                </div>
            </div>
            <div class="preview-content">
                <div class="excerpt">
                    <span class="the-excerpt"><?php echo get_the_excerpt();?></span>
                    <span class="meta">
                        <a href="<?php comments_link(); ?>" class="comments"><?php comments_number('0','1','%'); ?></a>
                        <div class="comment-arrow"></div>
                    </span>
                    <div class="arrow"></div>
                </div>

                <div class="xrange">
                    <div class="range-area">
                        <?php $rand = round(mt_rand(1,80));?>
                        <div class="point" style="left: <?php echo $percent = ($rand<3) ? 3 : $rand;  ?>%"><?php echo $percent ?></div>
                        <div class="range-mask" style="width: <?php echo 100-(int)$percent;?>%"></div>
                    </div>
                    <div class="follow"></div>
                </div>
                <div class="remained">zile ramase</div>

                <ul class="status">
                    <li class="progress"><div class="icon"></div><div class="text">In proces</div></li>
                    <li class="delim"></li>
                    <li class="success active"><div class="icon"></div><div class="text">Succes</div></li>
                    <li class="delim"></li>
                    <li class="fail"><div class="icon"></div><div class="text">Neindeplinit</div></li>
                    <li class="delim"></li>
                    <li class="stopped"><div class="icon"></div><div class="text">Stopata</div></li>
                </ul>

                <?php $source = get_post_meta(get_the_ID(), 'proof', true);?>
                <?php if($source):?>
                <div class="source"><a href="<?php echo $source;?>">Sursa promisiunei</a></div>
                <?php endif;?>
                <div class="share">
                    <?php flotheme_share('tweet');?>
                    <?php flotheme_share('plus1');?>
                    <div class="fb-like" data-href="<?php flotheme_share('fb');?>" data-send="false" data-layout="button_count" data-width="54" data-show-faces="false"></div>

                </div>
            </div>

    </div>
</div>