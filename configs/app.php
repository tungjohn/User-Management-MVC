<?php
$config['app'] = [
    'service' => [
        HtmlHelper::class
    ],
    'routeMiddleware' => [
        'auth/active-account' => ActiveAccountMiddleware::class,
        'auth/reset-password' => ResetPasswordMiddleware::class
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