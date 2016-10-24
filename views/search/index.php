<?php
/* @var \app\models\Search[] $searchList */
use zbsoft\helpers\Url;
use zbsoft\helpers\Html;
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
    <link rel="icon" href="<?= Url::to("/search_favicon.ico") ?>">
    <title>ZZ-便捷-搜索</title>
    <!-- Bootstrap core CSS -->
    <link href="<?= Url::to("/bower_components/bootstrap/dist/css/bootstrap.min.css") ?>" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="<?= Url::to("/css/search.min.css") ?>" rel="stylesheet">
    <link href="<?= Url::to("/bower_components/perfect-scrollbar/css/perfect-scrollbar.css") ?>" rel="stylesheet">
</head>

<body>
<div class="container-fluid">
    <div class="row-offcanvas row-offcanvas-left">
        <div class="list-nav col-sm-1">
            <div class="list-group sidebar-offcanvas" id="sidebar-nav">
                <?php foreach ($searchList as $search) { ?>
                    <a <?php if ($search->target == 1){ ?>target="_blank" href="<?= Html::encode($search->link) ?>"
                       <?php }else{ ?>data-href="<?= Html::encode($search->link) ?>"<?php } ?> class="list-group-item">
                        <img src="<?= Zb::$app->params["cdn"]["staticUrl"] . "/" . Html::encode($search->pic) ?>"/><?= Html::encode($search->title) ?>
                    </a>
                <?php } ?>
            </div>
            <div class="nav-toggler" data-toggle="offcanvas">
                <span class="glyphicon glyphicon-chevron-right">&nbsp;</span>
            </div>
        </div>
        <div class="col-xs-12 col-sm-11 nav-content">
            <iframe id="nav-iframe" height="100%" width="100%" frameborder="0"></iframe>
        </div><!--/span-->
    </div><!--/row-->
</div><!--/.container-->
<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="<?= Url::to("/bower_components/jquery/dist/jquery.min.js") ?>"></script>
<script src="<?= Url::to("/bower_components/bootstrap/dist/js/bootstrap.min.js") ?>"></script>
<script src="<?= Url::to("/js/search.min.js") ?>"></script>
<script src="<?= Url::to("/bower_components/perfect-scrollbar/js/perfect-scrollbar.jquery.js") ?>"></script>
</body>
</html>