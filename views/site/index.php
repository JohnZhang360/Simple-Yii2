<?php
/**
 * @var \app\models\Post[] $postList
 */
$text = "PHP TEXT";
?>
<html>
<body>

<h1>My First Heading</h1>

<p>My first paragraph.</p>

<p><?= $text ?></p>

<ul>
    <?php foreach ($postList as $post) { ?>
        <li><?= $post->title ?></li>
    <?php } ?>
</ul>

<form method="post">
    <input type="submit" name="submit" value="提交"/>
</form>

</body>
</html>