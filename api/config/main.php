<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);
return [
    'id' => 'app-api',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'api\controllers',
    'bootstrap' => ['log'],
    'language' => 'pt-BR',
    'charset' => 'UTF-8',
    'timeZone' => 'America/Recife',
    'modules' => [
        'v1' => [
            'basePath' => '@api/modules/v1',
            'class' => 'api\modules\v1\Module'
        ]
    ],
    'components' => [
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'defaultTimeZone' => 'America/Recife',
            'timeZone' => 'America/Recife',
        ],

        'request' => [
            'csrfParam' => '_csrf-api',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
        ],
        'user' => [
            'identityClass' => 'api\modules\v1\models\User',
            'enableAutoLogin' => false,
            'enableSession' => false,
            'loginUrl' => null
        ],
        'session' => [
            // this is the name of the session cookie used for login on the api
            'name' => 'advanced-api',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/user',
                    'extraPatterns' => [
                        'OPTIONS <whatever:.*>' => 'options',
                        // ***************************************************
                        // ** User endpoints
                        // ***************************************************
                        'POST login' => 'login',
                        'POST recover-password' => 'recover-password',
                        'POST reset-password' => 'reset-password',
                        'POST register-user' => 'register-user',
                        'POST check-reset-password-token' => 'check-reset-password-token',
                    ]
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/chatbot',
                    'pluralize' => false,
                    'extraPatterns' => [
                        'OPTIONS <whatever:.*>' => 'options',
                        // ***************************************************
                        // ** Chatbot endpoints
                        // ***************************************************
                        'GET generate-welcome-message' => 'generate-welcome-message',
                        'POST receive-message' => 'receive-message',
                    ]
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/transaction',
                    'pluralize' => false,
                    'extraPatterns' => [
                        'OPTIONS <whatever:.*>' => 'options',
                        // ***************************************************
                        // ** Transaction endpoints
                        // ***************************************************
                        'POST convert-currency' => 'convert-currency',
                        'POST deposit-money' => 'deposit-money',
                        'POST withdraw-money' => 'withdraw-money',
                        'POST show-wallet-balance' => 'show-wallet-balance',
                    ]
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/wallet',
                    'pluralize' => false,
                    'extraPatterns' => [
                        'OPTIONS <whatever:.*>' => 'options',
                        // ***************************************************
                        // ** Wallet endpoints
                        // ***************************************************
                        'POST register-wallet' => 'register-wallet',
                        'POST set-default-wallet' => 'set-default-wallet',
                        'POST check-wallet' => 'check-wallet',
                        'POST check-default-wallet' => 'check-default-wallet',
                    ]
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => [
                        'v1/category',
                        'v1/ingress-storage',
                        'v1/product',
                        'v1/location',
                        'v1/evaluation',
                        'v1/fiscal-governance'
                    ],
                    'extraPatterns' => [
                        'OPTIONS <whatever:.*>' => 'options',
                    ]
                ],
            ],
        ],
    ],
    'params' => $params,
];
