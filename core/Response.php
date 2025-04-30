<?php
class Response {

    public function __construct() {
        
    }

    // redirect sau khi nhận response
    public function redirect($uri = '') {
        if (preg_match('#^(https|http)?://#i', $uri) === 1) {
            header('Location: ' . $uri);
            exit();
        }
        $url = _WEB_ROOT . '/' . $uri;
        header('Location: ' . $url);
        exit();
    }
}