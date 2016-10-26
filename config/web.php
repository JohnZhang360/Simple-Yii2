<?php

$params = array_merge(
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'], // ensure logger gets loaded before application starts
    'components' => [
        'cache' => [
            'class' => 'zbsoft\caching\FileCache',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true
        ],
        'db' => require(__DIR__ . '/db.php'),
        'request' => [
            // cookie验证使用,防止客户端篡改cookie值
            'cookieValidationKey' => '65edd1f6-4933-4f35-b793-4ae3daa96cdb',
        ],
        'errorHandler' => [
            'errorAction' => 'error/index',
        ],
        'log' => [
            'traceLevel' => ZB_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'zbsoft\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
    ],
    'modules' => [
        'admin' => [
            'class' => 'app\modules\admin\Module',
        ],
    ],
    'params' => $params,
];

return $config;