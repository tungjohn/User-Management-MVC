<?php
$config['app'] = [
    'service' => [
        HtmlHelper::class
    ],
    'routeMiddleware' => [
        
    ],
    'globalMiddleware' => [
        
    ],
    'boot' => [
        AppServiceProvider::class
    ],
    'page_limit' => 10
];
?>