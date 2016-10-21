<?php

namespace app\modules\admin\controllers;

use app\models\Post;
use Zb;
use zbsoft\helpers\Json;

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
        return $this->render("index", Post::getPageList());
    }

    /**
     * 发布文章
     * @param string $pid
     * @return string
     */
    public function actionPost($pid = "")
    {
        /* @var Post $postMod */
        $postMod = Post::findOne($pid);
        if (empty($postMod)) {
            $postMod = new Post();
            $postMod->sort = 255;
            $postMod->is_show = Post::IS_SHOW_YES;
        }
        if (Zb::$app->request->isPost) {
            $return = ["flag" => false, "msg" => ""];
            $postAttr = ["title", "content", "sort", "is_show"];
            foreach ($postAttr as $attr) {
                $postMod->setAttribute($attr, Zb::$app->request->post($attr));
            }
            $postMod->isNewRecord && $postMod->created_at = time();
            $postMod->updated_at = time();
            if ($postMod->save()) {
                $return["flag"] = true;
            } else {
                $return["msg"] = json_encode($postMod->errors);
            }
            return Json::encode($return);
        } else {
            return $this->render("post", ['postMod' => $postMod]);
        }
    }

    /**
     * 删除文章
     * @param $pid
     * @return string
     */
    public function actionDelete($pid)
    {
        $postMod = Post::findOne($pid);
        if ($postMod) {
            $postMod->is_delete = Post::IS_DELETE_YES;
            $postMod->update();
            return $this->redirect(["default/index"]);
        } else {
            return "fail";
        }
    }
}
