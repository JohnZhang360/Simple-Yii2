<?php
/**
 * @link https://github.com/JohnZhang360/zgjian-framework
 */

namespace zbsoft\base;

class Controller extends Object
{
    /**
     * @var string the ID of this controller.
     */
    public $id;
    /**
     * @var Module $module the module that this controller belongs to.
     */
    public $module;

    /**
     * @param string $id the ID of this controller.
     * @param Module $module the module that this controller belongs to.
     * @param array $config name-value pairs that will be used to initialize the object properties.
     */
    public function __construct($id, $module, $config = [])
    {
        $this->id = $id;
        $this->module = $module;
        parent::__construct($config);
    }

    /**
     * 运行controller里面的action方法
     * @param $id
     * @param array $params
     */
    public function runAction($id, $params = [])
    {
        var_dump($id);
        echo "<pre>";
        var_dump($params);
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