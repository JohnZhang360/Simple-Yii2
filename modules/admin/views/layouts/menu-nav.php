<?php
use zbsoft\helpers\Url;
?>
<div class="bd-example" data-example-id="">
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a href="<?=Url::toRoute("default/post")?>" class="nav-link active">文章</a>
        </li>
        <li class="nav-item">
            <a href="<?=Url::toRoute("search/index")?>" class="nav-link">搜索</a>
        </li>
    </ul>
</div>