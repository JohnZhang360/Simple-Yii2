<?php
namespace app\models;

use zbsoft\db\ActiveRecord;

/**
 * This is the model class for table "{{%post}}".
 *
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property integer $login_at
 * @property string $login_ip
 */
class Admin extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%admin}}';
    }
}