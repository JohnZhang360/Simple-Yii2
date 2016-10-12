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
        if(isset($_POST["submit"])) {
            $postMod = new Post();
            $postMod->title = "test";
            $postMod->content = "content";
            $postMod->created_at = time();
            $postMod->insert();
        }
        return $this->render("index");
    }
}