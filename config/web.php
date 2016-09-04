<?php

$config = [
    'db' => require(__DIR__ . '/db.php'),
    'urlManager' => [
        'enablePrettyUrl' => false,
    ],
];

return $config;