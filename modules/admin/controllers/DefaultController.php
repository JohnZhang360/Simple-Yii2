<?php

namespace app\modules\admin\controllers;

use app\models\Admin;
use Zb;
use zbsoft\base\Response;
use zbsoft\exception\NotFoundHttpException;
use zbsoft\helpers\Url;

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
        echo Url::toRoute("default/logout");
        exit;
        return $this->render("index");
    }

    /**
     * 发布文章
     * @return string
     */
    public function actionPost()
    {
        return $this->render("post");
    }

    /**
     * 登出
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionLogout()
    {
        if (Zb::$app->request->isPost) {
            return ["flag" => Admin::logout()];
        } else {
            $response = Zb::$app->response;
            $response->statusCode = 404;
            throw new NotFoundHttpException(Response::$httpStatuses[404]);
        }
    }
}
