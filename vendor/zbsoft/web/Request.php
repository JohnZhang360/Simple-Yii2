<?php
/**
 * @link https://github.com/JohnZhang360/zgjian-framework
 */

namespace zbsoft\web;

use Zb;
use zbsoft\exception\NotFoundHttpException;

class Request
{
    private $_queryParams;

    /**
     * 返回GET参数
     * @return mixed
     */
    public function getQueryParams()
    {
        if ($this->_queryParams === null) {
            return $_GET;
        }

        return $this->_queryParams;
    }

    /**
     * 获取某个GET参数
     * @param $name
     * @param null $defaultValue
     * @return null
     */
    public function getQueryParam($name, $defaultValue = null)
    {
        $params = $this->getQueryParams();

        return isset($params[$name]) ? $params[$name] : $defaultValue;
    }

    /**
     * 解析请求地址
     * @return array
     * @throws NotFoundHttpException
     */
    public function resolve()
    {
        $route = Zb::$app->getUrlManager()->parseRequest($this);
        if ($route !== false) {
            return $route;
        } else {
            throw new NotFoundHttpException('Page not found.');
        }
    }
}