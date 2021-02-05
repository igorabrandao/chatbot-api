<?php
// config/test.php
$config = yii\helpers\ArrayHelper::merge(
    require(__DIR__ . '/main.php'),
    require(__DIR__ . '/main-local.php'),
    [
        'id' => 'app-api-tests',
        'components' => [
            'assetManager' => [
                'basePath' => __DIR__ . '/../web/assets',
            ],
            'db' => [
                'class' => 'yii\db\Connection',
                'dsn' => 'mysql:host=localhost;dbname=detail-data',
                'username' => 'root',
                'password' => 'root',
                'charset' => 'utf8',
            ]
        ]
    ]
);
return $config;