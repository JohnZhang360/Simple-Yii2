<?php
/**
 * @desc 入口文件
 * @link https://github.com/JohnZhang360/Simple-Yii2.git
 */

ini_set("display_errors", "On");
error_reporting(E_ALL);

// Autoload 自动载入
require '../vendor/autoload.php';

$config = require(__DIR__ . '/../config/web.php');
require(__DIR__."/../vendor/zbsoft/Zb.php");

(new \zbsoft\base\Application($config))->run();