<?php
use zbsoft\helpers\Url;
use app\models\Post;
?>
<link rel="stylesheet" href="<?= Url::to("/bower_components/editor.md/css/editormd.css") ?>"/>
<form class="form-post" id="formPost">
    <fieldset class="form-group">
        <label for="postTitle" class="sr-only">Post title</label>
        <input type="text" name="Post[title]" id="postTitle" class="form-control" placeholder="Post title" autofocus>
    </fieldset>
    <fieldset class="form-group">
        <div id="editor-md">
            <textarea name="Post[content]" style="display:none;"></textarea>
        </div>
    </fieldset>
    <fieldset class="form-group">
        <label for="postSort" class="sr-only">Sort</label>
        <input type="text" class="form-control" id="postSort" placeholder="Sort">
    </fieldset>
    <fieldset class="form-group">
        <div class="radio">
            <label>
                <input type="radio" name="Post[is_show]" value="<?=Post::IS_SHOW_YES?>" checked>
                Show Post
            </label>
        </div>
        <div class="radio">
            <label>
                <input type="radio" name="Post[is_show]" value="<?=Post::IS_SHOW_NO?>">
                Hide Post
            </label>
        </div>
    </fieldset>
    <button type="submit" class="btn btn-primary">Submit</button>
</form>
<script src="<?= Url::to("/bower_components/editor.md/editormd.min.js") ?>"></script>
<script src="<?= Url::to("/bower_components/jquery-validation/dist/jquery.validate.js")?>"></script>
<script type="text/javascript">
    var testEditor;
    $(function () {
        testEditor = editormd({
            id: "editor-md",
            height: 640,
            path: "<?= Url::to("/bower_components/editor.md/lib/")?>"
        });

        $("#formPost").validate({
            rules: {
                "Post[title]": {
                    required: true
                }
            }
        });
    });
</script>