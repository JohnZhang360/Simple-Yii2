<?php

namespace zbsoft\base;

/**
 * 代表控制器中的一个ACTION对象
 *
 * @property string $uniqueId The unique ID of this action among the whole application. This property is
 * read-only.
 */
class Action extends Object
{
    /**
     * @var string ID of the action
     */
    public $id;
    /**
     * @var Controller|Controller the controller that owns this action
     */
    public $controller;
    /**
     * @var string the controller method that this inline action is associated with
     */
    public $actionMethod;

    /**
     * @param string $id the ID of this action
     * @param Controller $controller the controller that owns this action
     * @param string $actionMethod the controller method that this inline action is associated with
     * @param array $config name-value pairs that will be used to initialize the object properties
     */
    public function __construct($id, $controller, $actionMethod, $config = [])
    {
        $this->id = $id;
        $this->controller = $controller;
        $this->actionMethod = $actionMethod;
        parent::__construct($config);
    }

    /**
     * 返回action的标示值
     * Returns the unique ID of this action among the whole application.
     *
     * @return string the unique ID of this action among the whole application.
     */
    public function getUniqueId()
    {
        return $this->controller->getUniqueId() . '/' . $this->id;
    }

    /**
     * 使用$params参数执行ACTION方法，主要在controller中调用
     * bindActionParams方法可能在不同类型的controller中处理的方式不同
     * @param array $params action parameters
     * @return mixed the result of the action
     */
    public function runWithParams($params)
    {
        $args = $this->controller->bindActionParams($this, $params);
        return call_user_func_array([$this->controller, $this->actionMethod], $args);
    }
}