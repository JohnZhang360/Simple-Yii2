<?php

namespace zbsoft\base;

use Zb;
use zbsoft\exception\InvalidCallException;
use zbsoft\exception\InvalidConfigException;
use zbsoft\exception\InvalidParamException;

/**
 * MVC模式中的视图处理类
 *
 * 提供了不同形式的生成方法
 */
class View extends Object
{
    /**
     * @var Controller the context under which the [[renderFile()]] method is being invoked.
     */
    public $context;

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
        return $this->renderFile($viewFile, $params, $context);
    }

    /**
     * 获取视图文件路径，可根据$view生成不同规则的路径
     *
     * - path alias (e.g. "@app/views/site/index");
     * - absolute path within application (e.g. "//site/index"): the view name starts with double slashes.
     *   The actual view file will be looked for under the [[Application::viewPath|view path]] of the application.
     * - absolute path within current module (e.g. "/site/index"): the view name starts with a single slash.
     *   The actual view file will be looked for under the [[Module::viewPath|view path]] of the [[Controller::module|current module]].
     * - relative view (e.g. "index"): the view name does not start with `@` or `/`. The corresponding view file will be
     *   looked for under the [[ViewContextInterface::getViewPath()|view path]] of the view `$context`.
     *   If `$context` is not given, it will be looked for under the directory containing the view currently
     *   being rendered (i.e., this happens when rendering a view within another view).
     *
     * @param $view
     * @param Controller $context
     * @return string
     * @throws InvalidConfigException
     */
    protected function findViewFile($view, $context = null)
    {
        if (strncmp($view, '@', 1) === 0) {
            // e.g. "@app/views/main"
            $file = Zb::getAlias($view);
        } elseif (strncmp($view, '//', 2) === 0) {
            // e.g. "//layouts/main"
            $file = Zb::$app->getViewPath() . DIRECTORY_SEPARATOR . ltrim($view, '/');
        } elseif (strncmp($view, '/', 1) === 0) {
            // e.g. "/site/index"
            if (Zb::$app->controller !== null) {
                $file = Zb::$app->controller->module->getViewPath() . DIRECTORY_SEPARATOR . ltrim($view, '/');
            } else {
                throw new InvalidConfigException("Unable to locate view file for view '$view': no active controller.");
            }
        } elseif ($context != null) {
            $file = $context->getViewPath() . DIRECTORY_SEPARATOR . $view;
        } else {
            throw new InvalidCallException("Unable to resolve view file for view '$view': no active view context.");
        }

        return $file . '.php';
    }

    /**
     * 渲染视图，该方法可用于在不用的视图模板中做相应的处理
     *
     * @param string $viewFile the view file. This can be either an absolute file path or an alias of it.
     * @param array $params the parameters (name-value pairs) that will be extracted and made available in the view file.
     * @param object $context the context that the view should use for rendering the view. If null,
     * existing [[context]] will be used.
     * @return string the rendering result
     * @throws InvalidParamException if the view file does not exist
     */
    public function renderFile($viewFile, $params = [], $context)
    {
        if ($context !== null) {
            $this->context = $context;
        }
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