<?php

namespace app\modules\admin\controllers;

use Zb;
use zbsoft\base\Controller;

/**
 * Default controller for the `admin` module
 */
class DefaultController extends BaseController
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render("index");
    }

    public function actionPost()
    {
        return $this->render("post");
    }
}
