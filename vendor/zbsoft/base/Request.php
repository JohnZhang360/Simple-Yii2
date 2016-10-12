<?php
/**
 * @link https://github.com/JohnZhang360/Simple-Yii2.git
 */

namespace zbsoft\base;

use Zb;
use zbsoft\exception\NotFoundHttpException;
use zbsoft\exception\InvalidConfigException;

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
        $result = Zb::$app->getUrlManager()->parseRequest($this);
        if ($result !== false) {
            list ($route, $params) = $result;
            $_GET = $params + $_GET; // preserve numeric keys

            return [$route, $_GET];
        } else {
            throw new NotFoundHttpException('Page not found.');
        }
    }

    private $_url;

    /**
     * 获取原始地址（被路由之前的）
     * @return bool|string
     * @throws InvalidConfigException
     */
    public function getUrl()
    {
        if ($this->_url === null) {
            $this->_url = $this->resolveRequestUri();
        }
        return $this->_url;
    }

    /**
     * $_SERVER['REQUEST_URI'](这里并不是唯一途径，框架还会考虑PATH_INFO以及IIS下有特殊处理，但是我们nginx和apache只要有#
     * request_uri就可以了)，只需要知道一点：虽然nginx内部重定向了，但是REQUEST_URI参数是没有改变的，它代表的是原始的url，也就是
     * 浏览器地址栏中的url。
     * @return mixed|string
     * @throws InvalidConfigException
     */
    protected function resolveRequestUri()
    {
        if (isset($_SERVER['HTTP_X_REWRITE_URL'])) { // IIS
            $requestUri = $_SERVER['HTTP_X_REWRITE_URL'];
        } elseif (isset($_SERVER['REQUEST_URI'])) {
            $requestUri = $_SERVER['REQUEST_URI'];
            if ($requestUri !== '' && $requestUri[0] !== '/') {
                $requestUri = preg_replace('/^(http|https):\/\/[^\/]+/i', '', $requestUri);
            }
        } elseif (isset($_SERVER['ORIG_PATH_INFO'])) { // IIS 5.0 CGI
            $requestUri = $_SERVER['ORIG_PATH_INFO'];
            if (!empty($_SERVER['QUERY_STRING'])) {
                $requestUri .= '?' . $_SERVER['QUERY_STRING'];
            }
        } else {
            throw new InvalidConfigException('Unable to determine the request URI.');
        }

        return $requestUri;
    }

    private $_pathInfo;

    /**
     * 获取路径信息
     * @return mixed
     */
    public function getPathInfo()
    {
        if ($this->_pathInfo === null) {
            $this->_pathInfo = $this->resolvePathInfo();
        }

        return $this->_pathInfo;
    }

    /**
     * 获取当前路径信息，HOST与GET参数之间的路径信息，作为路由配置
     * @return string
     */
    protected function resolvePathInfo()
    {
        $pathInfo = $this->getUrl();

        if (($pos = strpos($pathInfo, '?')) !== false) {
            $pathInfo = substr($pathInfo, 0, $pos);
        }

        $pathInfo = urldecode($pathInfo);

        if ($pathInfo[0] === '/') {
            $pathInfo = substr($pathInfo, 1);
        }

        return (string) $pathInfo;
    }
}