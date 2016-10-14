<?php

namespace app\modules\admin\controllers;

use Zb;
use zbsoft\base\Controller;

/**
 * Default controller for the `admin` module
 */
class PublicController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionLogin()
    {
        if(Zb::$app->request->isPost){
            return $this->renderContent("post data");
        }else {
            return $this->render('login');
        }
    }
}
