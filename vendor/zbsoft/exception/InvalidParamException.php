<?php
/**
 * @link https://github.com/JohnZhang360/Simple-Yii2.git
 */

namespace zbsoft\exception;

/**
 * 错误参数异常
 * @author JohnZhang360
 */
class InvalidParamException extends \BadMethodCallException
{
    /**
     * @return string the user-friendly name of this exception
     */
    public function getName()
    {
        return 'Invalid Parameter';
    }
}