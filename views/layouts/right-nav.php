<?php
use \zbsoft\helpers\Url;
use app\models\Tags;

$monthArchives = Zb::$app->cache->get("monthArchives");
$tagsList = Tags::find()->all();
?>
<div class="col-sm-3 offset-sm-1 blog-sidebar">
    <div class="sidebar-module sidebar-module-inset">
        <h4>About</h4>
        <p><?=$this->context->sysConfig["about"]?></p>
    </div>
    <div class="sidebar-module">
        <h4>Search</h4>
        <form class="form-inline navbar-form" action="<?=Url::toRoute("site/index")?>">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Search for..." name="search" value="<?= Zb::$app->request->getQueryParam("search") ?>">
                <span class="input-group-btn">
                    <button class="btn btn-secondary" type="submit">Go!</button>
                </span>
            </div>
        </form>
    </div>
    <?php if(isset($monthArchives) && !empty($monthArchives)){?>
        <div class="sidebar-module">
            <h4>Archives</h4>
            <ol class="list-unstyled">
                <?php foreach ($monthArchives as $val) { ?>
                    <li><a href="javascript:void(0)" class="list-archives"><?= $val ?></a></li>
                <?php } ?>
            </ol>
        </div>
    <?php }?>
    <?php if(!empty($tagsList)){?>
        <div class="sidebar-module">
            <h4>Tags</h4>
            <?php foreach ($tagsList as $tags) { ?>
                <span class="label label-default"><?= $tags->tag_name ?></span>
            <?php } ?>
        </div>
    <?php }?>
    <div class="sidebar-module">
        <h4>Elsewhere</h4>
        <ol class="list-unstyled">
            <li><a href="https://github.com/guang-zhang">GitHub</a></li>
            <li><a href="mailto:johnzhangbkb@gmail.com">Gmail</a></li>
            <li><a href="http://stackoverflow.com/users/6775018/john-zhang">Stack Overflow</a></li>
        </ol>
    </div>
</div><!-- /.blog-sidebar -->
