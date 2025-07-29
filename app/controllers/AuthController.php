<?php
class AuthController extends Controller {

    public $data = [];
    public $userModel;
                   
    public function __construct() {
        $this->userModel = $this->model('UserModel');
    }
                    
    public function login() {
        $this->data['params']['page_title'] = 'Đăng nhập hệ thống';


        $this->data['content'] = 'login/login';
        $this->data['page_title'] = 'Đăng nhập hệ thống';
        $this->data['action'] = Session::flash('action');
        $this->data['status'] = Session::flash('status');
        $this->data['icon'] = Session::flash('icon');
        $this->data['message'] = Session::flash('message');
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

    public function loginSubmit() {
        // Xử lý đăng nhập
        $request = new Request();
        if (!$request->isPost()) {
            $this->flashMessage('Thêm người dùng', 'success', 'success', 'Thêm người dùng thành công!');
            return redirect('auth/login');
        }
        if (!$request->getFieldPost('email')) {
            $this->flashMessage('Đăng nhập', 'error', 'error', 'Email không được để trống!');
            return redirect('auth/login');
        }
        if (!$request->getFieldPost('password')) {
            $this->flashMessage('Đăng nhập', 'error', 'error', 'Mật khẩu không được để trống!');
            return redirect('auth/login');
        }

        $email = $request->getFieldPost('email');
        $password = $request->getFieldPost('password');

        $user = $this->userModel->getUserByEmail($email);
        if (!$user) {
            $this->flashMessage('Đăng nhập', 'error', 'error', 'Email hoặc mật khẩu không chính xác!');
            return redirect('auth/login');
        }
        
        // Kiểm tra mật khẩu
        
        if (Hash::check($password, $user['password'])) {
            // Lưu thông tin người dùng vào session
            $session_login = [
                'id' => $user['id'],
                'email' => $user['email'],
                'role' => $user['role'],
            ];
            Session::data('user_login', $session_login);

            // Hiển thị thông báo thành công
            // $this->flashMessage('Đăng nhập', 'success', 'success', 'Đăng nhập thành công!');
            return redirect('/');
        } else {
            // Hiển thị thông báo lỗi
            $this->flashMessage('Đăng nhập', 'error', 'error', 'Email hoặc mật khẩu không chính xác!');
            return redirect('auth/login');
        }
    }
}