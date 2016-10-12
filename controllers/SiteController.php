<?php
/**
 * @link https://github.com/JohnZhang360/Simple-Yii2.git
 */

namespace app\controllers;

use app\models\Post;
use zbsoft\base\Controller;

class SiteController extends Controller
{
    public function actionIndex()
    {
        if (isset($_POST["submit"])) {
            $postMod = new Post();
            $postMod->title = "test" . time();
            $postMod->content = "content" . time();
            $postMod->created_at = time();
            $postMod->insert();
        }
        $postList = Post::find()->all();
        return $this->render("index", ["postList" => $postList]);
    }
}