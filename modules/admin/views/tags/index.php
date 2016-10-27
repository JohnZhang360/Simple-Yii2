<?php
/* @var \app\models\Tags[] $tagsList */
/* @var \zbsoft\helpers\Pagination $pager */
/* @var string $menuActive */
use zbsoft\helpers\Url;

?>
<?= $this->render("/layouts/menu-nav", ["menuActive" => $menuActive]) ?>
<?= $this->render("/layouts/sub-menu-nav", ["active" => "tags"]) ?>
<div class="table-responsive">
    <div class="table-nav">
        <a href="<?= Url::toRoute("tags/post") ?>" role="button" class="btn btn-success-outline">Add</a>
    </div>
    <table class="table table-bordered table-hover table-striped">
        <thead>
        <tr>
            <th>Tag Name</th>
            <th>Operation</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($tagsList as $tags) { ?>
            <tr>
                <td><?= $tags->tag_name ?></td>
                <td>
                    <a href="<?= Url::toRoute(["tags/post", "tid" => $tags->id]) ?>" role="button"
                       class="btn btn-info">Edit</a>
                    <a href="javascript:if(confirm('确定删除吗？')){window.location.href='<?= Url::toRoute(["tags/delete", "tid" => $tags->id]) ?>';}"
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