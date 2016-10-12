<?php
/**
 * @link https://github.com/JohnZhang360/zgjian-framework
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
            $postMod->title = "zgj";
            $postMod->content = "zgj content 123";
            $postMod->created_at = time();
            $postMod->insert();
        }
        return $this->render("index");
    }
}