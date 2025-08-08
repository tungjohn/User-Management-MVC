<?php
$config['app'] = [
    'service' => [
        HtmlHelper::class
    ],
    'routeMiddleware' => [
        'auth/active-account' => ActiveAccountMiddleware::class
    ],
    'globalMiddleware' => [
        AuthMiddleware::class
    ],
    'boot' => [
        AppServiceProvider::class
    ],
    'page_limit' => 10
];
?>