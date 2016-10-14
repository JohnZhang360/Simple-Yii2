<?php
use zbsoft\helpers\Url;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="<?=Url::to("/favicon.ico")?>">

    <title>Blog - Zhang Guangjian</title>

    <!-- Bootstrap core CSS -->
    <link href="<?=Url::to("/dist/css/bootstrap.min.css")?>" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="<?=Url::to("/assets/css/ie10-viewport-bug-workaround.css")?>" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?=Url::to("/css/blog.css")?>" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="<?=Url::to("/assets/js/ie8-responsive-file-warning.js")?>"></script><![endif]-->
    <script src="<?=Url::to("/assets/js/ie-emulation-modes-warning.js")?>"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    　　<script src="//cdn.bootcss.com/respond.js/1.4.2/respond.js"></script>
    　　<script src="//cdn.bootcss.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <![endif]-->
</head>

<body>

<div class="container">

    <div class="blog-header">
        <h1 class="blog-title">Zhang Guangjian</h1>
        <p class="lead blog-description">Good Good Code, Day Day Up.</p>
    </div>

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
<script src="//cdn.bootcss.com/jquery/3.1.1/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
<script src="<?=Url::to("/dist/js/bootstrap.min.js")?>"></script>
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="<?=Url::to("/assets/js/ie10-viewport-bug-workaround.js")?>"></script>
</body>
</html>