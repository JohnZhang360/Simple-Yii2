<?php
/* @var \app\models\Search[] $searchList */
/* @var \zbsoft\helpers\Pagination $pager */
/* @var string $menuActive */
use zbsoft\helpers\Url;
use zbsoft\helpers\Html;

?>
<?= $this->render("/layouts/menu-nav", ["menuActive"=>$menuActive]) ?>
<div class="table-responsive">
    <div class="table-nav">
        <a href="<?= Url::toRoute("search/post") ?>" role="button" class="btn btn-success">Add</a>
    </div>
    <table class="table table-bordered table-hover table-striped">
        <thead>
        <tr>
            <th>Title</th>
            <th>Pic</th>
            <th>Sort</th>
            <th>CreatedAt</th>
            <th>Operation</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($searchList as $post) { ?>
            <tr>
                <td><?= Html::encode($post->title) ?></td>
                <td>
                    <a target="_blank" href="<?= Zb::$app->params["cdn"]["staticUrl"] . "/" . Html::encode($post->pic) ?>">
                        <img src="<?= Zb::$app->params["cdn"]["staticUrl"]."/".Html::encode($post->pic) ?>" width="32" />
                    </a>
                </td>
                <td><?= $post->sort ?></td>
                <td><?= date("Y-m-d H:i:s", $post->created_at) ?></td>
                <td>
                    <a href="<?=Url::toRoute(["search/post", "pid"=>$post->id])?>" role="button" class="btn btn-info">Edit</a>
                    <a href="javascript:if(confirm('确定删除吗？')){window.location.href='<?=Url::toRoute(["search/delete", "pid"=>$post->id])?>';}" role="button" class="btn btn-danger">Delete</a>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    <?php if (!empty($pager->pageCount > 0)) { ?>
        <nav>
            <ul class="pager">
                <?php foreach ($pager->getLinks() as $key => $link) { ?>
                    <li><a href="<?= $link ?>"><?= $key ?></a></li>
                <?php } ?>
            </ul>
        </nav>
    <?php } ?>
</div>