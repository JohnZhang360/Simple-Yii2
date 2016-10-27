<?php
namespace app\models;

use Zb;
use zbsoft\db\ActiveRecord;

/**
 * This is the model class for table "{{%config}}".
 *
 * @property string $variable
 * @property string $value
 */
class Config extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%config}}';
    }

    /**
     * 获取所有配置项
     * @return array
     */
    public static function getAll()
    {
        $allConfig = [];
        /* @var Config[] $configList */
        $configList = Config::find()->all();
        foreach ($configList as $config) {
            $allConfig[$config->variable] = $config->value;
        }
        return $allConfig;
    }

    /**
     * 获取所有配置项（缓存）
     * @return array|mixed
     */
    public static function getAllByCache()
    {
        $configCache = Zb::$app->cache->get("system_config");
        if($configCache){
            return $configCache;
        }else{
            $allConfig = self::getAll();
            Zb::$app->cache->set("system_config", $allConfig);
            return $allConfig;
        }
    }

    /**
     * 更新配置项
     * @param $post
     * @return bool
     */
    public static function savePost($post)
    {
        $allCache = self::getAll();
        foreach ($post as $key => $val) {
            if(isset($allCache[$key])){
                $configMod = Config::find()->where("variable=:variable", [":variable"=>$key])->one();
                $configMod->value = $val;
                $configMod->save();
            }else{
                $configMod = new Config();
                $configMod->variable = $key;
                $configMod->value = $val;
                $configMod->insert();
            }
        }
        $allConfig = self::getAll();
        Zb::$app->cache->set("system_config", $allConfig);
        return true;
    }
}