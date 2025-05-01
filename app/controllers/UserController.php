<?php
class UserController extends Controller {

    private $data = [];
    private $model = [];
                   
    public function __construct() {
        
    }
                    
    public function index() {
        $this->data['params']['page_title'] = 'Quản lý người dùng';
        $this->data['content'] = 'users/index';
        $this->data['page_title'] = 'Quản lý người dùng';
        // Render ra view
        $this->render('layouts/client_layouts', $this->data);         
    }
}