<?php
/* @var string $menuActive */
use zbsoft\helpers\Url;
?>
<div class="bd-example" data-example-id="">
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a href="<?=Url::toRoute("default/index")?>" class="nav-link <?=$menuActive=="post"?"active":""?>">文章</a>
        </li>
        <li class="nav-item">
            <a href="<?=Url::toRoute("search/index")?>" class="nav-link <?=$menuActive=="search"?"active":""?>">搜索</a>
        </li>
        <li class="nav-item">
            <a href="<?=Url::toRoute("qiniu/index")?>" class="nav-link <?=$menuActive=="qiniu"?"active":""?>">七牛</a>
        </li>
    </ul>
</div>