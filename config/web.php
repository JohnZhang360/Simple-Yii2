<?php

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'components' => [
        'urlManager' => [
            'enablePrettyUrl' => false,
        ],
        'db' => require(__DIR__ . '/db.php'),
    ]
];

return $config;