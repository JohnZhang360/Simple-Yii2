<?php
/**
 * @link https://github.com/JohnZhang360/zgjian-framework
 */

namespace zbsoft\base;

class Controller extends Object
{
    /**
     * 运行controller里面的action方法
     * @param $id
     * @param array $params
     */
    public function runAction($id, $params = [])
    {
        echo "runAction";
        exit;
    }

    /**
     * 生成模板
     * @param $template
     * @param $params
     */
    public function render($template, $params = [])
    {

    }
}