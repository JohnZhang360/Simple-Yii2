<?php
/* @var Post $postMod */
/* @var \app\models\Tags[] $tagsList*/
use zbsoft\helpers\Url;
use app\models\Post;

$checkTags = [];
foreach ($postMod->tags as $tags){
    array_push($checkTags, $tags->id);
}
?>
<link rel="stylesheet" href="<?= Url::to("/bower_components/editor.md/css/editormd.css") ?>"/>
<div class="error-container"></div>
<form class="form-post" id="formPost" data-url="<?= Url::toRoute("default/index") ?>" data-md-lib="<?= Url::to("/bower_components/editor.md/lib/") ?>">
    <fieldset class="form-group">
        <label for="postTitle" class="sr-only">Post title</label>
        <input type="text" name="title" id="postTitle" value="<?= $postMod->title ?>" class="form-control"
               placeholder="Post title" autofocus>
    </fieldset>
    <fieldset class="form-group">
        <div id="editor-md-summary">
            <textarea name="summary" style="display:none;"><?= $postMod->summary ?></textarea>
        </div>
    </fieldset>
    <fieldset class="form-group">
        <div id="editor-md">
            <textarea name="content" style="display:none;"><?= $postMod->content ?></textarea>
        </div>
    </fieldset>
    <fieldset class="form-group">
        <label for="postSort" class="sr-only">Tags</label>
        <?php foreach ($tagsList as $tags){?>
            <label class="checkbox-inline">
                <input type="checkbox" name="tags[]" <?=in_array($tags->id, $checkTags)?"checked":""?> value="<?=$tags->id?>"> <?=$tags->tag_name?>
            </label>
        <?php }?>
    </fieldset>
    <fieldset class="form-group">
        <label for="postSort" class="sr-only">Sort</label>
        <input type="text" name="sort" class="form-control" id="postSort" value="<?= $postMod->sort ?>"
               placeholder="Sort">
    </fieldset>
    <fieldset class="form-group">
        <div class="radio">
            <label>
                <input type="radio" name="is_show"
                       value="<?= Post::IS_SHOW_YES ?>" <?= $postMod->is_show == Post::IS_SHOW_YES ? "checked" : "" ?>>
                Show Post
            </label>
        </div>
        <div class="radio">
            <label>
                <input type="radio" name="is_show"
                       value="<?= Post::IS_SHOW_NO ?>" <?= $postMod->is_show == Post::IS_SHOW_NO ? "checked" : "" ?>>
                Hide Post
            </label>
        </div>
    </fieldset>
    <button type="submit" class="btn btn-primary">Submit</button>
</form>
<script src="<?= Url::to("/bower_components/editor.md/editormd.min.js") ?>"></script>
<script src="<?= Url::to("/bower_components/jquery-validation/dist/jquery.validate.js") ?>"></script>
<script src="<?=Url::to("/bower_components/jquery-form/jquery.form.js")?>"></script>
<script src="<?= Url::to("/js/post.min.js") ?>"></script>