<?php
class AuthMiddleware extends Middlewares {

    public function __construct() {
        
    }

    public function handle() {
        // Middleware handle() xử lý trước khi vào controller
        $homeModel = Load::model('HomeModel');
        
    }
}