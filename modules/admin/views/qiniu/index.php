<?php
/* @var array $fileList */
/* @var string $menuActive */
/* @var integer $page */
use zbsoft\helpers\Url;

?>
<?= $this->render("/layouts/menu-nav", ["menuActive" => $menuActive]) ?>
<?php if (isset($error) && !empty($error)) { ?>
    <?= $error ?>
<?php } else { ?>
    <div class="table-responsive">
        <div class="table-nav">
            <a href="<?= Url::toRoute("qiniu/post") ?>" role="button" class="btn btn-success">Add</a>
            <form class="form-inline navbar-form pull-right">
                <input class="form-control" name="prefix" value="<?=$prefix?>" type="text" placeholder="Search Prefix">
                <input type="hidden" name="page" value="<?=$page?>" />
                <button class="btn btn-success-outline" type="submit">Search</button>
            </form>
        </div>
        <table class="table table-bordered table-hover table-striped table-qiniu">
            <thead>
            <tr>
                <th>Key</th>
                <th>View</th>
                <th>MimeType</th>
                <th>File Size</th>
                <th>File Modify Time</th>
                <th>Operation</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($fileList as $file) { ?>
                <tr>
                    <td><?= $file["key"] ?></td>
                    <td>
                        <a target="_blank" href="<?=Zb::$app->params["cdn"]["staticUrl"]."/".$file["key"]?>">
                            <img src="<?=Zb::$app->params["cdn"]["staticUrl"]."/".$file["key"]?>" class="pic" />
                        </a>
                    </td>
                    <td><?= $file["mimeType"] ?></td>
                    <td><?= $file["fsize"] ?></td>
                    <td><?= date("Y-m-d H:i:s", substr(sprintf("%f", $file["putTime"]), 0, 10)) ?></td>
                    <td>
                        <a href="javascript:if(confirm('确定删除吗？')){window.location.href='<?= Url::toRoute(["qiniu/delete", "key" => $file["key"]]) ?>';}"
                           role="button" class="btn btn-danger">Delete</a>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
        <nav>
            <ul class="pager">
                <?php if (isset($qiniuPager[$page - 2])) { ?>
                    <li><a href="<?= Url::toRoute(["qiniu/index", "page" => ($page - 1), "prefix"=>$prefix]) ?>">Previous</a></li>
                <?php } ?>
                <?php if (isset($qiniuPager[$page])) { ?>
                    <li><a href="<?= Url::toRoute(["qiniu/index", "page" => ($page + 1), "prefix"=>$prefix]) ?>">Next</a></li>
                <?php } ?>
            </ul>
        </nav>
    </div>
<?php } ?>