<?php
/* @var \app\models\Post $postMod*/
/* @var \app\models\Post $postPrev*/
/* @var \app\models\Post $postNext*/
use zbsoft\helpers\Html;
use zbsoft\helpers\Url;

?>
<link href="<?= Url::to("/bower_components/editor.md/css/editormd.preview.css") ?>" rel="stylesheet">
<div class="row">
    <div class="col-sm-8 blog-main">
        <div class="blog-post">
            <h2 class="blog-post-title"><?=$postMod->title?></h2>
            <p class="blog-post-meta">
                <span class="date"><?=date("Y-m-d", $postMod->created_at)?></span>
                <?php foreach ($postMod->tags as $tags){?>
                    <span class="label label-default"><?=$tags->tag_name?></span>
                <?php }?>
            </p>
            <div class="blog-markdown-content"><?=Html::encode($postMod->content)?></div>
            <div class="blog-content" id="blogContent"></div>
        </div><!-- /.blog-post -->
        <div class="list-group">
            <?php if(!empty($postPrev)){?>
                <a href="<?= Url::toRoute(["site/detail", "pid" => $postPrev->id]) ?>" class="list-group-item">上一篇：<?=$postPrev->title?></a>
            <?php }?>
            <?php if(!empty($postNext)){?>
                <a href="<?= Url::toRoute(["site/detail", "pid" => $postNext->id]) ?>" class="list-group-item">下一篇：<?=$postNext->title?></a>
            <?php }?>
        </div>
    </div><!-- /.blog-main -->
    <?= $this->render("/layouts/right-nav") ?>
</div><!-- /.row -->
<script src="<?= Url::to("/bower_components/editor.md/editormd.min.js") ?>"></script>
<script src="<?= Url::to("/bower_components/editor.md/lib/marked.min.js")?>"></script>
<script src="<?= Url::to("/bower_components/editor.md/lib/prettify.min.js")?>"></script>
<script src="<?= Url::to("/js/post-detail.min.js") ?>"></script>