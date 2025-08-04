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

    public function registerSubmit() {
        // xử lý đăng ký
        $request = new Request();
        if ($request->isPost()) {
            $dataFields = $request->getFields();
            // Validate dữ liệu
            $request->rules([
                'name' => ['required', 'min:5', 'max:30'],
                'email' => ['required', 'email', 'min:8', 'unique:users,email'],
                'password' => ['required', 'min:8'],
                'confirm_password' => ['required', 'min:8', 'match:password'],
                
            ]);
            $request->message([
                'name.required' => 'Tên không được để trống',
                'name.min' => 'Tên phải phải có ít nhất 5 ký tự',
                'name.max' => 'Tên phải nhỏ hơn 30 ký tự',
                'email.required' => 'Email không được để trống',
                'email.email' => 'Email không đúng định dạng',
                'email.min' => 'Email phải phải có ít nhất 8 ký tự',
                'email.unique' => 'Email đã tồn tại',
                'password.required' => 'Mật khẩu không được để trống',
                'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự',
                'confirm_password.required' => 'Mật khẩu xác nhận không được để trống',
                'confirm_password.min' => 'Mật khẩu xác nhận phải phải có ít nhất 8 ký tự',
                'confirm_password.match' => 'Mật khẩu xác nhận không trùng khớp',
            ]);
            // Validate dữ liệu
            $validate = $request->validate();

            if (!$validate) {
                // Thông báo lỗi
                Session::flash('alertModal', $modal_detail = [
                    'action' => 'Thêm người dùng',
                    'status' => 'error',
                    'icon' => 'error',
                    'message' => 'Có lỗi xảy ra trong quá trình thêm người dùng'
                ]);
                return redirect('/auth/register');
            }

            // Lưu người dùng mới
            $password = Hash::make($dataFields['password']);

            $userData = [
                'name' => $dataFields['name'],
                'email' => $dataFields['email'],
                'password' => $password,
                'group_id' => 3, // member
                'status' => 2, // chưa kích hoạt
                'created_at' => date('Y-m-d H:i:s'),
            ];
            $userid = $this->userModel->insert($userData);

            if ($userid) {
                // Thông báo thành công
                Session::flash('alertModal', $modal_detail = [
                    'action' => 'Thêm người dùng',
                    'status' => 'success',
                    'icon' => 'success',
                    'message' => 'Thêm người dùng thành công!'
                ]);
                // lưu session active
                Session::flash('userActive', $userActive = [
                    'id' => $userid,
                    'keyActive' => md5($userid . '_' . $dataFields['email']),
                ]);

                // gửi mail kích hoạt tk

                // redirect sang trang active
                return redirect('/auth/active');
            } else {
                // Thông báo lỗi
                Session::flash('alertModal', $modal_detail = [
                    'action' => 'Thêm người dùng',
                    'status' => 'error',
                    'icon' => 'error',
                    'message' => 'Có lỗi xảy ra trong quá trình thêm người dùng'
                ]);
                return redirect('/auth/register');
            }
        }
        return redirect('/auth/register');
    }

    public function active() {
        $this->data['params']['page_title'] = 'Kích hoạt tài khoản';
        $this->data['content'] = 'login/active';
        $this->data['page_title'] = 'Kích hoạt tài khoản';
        $this->render('layouts/auth', $this->data);
    }
}