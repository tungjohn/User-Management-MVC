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

        if (Session::data('alertModal')) {
            $this->data['alertModal'] = Session::flash('alertModal');
        }
        
        // Render ra view
        $this->render('layouts/auth', $this->data);                  
    }

    public function loginSubmit() {
        // Xử lý đăng nhập
        $request = new Request();
        if (!$request->isPost()) {
            Session::flash('alertModal', $modal_detail = [
                'action' => 'Đăng nhập',
                'status' => 'error',
                'icon' => 'error',
                'message' => 'Invalid request method!'
            ]);
            return redirect('auth/login');
        }
        if (!$request->getFieldPost('email')) {
            Session::flash('alertModal', $modal_detail = [
                'action' => 'Đăng nhập',
                'status' => 'error',
                'icon' => 'error',
                'message' => 'Email không được để trống!'
            ]);
            return redirect('auth/login');
        }
        if (!$request->getFieldPost('password')) {
            Session::flash('alertModal', $modal_detail = [
                'action' => 'Đăng nhập',
                'status' => 'error',
                'icon' => 'error',
                'message' => 'Mật khẩu không được để trống!'
            ]);
            return redirect('auth/login');
        }

        $email = $request->getFieldPost('email');
        $password = $request->getFieldPost('password');

        $user = $this->userModel->getUserByEmail($email);
        if (!$user) {
            Session::flash('alertModal', $modal_detail = [
                'action' => 'Đăng nhập',
                'status' => 'error',
                'icon' => 'error',
                'message' => 'Email hoặc mật khẩu không chính xác!'
            ]);
            return redirect('auth/login');
        }
        
        // Kiểm tra mật khẩu
        if (Hash::check($password, $user['password'])) {
            // kiểm tra user đã kích hoạt chưa
            if ($user['status'] != 1) {
                Session::flash('alertModal', $modal_detail = [
                    'action' => 'Đăng nhập',
                    'status' => 'error',
                    'icon' => 'error',
                    'message' => 'User chưa kích hoạt!'
                ]);
                return redirect('auth/login');
            }

            // Lưu thông tin người dùng vào session
            $userLogin = [
                'id' => $user['id'],
            ];
            Session::data('userLogin', $userLogin);

            // Hiển thị thông báo thành công
            // $this->flashMessage('Đăng nhập', 'success', 'success', 'Đăng nhập thành công!');
            return redirect('/');
        } else {
            // Hiển thị thông báo lỗi
            Session::flash('alertModal', $modal_detail = [
                'action' => 'Đăng nhập',
                'status' => 'error',
                'icon' => 'error',
                'message' => 'Email hoặc mật khẩu không chính xác!'
            ]);
            return redirect('auth/login');
        }
    }

    public function logOut() {
        if (Session::data('userLogin')) {
            Session::destroy('userLogin');
            return redirect('auth/login');
        }
        return redirect('/');
    }

    public function register() {
        $this->data['params']['page_title'] = 'Đăng ký thành viên';
        $this->data['content'] = 'login/register';
        $this->data['page_title'] = 'Đăng ký thành viên';
        // Render ra view
        $this->render('layouts/auth', $this->data);                  
    }
}