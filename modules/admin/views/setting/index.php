<?php
/* @var array[] $config */
/* @var string $menuActive*/
use zbsoft\helpers\Url;

?>
<?= $this->render("/layouts/menu-nav", ["menuActive" => $menuActive]) ?>
<div class="error-container"></div>
<form class="form-setting" id="formSetting" method="post" data-url="<?= Url::toRoute("setting/index") ?>">
    <fieldset class="form-group">
        <label for="SiteName" class="sr-only">Site Name</label>
        <input type="text" name="site_name" id="SiteName" value="<?= isset($config["site_name"])?$config["site_name"]:"" ?>" class="form-control"
               placeholder="Site Name">
    </fieldset>
    <fieldset class="form-group">
        <label for="SiteTitle" class="sr-only">Site Title</label>
        <input type="text" name="site_title" id="SiteTitle" value="<?= isset($config["site_title"])?$config["site_title"]:"" ?>" class="form-control"
               placeholder="Site Title">
    </fieldset>
    <fieldset class="form-group">
        <label for="SiteKeywords" class="sr-only">Site Keywords</label>
        <input type="text" name="site_keywords" id="SiteKeywords" value="<?= isset($config["site_keywords"])?$config["site_keywords"]:"" ?>" class="form-control"
               placeholder="Site Keywords">
    </fieldset>
    <fieldset class="form-group">
        <label for="SiteDescription" class="sr-only">Site Description</label>
        <input type="text" name="site_description" id="SiteDescription" value="<?= isset($config["site_description"])?$config["site_description"]:"" ?>" class="form-control"
               placeholder="Site Description">
    </fieldset>
    <fieldset class="form-group">
        <label for="SiteIcp" class="sr-only">Site Icp</label>
        <input type="text" name="site_icp" id="SiteIcp" value="<?= isset($config["site_icp"])?$config["site_icp"]:"" ?>" class="form-control"
               placeholder="Site Icp">
    </fieldset>
    <fieldset class="form-group">
        <label for="About" class="sr-only">About</label>
        <textarea id="About" name="about" class="form-control"  placeholder="About"><?= isset($config["site_icp"])?$config["about"]:"" ?></textarea>
    </fieldset>
    <fieldset class="form-group">
        <label for="Signature" class="sr-only">Signature</label>
        <textarea id="Signature" name="signature" class="form-control"  placeholder="Signature"><?= isset($config["site_icp"])?$config["signature"]:"" ?></textarea>
    </fieldset>
    <button type="submit" class="btn btn-primary">Submit</button>
</form>
<script src="<?= Url::to("/bower_components/jquery-validation/dist/jquery.validate.js") ?>"></script>
<script src="<?=Url::to("/bower_components/jquery-form/jquery.form.js")?>"></script>
<script src="<?= Url::to("/js/config.min.js") ?>"></script>
