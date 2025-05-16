<?php
class UserController extends Controller {

    private $data = [];
    private $userModel;
                   
    public function __construct() {
        $this->userModel = $this->model('UserModel');
    }
                    
    public function index() {
        $users = $this->userModel->getAllUser();
        
        $this->data['params']['users'] = $users;

        $this->data['params']['page_title'] = 'Quản lý người dùng';
        $this->data['content'] = 'users/index';
        $this->data['page_title'] = 'Quản lý người dùng';
        // Render ra view
        $this->render('layouts/client_layouts', $this->data);         
    }
}