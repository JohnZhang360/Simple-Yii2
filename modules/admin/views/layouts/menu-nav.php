<?php
/* @var string $menuActive */
use zbsoft\helpers\Url;
?>
<div class="bd-example">
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a href="<?=Url::toRoute("default/index")?>" class="nav-link <?=$menuActive=="post"?"active":""?>">文章</a>
        </li>
        <li class="nav-item">
            <a href="<?=Url::toRoute("qiniu/index")?>" class="nav-link <?=$menuActive=="qiniu"?"active":""?>">七牛</a>
        </li>
        <li class="nav-item">
            <a href="<?=Url::toRoute("setting/index")?>" class="nav-link <?=$menuActive=="setting"?"active":""?>">设置</a>
        </li>
    </ul>
</div>