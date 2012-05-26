<ul class="tabs">
    <li class="total<?php if(is_home()) echo' active';?>"><a href="<?php echo site_url('/');?>">Vezi toate</a></li>
    <li class="in-progress<?php if(is_category('in-progress')) echo' active';?>"><a href="<?php echo site_url('/category/in-progress');?>">In Progres</a></li>
    <li class="success<?php if(is_category('successful')) echo' active';?>"><a href="<?php echo site_url('/category/successful');?>">Indeplinit</a></li>
    <li class="fail<?php if(is_category('unsuccessful')) echo' active';?>"><a href="<?php echo site_url('/category/unsuccessful');?>">Neindeplinit</a></li>
    <li class="hero">Erou</li>
    <li class="tabs-shadow"></li>
</ul>