<?php
/* @var \app\models\Post[] $postList */
/* @var \zbsoft\helpers\Pagination $pager */
/* @var string $menuActive */
use zbsoft\helpers\Url;
use zbsoft\helpers\Html;

?>
<?= $this->render("/layouts/menu-nav", ["menuActive" => $menuActive]) ?>
<?= $this->render("/layouts/sub-menu-nav", ["active" => "list"]) ?>
<div class="table-responsive">
    <div class="table-nav">
        <a href="<?= Url::toRoute("default/post") ?>" role="button" class="btn btn-success-outline">Add</a>
        <form class="form-inline navbar-form pull-right">
            <input class="form-control" name="search" value="<?= Zb::$app->request->getQueryParam("search") ?>"
                   type="text" placeholder="Search">
            <button class="btn btn-success-outline" type="submit">Search</button>
        </form>
    </div>
    <table class="table table-bordered table-hover table-striped">
        <thead>
        <tr>
            <th>Title</th>
            <th>Visits</th>
            <th>Sort</th>
            <th>CreatedAt</th>
            <th>Operation</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($postList as $post) { ?>
            <tr>
                <td><?= Html::encode($post->title) ?></td>
                <td><?= $post->views ?></td>
                <td><?= $post->sort ?></td>
                <td><?= date("Y-m-d H:i:s", $post->created_at) ?></td>
                <td>
                    <a href="<?= Url::toRoute(["default/post", "pid" => $post->id]) ?>" role="button"
                       class="btn btn-info">Edit</a>
                    <a href="javascript:if(confirm('确定删除吗？')){window.location.href='<?= Url::toRoute(["default/delete", "pid" => $post->id]) ?>';}"
                       role="button" class="btn btn-danger">Delete</a>
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