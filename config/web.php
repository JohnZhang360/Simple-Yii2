<?php

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'components' => [
        'cache' => [
            'class' => 'zbsoft\caching\FileCache',
        ],
        'urlManager' => [
            'enablePrettyUrl' => false,
        ],
        'db' => require(__DIR__ . '/db.php'),
    ],
    'modules' => [
        'admin' => [
            'class' => 'app\modules\admin\Module',
        ],
    ],
];

return $config;