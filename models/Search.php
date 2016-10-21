<?php
namespace app\models;

use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator;
use zbsoft\db\ActiveRecord;
use zbsoft\helpers\Pagination;

/**
 * This is the model class for table "{{%search}}".
 *
 * @property integer $id
 * @property string $title
 * @property string $pic
 * @property string $description
 * @property integer $created_at
 * @property integer $sort
 * @property integer $is_show
 */
class Search extends ActiveRecord
{
    const IS_SHOW_YES = 1;
    const IS_SHOW_NO = 0;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%search}}';
    }

    /**
     * 获取分页列表
     * @param int $pageSize
     * @return array|\zbsoft\db\ActiveRecord[]
     */
    public static function getPageList($pageSize = 10)
    {
        $data = Search::find();
        $pager = new Pagination(['totalCount' => $data->count(), 'pageSize' => $pageSize]);
        $model = $data->offset($pager->offset)->limit($pager->limit)->orderBy("created_at desc")->all();
        return ["searchList" => $model, "pager" => $pager];
    }

    /**
     * 验证提交信息
     * @return bool
     */
    public function validate()
    {
        try {
            Validator::stringType()->notEmpty()->length(1, 30)->assert($this->title);
            Validator::stringType()->notEmpty()->length(1,200)->assert($this->pic);
            Validator::digit()->notEmpty()->assert($this->sort);
            Validator::in([0, 1])->assert($this->is_show);
            return true;
        } catch (NestedValidationException $exception) {
            $this->errors = $exception->getMessages();
            return false;
        }
    }
}