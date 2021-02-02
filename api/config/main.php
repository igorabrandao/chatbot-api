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
                        'POST login' => 'login',
                        'POST login-by-code' => 'login-by-code',
                        'POST check-ingress-code' => 'check-ingress-code',
                        'POST recover-password' => 'recover-password',
                        'POST reset-password' => 'reset-password',
                        'POST register-client' => 'register-client',
                        'POST check-reset-password-token' => 'check-reset-password-token',
                        'POST register-delivery-request' => 'register-delivery-request',
                        'GET generate-wirecard-customer-id' => 'generate-wirecard-customer-id',
                        'GET get-webfarma-delivery-staff' => 'get-webfarma-delivery-staff',
                        'GET get-webfarma-delivery' => 'get-webfarma-delivery',
                        'POST register-my-location' => 'register-my-location',
                        'POST register-my-city' => 'register-my-city',
                    ]
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/merchandise',
                    'extraPatterns' => [
                        'OPTIONS <whatever:.*>' => 'options',
                        'POST search-string' => 'search-string',
                    ]
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/company',
                    'extraPatterns' => [
                        'OPTIONS <whatever:.*>' => 'options',
                        'POST get-by-category' => 'get-by-category',
                        'POST open-store' => 'open-store',
                        'POST close-store' => 'close-store',
                        'POST get-store-profile' => 'get-store-profile',
                    ]
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/upload',
                    'tokens' => ['{id}' => '<id:\w+>'],
                    'except' => ['update']
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/purchase',
                    'extraPatterns' => [
                        'OPTIONS <whatever:.*>' => 'options',
                        'POST pay-by-credit-card' => 'pay-by-credit-card',
                        'POST reject-purchase' => 'reject-purchase',
                        'GET company-review-purchases' => 'company-review-purchases',
                        'GET company-purchases' => 'company-purchases',
                        'GET calculate-delivery-price' => 'calculate-delivery-price',
                    ]
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/wirecard',
                    'pluralize' => false,
                    'extraPatterns' => [
                        'OPTIONS <whatever:.*>' => 'options',
                        'GET get-permission-request-uri' => 'get-permission-request-uri',
                        'GET get-delivery-permission-uri' => 'get-delivery-permission-uri',
                        'GET redirect' => 'redirect',
                        'POST receive-notification' => 'receive-notification',
                    ]
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/distribution',
                    'extraPatterns' => [
                        'OPTIONS <whatever:.*>' => 'options',
                        'GET get-my-active-deliveries' => 'get-my-active-deliveries',
                        'GET get-my-pre-assigned-deliveries' => 'get-my-pre-assigned-deliveries',
                        'GET get-my-deliveries' => 'get-my-deliveries',
                        'POST send-proximity-alert' => 'send-proximity-alert',
                        'POST refuse-delivery' => 'refuse-delivery',
                    ]
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/purchase',
                    'extraPatterns' => [
                        'OPTIONS <whatever:.*>' => 'options',
                        'GET get-my-purchases' => 'get-my-purchases',
                    ]
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/payment',
                    'extraPatterns' => [
                        'OPTIONS <whatever:.*>' => 'options',
                        'POST perform-payment' => 'perform-payment',
                    ]
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/device',
                    'extraPatterns' => [
                        'OPTIONS <whatever:.*>' => 'options',
                        'POST save-new-device' => 'save-new-device',
                    ]
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/push-notification',
                    'pluralize' => false,
                    'extraPatterns' => [
                        'OPTIONS <whatever:.*>' => 'options',
                        'POST send-push-notification' => 'send-push-notification',
                    ]
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/contact',
                    'pluralize' => false,
                    'extraPatterns' => [
                        'OPTIONS <whatever:.*>' => 'options',
                        'POST send-email' => 'send-email',
                    ]
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => 'v1/import',
                    'extraPatterns' => [
                        'OPTIONS <whatever:.*>' => 'options',
                        'POST import-company-products' => 'import-company-products',
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
                        'v1/fiscal-governance'],
                    'extraPatterns' => [
                        'OPTIONS <whatever:.*>' => 'options',
                    ]
                ],
            ],
        ],
    ],
    'params' => $params,
];
