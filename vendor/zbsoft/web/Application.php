<?php
/**
 * @link https://github.com/JohnZhang360/zgjian-framework
 */

namespace zbsoft\web;

use zbsoft\di\ServiceLocator;

class Application extends ServiceLocator
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
     * @return array 框架核心类(必须加载)
     */
    public function coreComponents()
    {
        return [
            'request' => ['class' => 'zbsoft\web\Request'],
            'urlManager' => ['class' => 'zbsoft\web\UrlManager'],
        ];
    }

    public function __construct($config = [])
    {
        $this->preInit($config);
        parent::__construct($config);
    }

    /**
     * 将核心类预加载到配置变量中
     * @param $config
     */
    public function preInit(&$config)
    {
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
        $resole = $this->getRequest()->resolve();
        $response = new Response();
        $response->content = $this->runAction($resole);
        $response->send();
    }

    /**
     * 运行请求的controller/action
     */
    public function runAction($resole)
    {
        return "Hello World";
    }

    /**
     * 获取地址管理实例
     * @return \zbsoft\web\UrlManager the URL manager for this application.
     */
    public function getUrlManager()
    {
        return $this->get('urlManager');
    }

    /**
     * 获取请求实例
     * @return \zbsoft\web\Request the request component.
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