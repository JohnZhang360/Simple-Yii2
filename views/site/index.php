<?php
/* @var \app\models\Post[] $postList */
/* @var \zbsoft\helpers\Pagination $pager */
use zbsoft\helpers\Html;
use zbsoft\helpers\Url;

?>
<link href="<?= Url::to("/bower_components/editor.md/css/editormd.preview.css") ?>" rel="stylesheet">
<div class="row">
    <div class="col-sm-8 blog-main">
        <?php if(count($postList) > 0){?>
            <?php foreach ($postList as $post) { ?>
                <div class="blog-post">
                    <h2 class="blog-post-title">
                        <a href="<?= Url::toRoute(["site/detail", "pid" => $post->id]) ?>"><?= Html::encode($post->title) ?></a>
                    </h2>
                    <p class="blog-post-meta">
                        <span class="date"><?= date("Y-m-d", $post->created_at) ?></span>
                        <?php foreach ($post->tags as $tags){?>
                            <span class="label label-default"><?=$tags->tag_name?></span>
                        <?php }?>
                    </p>
                    <div class="blog-markdown-content"><?=Html::encode($post->summary)?></div>
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
        <?php }else{?>
            <h3>Not Found</h3>
            <h5>Sorry, but you are looking for something that isn't here.</h5>
        <?php }?>
    </div><!-- /.blog-main -->
    <?= $this->render("/layouts/right-nav") ?>
</div><!-- /.row -->
<script src="<?= Url::to("/bower_components/editor.md/editormd.min.js") ?>"></script>
<script src="<?= Url::to("/bower_components/editor.md/lib/marked.min.js")?>"></script>
<script src="<?= Url::to("/bower_components/editor.md/lib/prettify.min.js")?>"></script>
<script src="<?= Url::to("/js/post-list.min.js") ?>"></script>

