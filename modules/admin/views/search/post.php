<?php
/* @var Search $searchMod */
use zbsoft\helpers\Url;
use app\models\Search;

?>
<link rel="stylesheet" href="<?= Url::to("/bower_components/editor.md/css/editormd.css") ?>"/>
<div class="error-container"></div>
<form class="form-search" id="formSearch" data-url="<?= Url::toRoute("search/index") ?>">
    <fieldset class="form-group">
        <label for="searchTitle" class="sr-only">Search title</label>
        <input type="text" name="title" id="searchTitle" value="<?= $searchMod->title ?>" class="form-control"
               placeholder="Search title" autofocus>
    </fieldset>
    <fieldset class="form-group">
        <label for="searchPic" class="sr-only">Search Pic</label>
        <input type="file" name="pic" id="searchPic" class="form-control-file">
        <?php if ($searchMod->pic) { ?>
            <img src="<?= Zb::$app->params["cdn"]["staticUrl"]."/".$searchMod->pic ?>" width="100"/>
        <?php } ?>
    </fieldset>
    <fieldset class="form-group">
        <label for="searchDescription" class="sr-only">Search Description</label>
        <textarea class="form-control" name="description" id="searchDescription" placeholder="Search Description"
                  rows="3"><?= $searchMod->description ?></textarea>
    </fieldset>
    <fieldset class="form-group">
        <label for="searchSort" class="sr-only">Sort</label>
        <input type="text" name="sort" class="form-control" id="searchSort" value="<?= $searchMod->sort ?>"
               placeholder="Sort">
    </fieldset>
    <fieldset class="form-group">
        <div class="radio">
            <label>
                <input type="radio" name="is_show"
                       value="<?= Search::IS_SHOW_YES ?>" <?= $searchMod->is_show == Search::IS_SHOW_YES ? "checked" : "" ?>>
                Show Search
            </label>
        </div>
        <div class="radio">
            <label>
                <input type="radio" name="is_show"
                       value="<?= Search::IS_SHOW_NO ?>" <?= $searchMod->is_show == Search::IS_SHOW_NO ? "checked" : "" ?>>
                Hide Search
            </label>
        </div>
    </fieldset>
    <input type="hidden" id="isAdd" value="<?= empty($searchMod->id) ? 1 : 0 ?>"/>
    <button type="submit" class="btn btn-primary">Submit</button>
</form>
<script src="<?= Url::to("/bower_components/jquery-validation/dist/jquery.validate.js") ?>"></script>
<script src="<?= Url::to("/bower_components/jquery-form/jquery.form.js") ?>"></script>
<script src="<?= Url::to("/js/search-post.min.js") ?>"></script>