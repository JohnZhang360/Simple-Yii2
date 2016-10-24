<?php
$monthArchives = Zb::$app->cache->get("monthArchives");
?>
<div class="col-sm-3 offset-sm-1 blog-sidebar">
    <div class="sidebar-module sidebar-module-inset">
        <h4>About</h4>
        <p>额... <em>没什么写的?好可怕!</em> 一定要尽快不上</p>
    </div>
    <div class="sidebar-module">
        <h4>Archives</h4>
        <ol class="list-unstyled">
            <?php foreach ($monthArchives as $val) { ?>
                <li><a href="javascript:void(0)" class="list-archives"><?= $val ?></a></li>
            <?php } ?>
        </ol>
    </div>
    <div class="sidebar-module">
        <h4>Elsewhere</h4>
        <ol class="list-unstyled">
            <li><a href="https://github.com/guang-zhang">GitHub</a></li>
            <li><a href="mailto:johnzhangbkb@gmail.com">Gmail</a></li>
            <li><a href="http://stackoverflow.com/users/6775018/john-zhang">Stack Overflow</a></li>
        </ol>
    </div>
</div><!-- /.blog-sidebar -->
