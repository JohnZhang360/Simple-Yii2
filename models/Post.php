<?php
namespace app\models;

use Zb;
use zbsoft\db\ActiveQuery;
use zbsoft\db\ActiveRecord;
use zbsoft\helpers\Pagination;
use Respect\Validation\Validator;
use Respect\Validation\Exceptions\NestedValidationException;

/**
 * This is the model class for table "{{%post}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $content
 * @property string $summary
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $sort
 * @property integer $is_show
 * @property integer $views
 * @property integer $is_delete
 * @property Tags[] $tags
 */
class Post extends ActiveRecord
{
    const IS_SHOW_YES = 1;
    const IS_SHOW_NO = 0;

    const IS_DELETE_YES = 1;
    const IS_DELETE_NO = 0;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%post}}';
    }

    /**
     * 获取文章管理的Tags
     */
    public function getTags()
    {
        return $this->hasMany(Tags::className(), ['id' => 'tags_id'])->viaTable('zb_post_tags', ['post_id' => 'id']);
    }

    /**
     * 获取分页列表
     * @param int $perPage
     * @param PostFilter $postFilter
     * @return array
     */
    public static function getPageList($perPage = 10, $postFilter = null)
    {
        $data = Post::find()->where("is_delete=:is_delete", [":is_delete" => Post::IS_DELETE_NO]);

        if ($postFilter != null) {
            if ($postFilter->year != null) {
                if ($postFilter->month == null) {
                    $filterTime["begin"] = strtotime($postFilter->year . "-01-01");
                    $filterTime["end"] = strtotime($postFilter->year . "-12-31") + (24 * 60 * 60 - 1);
                } else {
                    $filterTime["begin"] = strtotime($postFilter->year . "-" . $postFilter->month . "-01");
                    $filterTime["end"] = strtotime($postFilter->year . "-" . $postFilter->month . "-01 +1 month -1 day") + (24 * 60 * 60 - 1);
                }
                $data->andFilterWhere(['between', 'created_at', $filterTime["begin"], $filterTime["end"]]);
            }

            if ($postFilter->search != null) {
                $data->andFilterWhere([
                    'or',
                    ['like', 'title', $postFilter->search],
                    ['like', 'summary', $postFilter->search]
                ]);
            }

            if ($postFilter->tags != null) {
                $tagsMod = Tags::find()->where("tag_name=:tag_name", [":tag_name"=>$postFilter->tags])->one();
                if($tagsMod){
                    $relatePosts = [];
                    foreach ($tagsMod->posts as $post){
                        array_push($relatePosts, $post->id);
                    }
                    $data->andWhere(["in", "id", $relatePosts]);
                }
            }
        }

        $pager = new Pagination(['totalCount' => $data->count(), 'pageSize' => $perPage]);
        $model = $data->offset($pager->offset)->limit($pager->limit)->orderBy("created_at desc")->all();
        return ["postList" => $model, "pager" => $pager];
    }

    /**
     * 验证提交信息
     * @return bool
     */
    public function validate()
    {
        try {
            Validator::stringType()->notEmpty()->length(1, 100)->assert($this->title);
            Validator::stringType()->notEmpty()->assert($this->content);
            Validator::digit()->notEmpty()->assert($this->sort);
            Validator::in([0, 1])->assert($this->is_show);
            return true;
        } catch (NestedValidationException $exception) {
            $this->errors = $exception->getMessages();
            return false;
        }
    }

    /**
     * 刷新归档日期缓存
     */
    public static function refreshArchives()
    {
        $archivesSql = "SELECT * FROM (SELECT FROM_UNIXTIME(created_at, '%Y年%c月') month_archives FROM zb_post ORDER BY created_at DESC) ma GROUP BY ma.month_archives;";
        $archivesCommand = Zb::$app->db->createCommand($archivesSql);
        $monthArchives = array_map(function ($row) {
            return $row["month_archives"];
        }, $archivesCommand->queryAll());
        Zb::$app->cache->set("monthArchives", $monthArchives);
    }
}