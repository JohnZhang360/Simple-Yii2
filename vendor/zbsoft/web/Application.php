<?php
/**
 * @link https://github.com/JohnZhang360/zgjian-framework
 */

namespace zbsoft\web;

use NoahBuscher\Macaw\Macaw;
use zbsoft\base\Object;
use zbsoft\web\Request;

class Application extends Object
{
    /**
     * 默认controller路由
     * @var string
     */
    public $defaultRoute = 'site';

    /**
     * 框架核心类(必须加载)
     * @return array
     */
    public function coreComponents()
    {
        return [
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
        $request = new Request();
        $queryParams = $request->getQueryParams();

        if ($this->enablePrettyUrl) {
            Macaw::get('fuck', function () {
                echo "成功！";
            });

            Macaw::get('(:all)', function ($fu) {
                echo '未匹配到路由<br>' . $fu;
            });

            Macaw::dispatch();
        } else {
            var_dump($queryParams);
            exit;
        }
    }
}