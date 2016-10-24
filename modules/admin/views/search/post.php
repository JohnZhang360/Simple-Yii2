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
        <input type="text" name="pic" id="searchPic" value="<?= $searchMod->pic ?>" class="form-control"
               placeholder="Search Pic" autofocus>
        <?php if ($searchMod->pic) { ?>
            <a target="_blank" href="<?= Zb::$app->params["cdn"]["staticUrl"] . "/" . $searchMod->pic ?>">
                <img class="pic" src="<?= Zb::$app->params["cdn"]["staticUrl"] . "/" . $searchMod->pic ?>" height="32"/>
            </a>
        <?php } ?>
    </fieldset>
    <fieldset class="form-group">
        <label for="searchLink" class="sr-only">Search Link</label>
        <input type="text" name="link" id="searchLink" value="<?= $searchMod->link ?>" class="form-control"
               placeholder="Search link" autofocus>
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
    <fieldset class="form-group">
        <div class="radio">
            <label>
                <input type="radio" name="target"
                       value="<?= Search::TARGET_DEFAULT ?>" <?= $searchMod->target == Search::TARGET_DEFAULT ? "checked" : "" ?>>
                Default Target
            </label>
        </div>
        <div class="radio">
            <label>
                <input type="radio" name="target"
                       value="<?= Search::TARGET_BLANK ?>" <?= $searchMod->target == Search::TARGET_BLANK ? "checked" : "" ?>>
                Blank Target
            </label>
        </div>
    </fieldset>
    <input type="hidden" id="isAdd" value="<?= $searchMod->id == null ? 1 : 0 ?>"/>
    <button type="submit" class="btn btn-primary">Submit</button>
</form>
<script src="<?= Url::to("/bower_components/jquery-validation/dist/jquery.validate.js") ?>"></script>
<script src="<?= Url::to("/bower_components/jquery-form/jquery.form.js") ?>"></script>
<script src="<?= Url::to("/js/search-post.min.js") ?>"></script>