<?php
return [
    'resetPasswordBaseUrl' => 'http://painel.webfarma.net.br/password-reset/',

    'messages' => [
        'productName' => 'WebFarma'
    ],

    'sender' => [
        'email' => 'programacao@interativadigital.com.br'
    ],
    'wirecard' => [
        'access_token' => '', // My APP accessToken
        'access_key' => '',
        'base64' => '',
        'redirect_uri' => '',
        'post_redirect_uri_delivery' => 'http://portal.webfarma.sandbox.interativa.digital',
        'post_redirect_uri_company' => 'http://painel-webfarma.sandbox.interativa.digital/home',
        'client_id' => '', // My APP ID
        'client_secret' => '', // My APP secret
        'is_production' => true,
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
