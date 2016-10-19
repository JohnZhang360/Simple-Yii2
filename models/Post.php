<?php
namespace app\models;

use zbsoft\db\ActiveRecord;

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
 */
class Post extends ActiveRecord
{
    const IS_SHOW_YES = 1;
    const IS_SHOW_NO = 0;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%post}}';
    }
}