<?php

namespace app\modules\admin\controllers;

use app\tools\Qiniu;
use app\tools\Upload;
use Zb;
use app\models\Search;
use zbsoft\helpers\Json;

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
        return $this->render("index", array_merge(Search::getPageList(), ["menuActive" => "search"]));
    }

    /**
     * 发布搜索
     * @param string $pid
     * @return string
     */
    public function actionPost($pid = "")
    {
        /* @var Search $searchMod */
        $searchMod = Search::findOne($pid);
        if (empty($searchMod)) {
            $searchMod = new Search();
            $searchMod->sort = 255;
            $searchMod->is_show = Search::IS_SHOW_YES;
        }
        if (Zb::$app->request->isPost) {
            $return = ["flag" => false, "msg" => ""];
            $postAttr = ["title", "description", "sort", "is_show", "link", "target"];
            foreach ($postAttr as $attr) {
                $searchMod->setAttribute($attr, Zb::$app->request->post($attr));
            }

            $pic = Zb::$app->request->post("pic");
            if (empty($pic)) {
                if ($searchMod->isNewRecord) {
                    $return["msg"] = "Please enter image key";
                    return Json::encode($return);
                }
            } else {
                $searchMod->pic = trim($pic);
            }

            $searchMod->isNewRecord && $searchMod->created_at = time();
            if ($searchMod->save()) {
                $return["flag"] = true;
            } else {
                $return["msg"] = json_encode($searchMod->errors);
            }
            return Json::encode($return);
        } else {
            return $this->render("post", ['searchMod' => $searchMod]);
        }
    }

    /**
     * 删除搜索
     * @param $pid
     * @return string
     */
    public function actionDelete($pid)
    {
        $searchMod = Search::findOne($pid);
        if ($searchMod) {
            $searchMod->delete();
        }
        return $this->redirect(["default/index"]);
    }
}