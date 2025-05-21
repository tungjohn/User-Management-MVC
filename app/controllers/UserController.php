<?php
class UserController extends Controller {

    private $data = [];
    private $userModel;
                   
    public function __construct() {
        $this->userModel = $this->model('UserModel');
    }
                    
    public function index() {
        // lấy các request params
        $request = new Request();
        $dataFields = $request->getFields();

        $keyword = $dataFields['keyword'] ?? '';
        $groups = $this->userModel->getGroups();
        $status = $this->userModel->getStatus();

        // thêm điều kiện lọc
        $condition = [];
        if (!empty($dataFields['group'])) {
            $condition['group_id'] = $dataFields['group'];
        }
        if (!empty($dataFields['status'])) {
            $condition['status'] = $dataFields['status'];
        }
        if (!empty($dataFields['keyword'])) {
            $condition['keyword'] = $dataFields['keyword'];
        }
        // Lấy danh sách người dùng
        $users = $this->userModel->getListUser($condition);
        
        $this->data['params']['users'] = $users;

        $this->data['params']['page_title'] = 'Quản lý người dùng';
        $this->data['params']['dataFields'] = $dataFields;
        $this->data['params']['groups'] = $groups;
        $this->data['params']['status'] = $status;
        $this->data['content'] = 'users/index';
        $this->data['page_title'] = 'Quản lý người dùng';
        // Render ra view
        $this->render('layouts/client_layouts', $this->data);         
    }
}