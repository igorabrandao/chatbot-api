<?php
return [
    'resetPasswordBaseUrl' => 'http://painel-webfarma.sandbox.interativa.digital/password-reset/',

    'messages' => [
        'productName' => 'WebFarma'
    ],

    'sender' => [
        'email' => 'programacao@interativadigital.com.br'
    ],
    'wirecard' => [
        'access_token' => '1142f1346e4e46ea83a63d917c30713e_v2', // My APP accessToken
        'access_key' => '',
        'base64' => '',
        'redirect_uri' => 'http://api-webfarma.sandbox.interativa.digital/v1/wirecard/redirect',
        'post_redirect_uri_delivery' => 'http://portal.webfarma.sandbox.interativa.digital',
        'post_redirect_uri_company' => 'http://painel-webfarma.sandbox.interativa.digital/home',
        'client_id' => 'APP-JWI1URFE1ZDP', // My APP ID
        'client_secret' => '57a66e6f46404bcbbc516aca69b91eac', // My APP secret
        'is_production' => false,
    ],
    'contact' => [
        'from' => 'programacao@interativadigital.com.br',
        'main' => ['contato@webfarma.net.br', 'thiagolimaoliveira@hotmail.com', 'pedrocosta@adinvest.club'],
        'testers' => ['mannuel@interativadigital.com.br'],
    ],
    'google-maps' => [
        'api-key-directions' => 'AIzaSyDB9WtFI67Ra4ipVD2BJbsy3RLtUBfwwhc'
    ],
];
