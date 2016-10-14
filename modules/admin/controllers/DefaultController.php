<?php

namespace app\modules\admin\controllers;

use Zb;
use zbsoft\base\Controller;

/**
 * Default controller for the `admin` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        if(Zb::$app->request->isPost){

        }else {
            return $this->render('index');
        }
    }
}
