<?php
/**
 * @link https://github.com/JohnZhang360/Simple-Yii2.git
 */

namespace app\modules\admin\controllers;

use app\tools\Upload;
use Zb;
use app\tools\Qiniu;
use zbsoft\helpers\Json;

class QiniuController extends BaseController
{
    public function actionIndex()
    {
        $pagerSessionKey = "qiniuPager";
        $zbSession = Zb::$app->session;
        $page = Zb::$app->request->getQueryParam("page", 1);
        $prefix = Zb::$app->request->getQueryParam("prefix", "");
        $qiniuPager = $zbSession->has($pagerSessionKey) ? $zbSession->get($pagerSessionKey) : [""];
        if (!isset($qiniuPager[$page - 1])) {
            //不存在的列表，重新第一页开始
            $zbSession->remove($pagerSessionKey);
            return $this->redirect(["qiniu/index", "prefix"=>$prefix]);
        }

        $pageSize = 10;
        $fileList = Qiniu::getInstance()->listFile(Zb::$app->params["cdn"]["bucket"]["default"], $qiniuPager[$page - 1], $pageSize, $prefix);
        $assign = [];
        if ($fileList["flag"] == false) {
            $assign["error"] = json_encode($fileList["error"]);
        } else {
            if (!empty($fileList["marker"]) && !in_array($fileList["marker"], $qiniuPager)) {
                $qiniuPager[] = $fileList["marker"];
            }
            $zbSession->set("qiniuPager", $qiniuPager);
            $assign = ['fileList' => $fileList["items"], 'qiniuPager' => $qiniuPager, 'page' => $page, 'prefix' => $prefix];
        }
        return $this->render("index", array_merge($assign, ['menuActive' => "qiniu"]));
    }

    public function actionPost()
    {
        if (Zb::$app->request->isPost) {
            $return = ["flag" => false, "msg" => ""];
            $uploadKey = "pic";
            $uploadResult = null;
            if (!isset($_FILES[$uploadKey]) || $_FILES[$uploadKey]["error"] == UPLOAD_ERR_NO_FILE) {
                $return["msg"] = "Please upload image";
            } else {
                $prefix = Zb::$app->request->post("prefix", "");
                $upload = new Upload();
                $uploadResult = $upload->upload($_FILES[$uploadKey]);
                if (!is_array($uploadResult)) {
                    $return["msg"] = $uploadResult;
                } else {
                    $fullPath = $uploadResult["savepath"] . $uploadResult["savename"];
                    $qiniuUpload = Qiniu::getInstance()->upload($fullPath, Zb::$app->params["cdn"]["bucket"]["default"], $prefix);
                    @unlink($fullPath);
                    if ($qiniuUpload["flag"] == false) {
                        $return["msg"] = json_encode($qiniuUpload["error"]);
                    } else {
                        $return["flag"] = true;
                    }
                }
            }
            return Json::encode($return);
        }
        return $this->render("post");
    }


    /**
     * 删除文件
     * @param $key
     * @return string
     */
    public function actionDelete($key)
    {
        Qiniu::getInstance()->delete($key, Zb::$app->params["cdn"]["bucket"]["default"]);
        return $this->redirect(["qiniu/index"]);
    }
}