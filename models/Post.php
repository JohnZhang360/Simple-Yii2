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
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%post}}';
    }
}