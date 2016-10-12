<?php
namespace app\models;

use zbsoft\db\ActiveRecord;

/**
 * This is the model class for table "{{%post}}".
 *
 * @property string $id
 * @property string $title
 * @property string $content
 * @property string $created_at
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