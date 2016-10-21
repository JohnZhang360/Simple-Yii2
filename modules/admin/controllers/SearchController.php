<?php

namespace app\modules\admin\controllers;

/**
 * Search controller for the `admin` module
 */
class SearchController extends BaseController
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render("index");
    }
}