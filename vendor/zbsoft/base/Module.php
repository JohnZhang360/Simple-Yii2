<?php

namespace zbsoft\base;

use Zb;
use zbsoft\di\ServiceLocator;
use zbsoft\exception\InvalidParamException;
use zbsoft\exception\InvalidRouteException;

/**
 * 模块以及应用基类
 * 通过命名空间限制，子模块具有自己的MVC特性
 */
class Module extends ServiceLocator
{
    /**
     * @var string 默认的controller命名空间
     */
    public $controllerNamespace;
    /**
     * @var string 默认controller路由
     */
    public $defaultRoute = 'default';
    /**
     * @var array 该模块的子模块
     */
    private $_modules = [];
    /**
     * @var array 已加载的控制器
     */
    public $controllerMap = [];
    /**
     * @var string an ID that uniquely identifies this module among other modules which have the same [[module|parent]].
     */
    public $id;
    /**
     * @var Module the parent module of this module. Null if this module does not have a parent.
     */
    public $module;
    /**
     * @var string the root directory that contains layout view files for this module.
     */
    private $_layoutPath;
    /**
     * @var string the root directory that contains view files for this module
     */
    private $_viewPath;
    /**
     * @var string the root directory of the module.
     */
    private $_basePath;
    /**
     * @var string|boolean the layout that should be applied for views within this module. This refers to a view name
     * relative to [[layoutPath]]. If this is not set, it means the layout value of the [[module|parent module]]
     * will be taken. If this is false, layout will be disabled within this module.
     */
    public $layout;

    /**
     * Constructor.
     * @param string $id the ID of this module
     * @param Module $parent the parent module (if any)
     * @param array $config name-value pairs that will be used to initialize the object properties
     */
    public function __construct($id, $parent = null, $config = [])
    {
        $this->id = $id;
        $this->module = $parent;
        parent::__construct($config);
    }

    /**
     * 初始化模块
     * 比如控制器命令空间，因为每个子模块的命名空间不同
     */
    public function init()
    {
        if ($this->controllerNamespace === null) {
            $class = get_class($this);
            if (($pos = strrpos($class, '\\')) !== false) {
                $this->controllerNamespace = substr($class, 0, $pos) . '\\controllers';
            }
        }
    }

    /**
     * 返回模块的标识值
     * Returns an ID that uniquely identifies this module among all modules within the current application.
     * Note that if the module is an application, an empty string will be returned.
     * @return string the unique ID of the module.
     */
    public function getUniqueId()
    {
        return $this->module ? ltrim($this->module->getUniqueId() . '/' . $this->id, '/') : $this->id;
    }

    /**
     * 返回当前请求模块
     * @return static|null
     */
    public static function getInstance()
    {
        $class = get_called_class();
        return isset(Zb::$app->loadedModules[$class]) ? Zb::$app->loadedModules[$class] : null;
    }

    /**
     * 设置当前请求模块实例到集合中
     * @param Module|null
     */
    public static function setInstance($instance)
    {
        if ($instance === null) {
            unset(Zb::$app->loadedModules[get_called_class()]);
        } else {
            Zb::$app->loadedModules[get_class($instance)] = $instance;
        }
    }

    /**
     * 运行请求的controller/action
     * @param $route
     * @param $params
     * @return mixed
     * @throws InvalidRouteException
     */
    public function runAction($route, $params)
    {
        $parts = $this->createController($route);
        if (is_array($parts)) {
            /* @var $controller Controller */
            list($controller, $actionID) = $parts;
            $oldController = Zb::$app->controller;
            Zb::$app->controller = $controller;
            $result = $controller->runAction($actionID, $params);
            Zb::$app->controller = $oldController;

            return $result;
        } else {
            throw new InvalidRouteException('Unable to resolve the request "' . $route . '".');
        }
    }

    /**
     * 根据以下规则创建控制器实例
     *
     * 1.如果路由为空，则使用[defaultRoute]配置
     * 2.如果第一个解析到时模块，则调用该模块的`createController`方法
     * 3.如果第一个解析已存在[controllerMap]中，则直接调用
     * 4.如果给出的路由是以`abc/def/xyz`形式的，则会尝试`abc\DefController`和`abc\def\XyzController`两种路径
     *
     * 如果匹配出路由，将会返回控制器以及剩余部分的路由作为action
     *
     * @param string $route the route consisting of module, controller and action IDs.
     * @return array|boolean If the controller is created successfully, it will be returned together
     * with the requested action ID. Otherwise false will be returned.
     */
    public function createController($route)
    {
        if ($route === '') {
            $route = $this->defaultRoute;
        }

        // double slashes or leading/ending slashes may cause substr problem
        $route = trim($route, '/');
        if (strpos($route, '//') !== false) {
            return false;
        }

        if (strpos($route, '/') !== false) {
            list ($id, $route) = explode('/', $route, 2);
        } else {
            $id = $route;
            $route = '';
        }

        // module and controller map take precedence
        if (isset($this->controllerMap[$id])) {
            $controller = Zb::createObject($this->controllerMap[$id], [$id, $this]);
            return [$controller, $route];
        }
        $module = $this->getModule($id);
        if ($module !== null) {
            return $module->createController($route);
        }

        $controller = $this->createControllerByID($id);

        return $controller === null ? false : [$controller, $route];
    }

    /**
     * 根据路由路径循环获取各个子模块
     * @param string $id module ID (case-sensitive). To retrieve grand child modules,
     * use ID path relative to this module (e.g. `admin/content`).
     * @param boolean $load whether to load the module if it is not yet loaded.
     * @return Module|null the module instance, null if the module does not exist.
     * @see hasModule()
     */
    public function getModule($id, $load = true)
    {
        if (($pos = strpos($id, '/')) !== false) {
            // sub-module
            $module = $this->getModule(substr($id, 0, $pos));

            return $module === null ? null : $module->getModule(substr($id, $pos + 1), $load);
        }

        if (isset($this->_modules[$id])) {
            if ($this->_modules[$id] instanceof Module) {
                return $this->_modules[$id];
            } elseif ($load) {
                /* @var $module Module */
                $module = Zb::createObject($this->_modules[$id], [$id, $this]);
                $module->setInstance($module);
                return $this->_modules[$id] = $module;
            }
        }

        return null;
    }

    /**
     * 根据路径生成控制器实例
     * @param string $id the controller ID
     * @return Controller the newly created controller instance, or null if the controller ID is invalid.
     * This exception is only thrown when in debug mode.
     */
    public function createControllerByID($id)
    {
        $className = $id;
        if (!preg_match('%^[a-z][a-z0-9\\-_]*$%', $className)) {
            return null;
        }

        $className = str_replace(' ', '', ucwords(str_replace('-', ' ', $className))) . 'Controller';
        $className = ltrim($this->controllerNamespace . '\\' . $className, '\\');
        if (strpos($className, '-') !== false || !class_exists($className)) {
            return null;
        }

        if (is_subclass_of($className, 'zbsoft\base\Controller')) {
            $controller = Zb::createObject($className, [$id, $this]);
            return get_class($controller) === $className ? $controller : null;
        } else {
            return null;
        }
    }

    /**
     * 返回*当前模块*的视图路径
     * @return string the root directory of view files. Defaults to "[[basePath]]/views".
     */
    public function getViewPath()
    {
        if ($this->_viewPath === null) {
            $this->_viewPath = $this->getBasePath() . DIRECTORY_SEPARATOR . 'views';
        }
        return $this->_viewPath;
    }

    /**
     * 获取*当前模块*中的根路径
     * @return string the root directory of the module.
     */
    public function getBasePath()
    {
        if ($this->_basePath === null) {
            $class = new \ReflectionClass($this);
            $this->_basePath = dirname($class->getFileName());
        }

        return $this->_basePath;
    }

    /**
     * 返回当前模块的布局路径
     * @return string the root directory of layout files. Defaults to "[[viewPath]]/layouts".
     */
    public function getLayoutPath()
    {
        if ($this->_layoutPath !== null) {
            return $this->_layoutPath;
        } else {
            return $this->_layoutPath = $this->getViewPath() . DIRECTORY_SEPARATOR . 'layouts';
        }
    }

    /**
     * 设置当前模块的布局路径
     * @param string $path the root directory or path alias of layout files.
     * @throws InvalidParamException if the directory is invalid
     */
    public function setLayoutPath($path)
    {
        $this->_layoutPath = Zb::getAlias($path);
    }
}