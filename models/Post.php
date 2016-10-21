<?php
namespace app\models;

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
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $sort
 * @property integer $is_show
 * @property integer $views
 * @property integer $is_delete
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
     * 获取分页列表
     * @param int $pageSize
     * @return array|\zbsoft\db\ActiveRecord[]
     */
    public static function getPageList($pageSize = 10)
    {
        $data = Post::find()->where("is_delete=:is_delete", [":is_delete" => Post::IS_DELETE_NO]);
        $pager = new Pagination(['totalCount' => $data->count(), 'pageSize' => $pageSize]);
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
}