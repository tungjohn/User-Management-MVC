<?php
class AuthController extends Controller {

    public $data = [];
    public $model = [];
                   
    public function __construct() {
        
    }
                    
    public function login() {
        $this->data['params']['page_title'] = 'Đăng nhập hệ thống';
        $this->data['content'] = 'login/login';
        $this->data['page_title'] = 'Đăng nhập hệ thống';
        // Render ra view
        $this->render('layouts/auth', $this->data);                  
    }

    public function register() {
        $this->data['params']['page_title'] = 'Đăng ký thành viên';
        $this->data['content'] = 'login/register';
        $this->data['page_title'] = 'Đăng ký thành viên';
        // Render ra view
        $this->render('layouts/auth', $this->data);                  
    }
}