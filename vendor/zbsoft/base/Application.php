<?php
/**
 * @link https://github.com/JohnZhang360/Simple-Yii2.git
 */

namespace zbsoft\base;

use Zb;
use zbsoft\exception\InvalidConfigException;
use zbsoft\exception\InvalidParamException;
use zbsoft\exception\InvalidRouteException;
use zbsoft\exception\NotFoundHttpException;
use zbsoft\helpers\Security;

/**
 * Application is the base class for all application classes.
 *
 * @property string $basePath The root directory of the application.
 * @property \zbsoft\caching\Cache $cache The cache application component. Null if the component is not enabled.
 * This property is read-only.
 * @property \zbsoft\db\Connection $db The database connection. This property is read-only.
 * @property \zbsoft\base\Request $request The request component. This property is read-only.
 * @property \zbsoft\base\Response $response The response component. This property is
 * read-only.
 * @property string $runtimePath The directory that stores runtime files. Defaults to the "runtime"
 * subdirectory under [[basePath]].
 * @property string $timeZone The time zone used by this application.
 * @property string $uniqueId The unique ID of the module. This property is read-only.
 * @property Security $security The security application component. This property is read-only.
 * @property \zbsoft\base\UrlManager $urlManager The URL manager for this application. This property is read-only.
 * @property string $vendorPath The directory that stores vendor files. Defaults to "vendor" directory under
 * [[basePath]].
 * @property View|\zbsoft\base\View $view The view application component that is used to render various view
 * files. This property is read-only.
 * @property Session $session The session component. This property is read-only.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
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
     * @var string the charset currently used for the application.
     */
    public $charset = 'UTF-8';
    /**
     * @var string|boolean the layout that should be applied for views in this application. Defaults to 'main'.
     * If this is false, layout will be disabled.
     */
    public $layout = 'main';

    /**
     * @return array 框架核心类(必须加载)
     */
    public function coreComponents()
    {
        return [
            'view' => ['class' => 'zbsoft\base\View'],
            'request' => ['class' => 'zbsoft\base\Request'],
            'response' => ['class' => 'zbsoft\base\Response'],
            'urlManager' => ['class' => 'zbsoft\base\UrlManager'],
            'security' => ['class' => 'zbsoft\helpers\Security'],
            'session' => ['class' => 'zbsoft\base\Session'],
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

        Object::__construct($config);
    }

    /**
     * 将核心类预加载到配置变量中待初始化
     * @param $config
     * @throws InvalidConfigException
     */
    public function preInit(&$config)
    {
        if (!isset($config['id'])) {
            throw new InvalidConfigException('The "id" configuration for the Application is required.');
        }
        if (isset($config['basePath'])) {
            $this->setBasePath($config['basePath']);
            unset($config['basePath']);
        } else {
            throw new InvalidConfigException('The "basePath" configuration for the Application is required.');
        }
        if (isset($config['runtimePath'])) {
            $this->setRuntimePath($config['runtimePath']);
            unset($config['runtimePath']);
        } else {
            // set "@runtime"
            $this->getRuntimePath();
        }

        foreach ($this->coreComponents() as $id => $component) {
            if (!isset($config['components'][$id])) {
                $config['components'][$id] = $component;
            } elseif (is_array($config['components'][$id]) && !isset($config['components'][$id]['class'])) {
                $config['components'][$id]['class'] = $component['class'];
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        $request = $this->getRequest();
        Zb::setAlias('@webroot', dirname($request->getScriptFile()));
        Zb::setAlias('@web', $request->getBaseUrl());
    }

    public function run()
    {
        try {
            list($route, $params) = $this->getRequest()->resolve();
            $response = $this->getResponse();
            $response->content = $this->runAction($route, $params);
            $response->send();
        } catch (InvalidRouteException $e) {
            throw new NotFoundHttpException('Page not found.', $e->getCode(), $e);
        }
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

    /**
     * 获取视图对象
     * @return \zbsoft\base\View the view application component that is used to render various view files.
     */
    public function getView()
    {
        return $this->get('view');
    }

    private $_runtimePath;

    /**
     * Returns the directory that stores runtime files.
     * @return string the directory that stores runtime files.
     * Defaults to the "runtime" subdirectory under [[basePath]].
     */
    public function getRuntimePath()
    {
        if ($this->_runtimePath === null) {
            $this->setRuntimePath($this->getBasePath() . DIRECTORY_SEPARATOR . 'runtime');
        }

        return $this->_runtimePath;
    }

    /**
     * Sets the directory that stores runtime files.
     * @param string $path the directory that stores runtime files.
     */
    public function setRuntimePath($path)
    {
        $this->_runtimePath = Zb::getAlias($path);
        Zb::setAlias('@runtime', $this->_runtimePath);
    }

    /**
     * Returns the database connection component.
     * @return \zbsoft\db\Connection the database connection.
     */
    public function getDb()
    {
        return $this->get('db');
    }

    private $_homeUrl;

    /**
     * @return string the homepage URL
     */
    public function getHomeUrl()
    {
        if ($this->_homeUrl === null) {
            return $this->getRequest()->getBaseUrl() . '/';
        } else {
            return $this->_homeUrl;
        }
    }

    /**
     * @param string $value the homepage URL
     */
    public function setHomeUrl($value)
    {
        $this->_homeUrl = $value;
    }

    /**
     * Returns the security component.
     * @return Security the security application component.
     */
    public function getSecurity()
    {
        return $this->get('security');
    }

    /**
     * Returns the session component.
     * @return Session the session component.
     */
    public function getSession()
    {
        return $this->get('session');
    }

    /**
     * Returns an ID that uniquely identifies this module among all modules within the current application.
     * Since this is an application instance, it will always return an empty string.
     * @return string the unique ID of the module.
     */
    public function getUniqueId()
    {
        return '';
    }
}