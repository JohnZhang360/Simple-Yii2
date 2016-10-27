<?php
/**
 * @link https://github.com/JohnZhang360/Simple-Yii2.git
 */
namespace app\controllers;

use Zb;
use zbsoft\exception\HttpException;
use zbsoft\exception\Exception;
use zbsoft\exception\UserException;

class ErrorController extends BaseController
{
    public function actionIndex()
    {
        if (($exception = Zb::$app->getErrorHandler()->exception) === null) {
            // action has been invoked not from error handler, but by direct route, so we display '404 Not Found'
            $exception = new HttpException(404, 'Page not found.');
        }

        if ($exception instanceof HttpException) {
            $code = $exception->statusCode;
        } else {
            $code = $exception->getCode();
        }
        if ($exception instanceof Exception) {
            $name = $exception->getName();
        } else {
            $name = 'Error';
        }
        if ($code) {
            $name .= " (#$code)";
        }

        if ($exception instanceof UserException) {
            $message = $exception->getMessage();
        } else {
            $message = 'An internal server error occurred.';
        }

        if (Zb::$app->getRequest()->getIsAjax()) {
            return "$name: $message";
        } else {
            return $this->render("index", [
                'name' => $name,
                'message' => $message,
                'exception' => $exception,
            ]);
        }
    }
}