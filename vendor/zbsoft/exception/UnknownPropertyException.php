<?php
/**
 * @link https://github.com/JohnZhang360/Simple-Yii2.git
 */

namespace zbsoft\exception;

/**
 * 未知属性异常类
 * @author JohnZhang360
 */
class UnknownPropertyException extends \Exception
{
    /**
     * @return string the user-friendly name of this exception
     */
    public function getName()
    {
        return 'Unknown Property';
    }
}