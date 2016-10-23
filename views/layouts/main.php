<?php
use zbsoft\helpers\Url;
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
    <title>Zhang Guangjian</title>
    <link rel="icon" href="<?=Url::to("/favicon.ico")?>">

    <!-- Bootstrap core CSS -->
    <link href="<?=Url::to("/bower_components/bootstrap/dist/css/bootstrap.min.css")?>" rel="stylesheet">
    <link href="<?= Url::to("/css/style.min.css") ?>" rel="stylesheet">
</head>

<body>

<div class="blog-header">
    <div class="container">
        <h1 class="blog-title"><a href="/">Zhang Guangjian</a></h1>
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
<script src="<?=Url::to("/bower_components/jquery/dist/jquery.min.js")?>"></script>
<script>window.jQuery || document.write('<script src="<?=Url::to("/bower_components/bootstrap/dist/js/vendor/jquery.min.js")?>"><\/script>')</script>

<script src="<?=Url::to("/bower_components/tether/dist/js/tether.min.js")?>"></script>
<script src="<?=Url::to("/bower_components/bootstrap/dist/js/bootstrap.min.js")?>"></script>
<script src="<?=Url::to("/js/script.min.js")?>"></script>
</body>

</html>
