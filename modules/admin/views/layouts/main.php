<?php
use zbsoft\helpers\Url;
use app\models\Admin;
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="<?=Url::to("/favicon.ico")?>">

    <!-- Bootstrap core CSS -->
    <link href="<?=Url::to("/bower_components/bootstrap/dist/css/bootstrap.min.css")?>" rel="stylesheet">
    <link href="<?= Url::to("/css/admin.min.css") ?>" rel="stylesheet">
    <script src="<?=Url::to("/bower_components/jquery/dist/jquery.min.js")?>"></script>
</head>

<body>

<div class="blog-header">
    <div class="container">
        <h1 class="blog-title">
            <a href="<?=Url::toRoute(["/admin"])?>">Zhang Guangjian</a>
            <?php if(Admin::isLogin()){?>
                <button type="button" class="btn btn-warning logout-btn" id="logout-btn"
                        data-url="<?= Url::toRoute("default/logout") ?>" data-turn="<?= Url::toRoute("public/login") ?>">Logout
                </button>
            <?php }?>
        </h1>
        <p class="lead blog-description">Good Good Code, Day Day Up.</p>
    </div>
</div>

<div class="container">

    <?=$content?>

</div><!-- /.container -->

<footer class="blog-footer">
    <p><a href="#">Back to top</a></p>
    <p>Copyright©<a href="/">ZhangGuangjian</a>，All Rights Reserved</p>
    <p>粤ICP备16083517号-1</p>
</footer>


<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="<?=Url::to("/bower_components/tether/dist/js/tether.min.js")?>"></script>
<script src="<?=Url::to("/bower_components/bootstrap/dist/js/bootstrap.min.js")?>"></script>
<script src="<?=Url::to("/js/admin.min.js")?>"></script>
</body>

</html>
