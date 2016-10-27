<?php
use zbsoft\helpers\Url;
?>
<form id="formSignin" class="form-signin" data-url = "<?=Url::toRoute("default/index")?>">
    <h2 class="form-signin-heading">Please sign in</h2>
    <label for="inputUsername" class="sr-only">Username</label>
    <input type="text" name="username" id="inputUsername" class="form-control" placeholder="Username"
           autofocus>
    <label for="inputPassword" class="sr-only">Password</label>
    <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Password">
    <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
</form>
<script src="<?=Url::to("/bower_components/jquery-validation/dist/jquery.validate.js")?>"></script>
<script src="<?=Url::to("/bower_components/jquery-form/jquery.form.js")?>"></script>
<script src="<?=Url::to("/js/login.min.js")?>"></script>