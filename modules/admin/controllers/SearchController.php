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
     * 发布文章
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
            $postAttr = ["title", "description", "sort", "is_show"];
            foreach ($postAttr as $attr) {
                $searchMod->setAttribute($attr, Zb::$app->request->post($attr));
            }

            $uploadKey = "pic";
            $uploadResult = null;
            if ($_FILES[$uploadKey]["error"] == UPLOAD_ERR_NO_FILE) {
                if ($searchMod->isNewRecord) {
                    $return["msg"] = "Please upload image";
                    return Json::encode($return);
                }
            } else {
                $upload = new Upload();
                $uploadResult = $upload->upload($_FILES[$uploadKey]);
                if (!is_array($uploadResult)) {
                    $return["msg"] = $uploadResult;
                    return Json::encode($return);
                }
            }

            if ($uploadResult !== null && is_array($uploadResult)) {
                $fullPath = $uploadResult["savepath"] . $uploadResult["savename"];
                $qiniuUpload = Qiniu::getInstance()->upload($fullPath, Zb::$app->params["cdn"]["bucket"]);
                @unlink($fullPath);
                if ($qiniuUpload["flag"] == false) {
                    $return["msg"] = json_encode($qiniuUpload["error"]);
                    return Json::encode($return);
                } else {
                    if(!$searchMod->isNewRecord){
                        //delete remote image...

                    }
                    $searchMod->pic = $qiniuUpload["ret"]["key"];
                }
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
     * 删除文章
     * @param $pid
     * @return string
     */
    public function actionDelete($pid)
    {
        $searchMod = Search::findOne($pid);
        if ($searchMod) {
            $searchMod->delete();
            return $this->redirect(["default/index"]);
        } else {
            return "fail";
        }
    }
}