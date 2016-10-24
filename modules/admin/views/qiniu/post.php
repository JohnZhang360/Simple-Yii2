<?php
use zbsoft\helpers\Url;

?>
<div class="error-container"></div>
<form class="form-qiniu" id="formQiniu" data-url="<?= Url::toRoute("qiniu/index") ?>">
    <fieldset class="form-group">
        <label for="qiniuPic" class="sr-only">Upload Pic</label>
        <input type="file" name="pic" id="qiniuPic" class="form-control-file">
    </fieldset>
    <fieldset class="form-group">
        <label for="qiniuPrefix" class="sr-only">File Prefix</label>
        <input type="text" name="prefix" id="qiniuPrefix" class="form-control" placeholder="File Prefix" />
    </fieldset>
    <button type="submit" class="btn btn-primary">Submit</button>
</form>
<script src="<?= Url::to("/bower_components/jquery-validation/dist/jquery.validate.js") ?>"></script>
<script src="<?= Url::to("/bower_components/jquery-form/jquery.form.js") ?>"></script>
<script src="<?= Url::to("/js/qiniu-post.min.js") ?>"></script>