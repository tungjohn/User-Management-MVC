<?php

/**
 * Kế thừa class Controller (require file, tạo /app/models/HomeModel)
 * HomeModel kế thừa từ class Model
 * Model Kế thừa class Database
 * Database connect qua class Connection và gọi đến các phương thức truy vấn CSDL
 */
class Home extends Controller {

    public $users;
    public $data = [];

    public function __construct() {
        $this->users = $this->model(self::class . 'Model');
    }

    public function index() {

        // Render ra view
        $this->data['params']['page_title'] = 'Home page';
        $this->data['content'] = 'home/index';
        $this->data['page_title'] = 'Home page';
        $this->render('layouts/client_layouts', $this->data);
        
    }
}