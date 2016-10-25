<?php
/* @var Tags $tagsMod */
use zbsoft\helpers\Url;
use app\models\Tags;

?>
<div class="error-container"></div>
<form class="form-tags" id="formTags" data-url="<?= Url::toRoute("tags/index") ?>">
    <fieldset class="form-group">
        <label for="tagsTagName" class="sr-only">Tags Name</label>
        <input type="text" name="tag_name" id="tagsTagName" value="<?= $tagsMod->tag_name ?>" class="form-control"
               placeholder="Tags Name" autofocus>
    </fieldset>
    <button type="submit" class="btn btn-primary">Submit</button>
</form>
<script src="<?= Url::to("/bower_components/jquery-validation/dist/jquery.validate.js") ?>"></script>
<script src="<?=Url::to("/bower_components/jquery-form/jquery.form.js")?>"></script>
<script src="<?= Url::to("/js/tags.min.js") ?>"></script>