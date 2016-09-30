<?php
/**
 * @link https://github.com/JohnZhang360/zgjian-framework
 */

namespace zbsoft\base;

/**
 * 响应请求
 * @author JohnZhang360
 */
class Response extends Object
{
    /**
     * @var string 响应内容
     */
    public $content;

    /**
     * 发送内容到客户端
     */
    public function send()
    {
        echo $this->content;
    }
}