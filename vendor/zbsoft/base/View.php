<?php

namespace zbsoft\base;

use zbsoft\exception\InvalidParamException;

/**
 * MVC模式中的视图处理类
 *
 * 提供了不同形式的生成方法
 */
class View extends Object
{
    /**
     * 渲染一个视图
     * 查找视图路径，然后执行视图代码
     *
     * @param $view
     * @param array $params
     * @param null $context
     * @return string
     */
    public function render($view, $params = [], $context = null)
    {
        $viewFile = $this->findViewFile($view, $context);
        return $this->renderFile($viewFile, $params);
    }

    /**
     * 获取视图文件路径，可根据$view生成不同规则的路径
     *
     * @param $view
     * @param \zbsoft\base\Controller $context
     * @return string
     */
    protected function findViewFile($view, $context = null)
    {
        return $context->getViewPath() . DIRECTORY_SEPARATOR . $view . '.php';
    }

    /**
     * 渲染视图，该方法可用于在不用的视图模板中做相应的处理
     *
     * @param string $viewFile the view file. This can be either an absolute file path or an alias of it.
     * @param array $params the parameters (name-value pairs) that will be extracted and made available in the view file.
     * @return string the rendering result
     * @throws InvalidParamException if the view file does not exist
     */
    public function renderFile($viewFile, $params = [])
    {
        return $this->renderPhpFile($viewFile, $params);
    }

    /**
     * 渲染PHP视图
     *
     * 该方法会传递render中的参数到视图中。
     * 通过require方法执行视图里面的代码并放入到缓存中
     *
     * @param string $_file_ the view file.
     * @param array $_params_ the parameters (name-value pairs) that will be extracted and made available in the view file.
     * @return string the rendering result
     */
    public function renderPhpFile($_file_, $_params_ = [])
    {
        ob_start();
        ob_implicit_flush(false);
        extract($_params_, EXTR_OVERWRITE);
        require($_file_);

        return ob_get_clean();
    }
}