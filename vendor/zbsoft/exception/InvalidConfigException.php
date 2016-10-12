<?php
/**
 * @link https://github.com/JohnZhang360/Simple-Yii2.git
 */

namespace zbsoft\exception;

/**
 * 配置错误一场
 * @author JohnZhang360
 */
class InvalidConfigException extends \Exception
{
    /**
     * @return string the user-friendly name of this exception
     */
    public function getName()
    {
        return 'Invalid Configuration';
    }
}