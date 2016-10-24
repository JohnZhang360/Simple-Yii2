<?php
/* @var \app\models\Post[] $postList */
/* @var \zbsoft\helpers\Pagination $pager */
use zbsoft\helpers\Html;
use zbsoft\helpers\Url;

?>
<link href="<?= Url::to("/bower_components/editor.md/css/editormd.preview.css") ?>" rel="stylesheet">
<div class="row">
    <div class="col-sm-8 blog-main">
        <?php foreach ($postList as $post) { ?>
            <div class="blog-post">
                <h2 class="blog-post-title">
                    <a href="<?= Url::toRoute(["site/detail", "pid" => $post->id]) ?>"><?= Html::encode($post->title) ?></a>
                </h2>
                <p class="blog-post-meta"><?= date("Y-m-d", $post->created_at) ?></a></p>
                <div class="blog-markdown-content"><?=Html::encode($post->content)?></div>
                <div class="blog-content" id="blogContent<?=$post->id?>"></div>
            </div><!-- /.blog-post -->
        <?php } ?>
        <?php if (!empty($pager->pageCount > 0)) { ?>
            <nav>
                <ul class="pager">
                    <?php foreach ($pager->getLinks() as $key => $link) { ?>
                        <li><a href="<?= $link ?>"><?= $key ?></a></li>
                    <?php } ?>
                </ul>
            </nav>
        <?php } ?>
    </div><!-- /.blog-main -->
    <?= $this->render("/layouts/right-nav") ?>
</div><!-- /.row -->
<script src="<?= Url::to("/bower_components/editor.md/editormd.min.js") ?>"></script>
<script src="<?= Url::to("/bower_components/editor.md/lib/marked.min.js")?>"></script>
<script src="<?= Url::to("/bower_components/editor.md/lib/prettify.min.js")?>"></script>
<script src="<?= Url::to("/js/post-list.min.js") ?>"></script>

