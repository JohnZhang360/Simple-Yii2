<?php
namespace app\models;

use zbsoft\db\ActiveRecord;

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
}