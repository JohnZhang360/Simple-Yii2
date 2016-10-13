<?php
namespace app\models;

use zbsoft\db\ActiveRecord;

/**
 * This is the model class for table "{{%post_tags}}".
 *
 * @property integer $post_id
 * @property integer $tags_id
 */
class PostTags extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%post_tags}}';
    }
}