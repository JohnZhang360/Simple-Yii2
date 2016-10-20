<?php
namespace app\models;

use Zb;
use zbsoft\db\ActiveRecord;

/**
 * This is the model class for table "{{%admin}}".
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

    /**
     * 是否登录
     * @return bool
     */
    public static function isLogin()
    {
        return Zb::$app->session->has("_adminId") === true;
    }

    /**
     * 登录成功后的操作
     * @return bool
     */
    public function loginSuccess()
    {
        $this->login_at = time();
        $this->login_ip = Zb::$app->request->userIP;
        if($this->save()){
            Zb::$app->session->set("_adminId", $this->id);
            return true;
        }else{
            return false;
        }
    }
}