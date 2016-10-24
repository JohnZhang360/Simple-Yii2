<?php
/**
 * @link https://github.com/JohnZhang360/Simple-Yii2.git
 */

namespace app\controllers;

use app\models\Post;
use zbsoft\base\Controller;
use zbsoft\exception\NotFoundHttpException;

class SiteController extends Controller
{
    public function actionIndex()
    {
        return $this->render("index", Post::getPageList());
    }

    public function actionDetail($pid)
    {
        $postMod = Post::findOne($pid);
        if(empty($postMod)){
            throw new NotFoundHttpException();
        }
        return $this->render("detail", ['postMod'=>$postMod]);
    }
}