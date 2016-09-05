<?php
/**
 * @link https://github.com/JohnZhang360/zgjian-framework
 */

namespace zbsoft\web;

class UrlManager
{
    public $enablePrettyUrl = false;

    /**
     * @var string enablePrettyUrl为false的时候使用，作为获取路由的参数
     */
    public $routeParam = 'r';

    /**
     * 解析请求路由
     * @param Request $request
     * @return array|bool
     */
    public function parseRequest($request)
    {
        if ($this->enablePrettyUrl) {
            //do prettyUrl logical example:site/index
            return [];
        } else {
            $route = $request->getQueryParam($this->routeParam, '');
            if (is_array($route)) {
                $route = '';
            }
            return (string)$route;
        }
    }
}