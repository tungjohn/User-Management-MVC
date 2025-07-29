<?php
$config['app'] = [
    'service' => [
        HtmlHelper::class
    ],
    'routeMiddleware' => [
        
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