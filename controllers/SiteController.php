<?php
/**
 * @link https://github.com/JohnZhang360/Simple-Yii2.git
 */

namespace app\controllers;

use app\models\PostFilter;
use Zb;
use app\models\Post;
use zbsoft\base\Controller;
use zbsoft\exception\NotFoundHttpException;

class SiteController extends BaseController
{
    public function actionIndex()
    {
        $perPage = Zb::$app->request->getQueryParam("per-page", 10);
        $archive = Zb::$app->request->getQueryParam("archive");
        $search = Zb::$app->request->getQueryParam("search");
        $tags = Zb::$app->request->getQueryParam("tags");

        $postFilter = new PostFilter();
        if ($archive) {
            $archive = explode("-", $archive);
            $postFilter->year = intval($archive[0]);
            count($archive) >= 2 && $postFilter->month = intval($archive[1]);
        }
        if ($search) {
            $postFilter->search = $search;
        }
        if ($tags) {
            $postFilter->tags = $tags;
        }
        return $this->render("index", Post::getPageList($perPage, $postFilter));
    }

    public function actionDetail($pid)
    {
        $postMod = Post::findOne($pid);
        if (empty($postMod)) {
            throw new NotFoundHttpException();
        }
        $postPrev = Post::find()->where(["<", "id", $pid])->orderBy("created_at desc")->one();
        $postNext = Post::find()->where([">", "id", $pid])->orderBy("created_at desc")->one();
        return $this->render("detail", ['postMod' => $postMod, 'postPrev' => $postPrev, 'postNext' => $postNext]);
    }
}