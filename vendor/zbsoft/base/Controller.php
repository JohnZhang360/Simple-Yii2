<?php
/**
 * @link https://github.com/JohnZhang360/zgjian-framework
 */

namespace zbsoft\base;

use Zb;
use zbsoft\exception\BadRequestHttpException;
use zbsoft\exception\InvalidRouteException;

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
     * @var string the ID of the action that is used when the action ID is not specified
     * in the request. Defaults to 'index'.
     */
    public $defaultAction = 'index';
    /**
     * @var View the view object that can be used to render views or view files.
     */
    private $_view;
    /**
     * @var string the root directory that contains view files for this controller.
     */
    private $_viewPath;

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
     * 返回控制器的标示值
     * Returns the unique ID of the controller.
     * @return string the controller ID that is prefixed with the module ID (if any).
     */
    public function getUniqueId()
    {
        return $this->module instanceof Application ? $this->id : $this->module->getUniqueId() . '/' . $this->id;
    }

    /**
     * 运行controller里面的action方法
     * @param $id
     * @param array $params
     * @return mixed
     * @throws InvalidRouteException
     */
    public function runAction($id, $params = [])
    {
        $action = $this->createAction($id);
        if ($action === null) {
            throw new InvalidRouteException('Unable to resolve the request: ' . $this->getUniqueId() . '/' . $id);
        }

        // run the action
        return $action->runWithParams($params);
    }

    /**
     * 创建action实例
     * action中以驼峰命名法，在URL中以"-"号隔开
     * @param string $id the action ID.
     * @return Action the newly created action instance. Null if the ID doesn't resolve into any action.
     */
    public function createAction($id)
    {
        if ($id === '') {
            $id = $this->defaultAction;
        }

        $methodName = 'action' . str_replace(' ', '', ucwords(implode(' ', explode('-', $id))));
        if (method_exists($this, $methodName)) {
            $method = new \ReflectionMethod($this, $methodName);
            if ($method->isPublic() && $method->getName() === $methodName) {
                return new Action($id, $this, $methodName);
            }
        }

        return null;
    }

    /**
     * 绑定URL参数到ACTION方法中，检查参数是否对应ACTION所要求的参数
     * 如果在绑定ACTION的参数中，存在未赋值且无默认值的，抛出异常
     * @param \zbsoft\base\Action $action the action to be bound with parameters
     * @param array $params the parameters to be bound to the action
     * @return array the valid parameters that the action can run with.
     * @throws BadRequestHttpException if there are missing or invalid parameters.
     */
    public function bindActionParams($action, $params)
    {
        $method = new \ReflectionMethod($this, $action->actionMethod);

        $args = [];
        $missing = [];
        foreach ($method->getParameters() as $param) {
            $name = $param->getName();
            if (array_key_exists($name, $params)) {
                if ($param->isArray()) {
                    $args[] = (array)$params[$name];
                } elseif (!is_array($params[$name])) {
                    $args[] = $params[$name];
                } else {
                    throw new BadRequestHttpException('Invalid data received for parameter "' . $name . '".');
                }
                unset($params[$name]);
            } elseif ($param->isDefaultValueAvailable()) {
                $args[] = $param->getDefaultValue();
            } else {
                $missing[] = $name;
            }
        }

        if (!empty($missing)) {
            throw new BadRequestHttpException('Missing required parameters: ' . implode(', ', $missing));
        }

        return $args;
    }

    /**
     * 获取view对象并存到该对象中
     * @return View|\zbsoft\base\View the view object that can be used to render views or view files.
     */
    public function getView()
    {
        if ($this->_view === null) {
            $this->_view = Zb::$app->getView();
        }
        return $this->_view;
    }

    /**
     * 生成$view中指定的模板
     * @param $view
     * @param array $params
     * @return string
     */
    public function render($view, $params = [])
    {
        return $this->getView()->render($view, $params, $this);
    }

    /**
     * 获取该*控制器*的视图路径
     * @return string the directory containing the view files for this controller.
     */
    public function getViewPath()
    {
        if ($this->_viewPath === null) {
            $this->_viewPath = $this->module->getViewPath() . DIRECTORY_SEPARATOR . $this->id;
        }
        return $this->_viewPath;
    }
}