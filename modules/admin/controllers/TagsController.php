<?php
namespace app\modules\admin\controllers;

use app\models\Tags;
use Zb;
use zbsoft\helpers\ArrayHelper;
use zbsoft\helpers\Json;

/**
 * Default controller for the `tags` module
 */
class TagsController extends BaseController
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render("index", ArrayHelper::merge(Tags::getPageList(), ["menuActive" => "post"]));
    }

    /**
     * 发布文章
     * @param string $tid
     * @return string
     */
    public function actionPost($tid = "")
    {
        /* @var Tags $tagsMod */
        $tagsMod = Tags::findOne($tid);
        if (empty($tagsMod)) {
            $tagsMod = new Tags();
        }
        if (Zb::$app->request->isPost) {
            $return = ["flag" => false, "msg" => ""];
            $tagsMod->tag_name = Zb::$app->request->post("tag_name");
            if ($tagsMod->save()) {
                $return["flag"] = true;
            } else {
                $return["msg"] = json_encode($tagsMod->errors);
            }
            return Json::encode($return);
        } else {
            return $this->render("post", ['tagsMod' => $tagsMod]);
        }
    }

    /**
     * 删除文章
     * @param $tid
     * @return string
     */
    public function actionDelete($tid)
    {
        $tagsMod = Tags::findOne($tid);
        if ($tagsMod) {
            $tagsMod->delete();
        }
        return $this->redirect(["tags/index"]);
    }
}