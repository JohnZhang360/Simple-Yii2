<?php

namespace app\modules\admin\controllers;

use app\models\Post;
use app\models\PostFilter;
use app\models\Tags;
use Zb;
use zbsoft\helpers\ArrayHelper;
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
        $postFilter = null;
        $search = Zb::$app->request->getQueryParam("search");
        if(!empty($search)){
            $postFilter = new PostFilter();
            $postFilter->search = $search;
        }
        return $this->render("index", ArrayHelper::merge(Post::getPageList(10, $postFilter), ["menuActive" => "post"]));
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
            $postAttr = ["title", "content", "sort", "is_show", "summary"];
            foreach ($postAttr as $attr) {
                $postMod->setAttribute($attr, Zb::$app->request->post($attr));
            }
            $postMod->isNewRecord && $postMod->created_at = time();
            $postMod->updated_at = time();
            if ($postMod->save()) {
                if ($postMod->isNewRecord) {
                    Post::refreshArchives();
                }else{
                    //编辑，先删除之前的标签
                    $postMod->unlinkAll("tags", true);
                }

                //关联标签
                $checkTags = Zb::$app->request->post("tags");
                if(!empty($checkTags)){
                    foreach ($checkTags as $tags) {
                        /* @var Tags $tagsMod */
                        $tagsMod = Tags::findOne($tags);
                        $postMod->link("tags", $tagsMod);
                    }
                }

                $return["flag"] = true;
            } else {
                $return["msg"] = json_encode($postMod->errors);
            }
            return Json::encode($return);
        } else {
            return $this->render("post", ['postMod' => $postMod, "tagsList"=>Tags::find()->all()]);
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
        }
        return $this->redirect(["default/index"]);
    }
}
