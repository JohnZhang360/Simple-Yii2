<?php
/**
 * @link https://github.com/JohnZhang360/zgjian-framework
 */

namespace zbsoft\base;

use Zb;
use zbsoft\exception\InvalidConfigException;
use zbsoft\exception\InvalidParamException;

class Application extends Module
{
    /**
     * @var string 默认controller路由
     */
    public $defaultRoute = 'site';

    /**
     * @var string 请求路由规则
     */
    public $requestedRoute;

    /**
     * @var array 需要加载的模块名
     */
    public $loadedModules = [];

    /**
     * @var string 模块根目录
     */
    private $_basePath;

    /**
     * @var Controller 当前控制器
     */
    public $controller;

    /**
     * @var string 主模块默认控制器命名空间
     */
    public $controllerNamespace = 'app\\controllers';

    /**
     * @return array 框架核心类(必须加载)
     */
    public function coreComponents()
    {
        return [
            'request' => ['class' => 'zbsoft\base\Request'],
            'urlManager' => ['class' => 'zbsoft\base\UrlManager'],
        ];
    }

    /**
     * 设置应用根目录以及@app别名的配置
     * @param string $path the root directory of the application.
     * @property string the root directory of the application.
     * @throws InvalidParamException if the directory does not exist.
     */
    public function setBasePath($path)
    {
        $path = Zb::getAlias($path);
        $p = strncmp($path, 'phar://', 7) === 0 ? $path : realpath($path);
        if ($p !== false && is_dir($p)) {
            $this->_basePath = $p;
        } else {
            throw new InvalidParamException("The directory does not exist: $path");
        }

        Zb::setAlias('@app', $this->getBasePath());
    }

    /**
     * 返回模块根目录
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

    public function __construct($config = [])
    {
        Zb::$app = $this;
        $this->setInstance($this);

        $this->preInit($config);

        parent::__construct($config);
    }

    /**
     * 将核心类预加载到配置变量中
     * @param $config
     * @throws InvalidConfigException
     */
    public function preInit(&$config)
    {
        if (isset($config['basePath'])) {
            $this->setBasePath($config['basePath']);
            unset($config['basePath']);
        } else {
            throw new InvalidConfigException('The "basePath" configuration for the Application is required.');
        }

        foreach ($this->coreComponents() as $id => $component) {
            if (!isset($config['components'][$id])) {
                $config['components'][$id] = $component;
            } elseif (is_array($config['components'][$id]) && !isset($config['components'][$id]['class'])) {
                $config['components'][$id]['class'] = $component['class'];
            }
        }
    }

    public function run()
    {
        list($route, $params) = $this->getRequest()->resolve();
        $response = new Response();
        $response->content = $this->runAction($route, $params);
        $response->send();
    }

    /**
     * 获取地址管理实例
     * @return \zbsoft\base\UrlManager the URL manager for this application.
     */
    public function getUrlManager()
    {
        return $this->get('urlManager');
    }

    /**
     * 获取请求实例
     * @return \zbsoft\base\Request the request component.
     */
    public function getRequest()
    {
        return $this->get('request');
    }

    /**
     * 获取响应实例
     * @return null|object
     * @throws \zbsoft\exception\InvalidConfigException
     */
    public function getResponse()
    {
        return $this->get("response");
    }
}