<?php
namespace app\models;

use zbsoft\db\ActiveRecord;

/**
 * This is the model class for table "{{%tags}}".
 *
 * @property integer $id
 * @property string $tag_name
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
}