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
        $this->data['params']['keyword'] = $keyword;

        $this->data['params']['action'] = Session::flash('action');
        $this->data['params']['status'] = Session::flash('status');
        $this->data['params']['icon'] = Session::flash('icon');
        $this->data['params']['message'] = Session::flash('message');

        $this->data['content'] = 'users/index';
        $this->data['page_title'] = 'Quản lý người dùng';
        // Render ra view
        $this->render('layouts/client_layouts', $this->data);         
    }

    public function delete() {
        $request = new Request();
        $dataFields = $request->getFields();

        if (!empty($dataFields['ids'])) {
            // Kiểm tra nếu ids là một chuỗi thì chuyển đổi thành mảng
            if (is_string($dataFields['ids'])) {
                $ids = trim($dataFields['ids']);
                $ids = explode(',', $ids);
            } else {
                $ids = $dataFields['ids'];
            }

            // Chuyển đổi các giá trị trong mảng thành số nguyên
            $ids = array_map('intval', $ids);
            // Loại bỏ các giá trị trùng lặp và không hợp lệ
            $ids = array_filter(array_unique(($ids))); 
            // Kiểm tra nếu mảng ids rỗng
            if (empty($ids)) {
                $this->flashMessage('Xóa danh sách người dùng', 'status', 'error', 'Không có dữ liệu nào được chọn để xóa');
                redirect('/users');
            }

            // Xóa nhiều người dùng
            $this->userModel->deleteManyUsers($ids);
            // Thông báo thành công
            $this->flashMessage('Xóa danh sách người dùng', 'success', 'success', 'Xóa dữ liệu thành công!');
            redirect('/users');
        } else {
            // Thông báo lỗi nếu không có người dùng nào được chọn
            $this->flashMessage('Xóa danh sách người dùng','error', 'error', 'Không có dữ liệu nào được chọn để xóa!');
            redirect('/users');
        }

    }

    public function flashMessage($action, $status, $icon, $message) {
        Session::flash('action', $action);
        Session::flash('status', $status);
        Session::flash('icon', $icon);
        Session::flash('message', $message);
    }
}