<?php
/* @var string $active */
use zbsoft\helpers\Url;
?>
<ul class="nav nav-pills clearfix sub-nav">
    <li class="nav-item">
        <a href="<?=Url::toRoute("default/index")?>" class="nav-link <?=$active=="list"?"active":""?>">列表</a>
    </li>
    <li class="nav-item">
        <a href="<?=Url::toRoute("tags/index")?>" class="nav-link <?=$active=="tags"?"active":""?>">标签</a>
    </li>
</ul>