<?php
/**
 * @link https://github.com/JohnZhang360/Simple-Yii2.git
 */

namespace app\controllers;

use zbsoft\base\Controller;

class SiteController extends Controller
{
    public function actionIndex()
    {
        return $this->render("index");
    }
}