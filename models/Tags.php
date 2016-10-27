<?php
namespace app\models;

use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator;
use zbsoft\db\ActiveRecord;
use zbsoft\helpers\Pagination;

/**
 * This is the model class for table "{{%tags}}".
 *
 * @property integer $id
 * @property string $tag_name
 * @property Post[] $posts
 */
class Tags extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%tags}}';
    }

    /**
     * 获取标签对应的文章
     */
    public function getPosts()
    {
        return $this->hasMany(Post::className(), ['id' => 'post_id'])->viaTable('zb_post_tags', ['tags_id' => 'id']);
    }

    /**
     * 获取分页列表
     * @param int $perPage
     * @return array
     */
    public static function getPageList($perPage = 10)
    {
        $data = Tags::find();
        $pager = new Pagination(['totalCount' => $data->count(), 'pageSize' => $perPage]);
        $model = $data->offset($pager->offset)->limit($pager->limit)->all();
        return ["tagsList" => $model, "pager" => $pager];
    }

    /**
     * 验证提交信息
     * @return bool
     */
    public function validate()
    {
        try {
            Validator::stringType()->notEmpty()->length(1, 30)->assert($this->tag_name);
            return true;
        } catch (NestedValidationException $exception) {
            $this->errors = $exception->getMessages();
            return false;
        }
    }
}