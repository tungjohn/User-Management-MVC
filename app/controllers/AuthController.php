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

        if (Session::data('alertModal')) {
            $this->data['alertModal'] = Session::flash('alertModal');
        }
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
            // tạo token lưu db
            $activeToken = md5(uniqid());

            $userData = [
                'name' => $dataFields['name'],
                'email' => $dataFields['email'],
                'password' => $password,
                'group_id' => 3, // member
                'status' => 2, // chưa kích hoạt
                'active_token' => $activeToken,
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
                ]);

                // tạo link kích hoạt
                $linkActive = _WEB_ROOT . '/auth/active/?token=' . $activeToken;

                // gửi mail kích hoạt tk
                $userName = $dataFields['name'];
                $subject = "User mangagement - Kích hoạt tài khoản $userName";
                $message = "<p>Quý khách đã đăng ký tài khoản <b>$userName</b> tại website <a href='" . _WEB_ROOT . "'>" . _WEB_ROOT . "</a></p>";
                $message .= "<p>Để kích hoạt và sử dụng tài khoản, quý khách vui lòng ấn vào <a href='$linkActive'>link kích hoạt</a>.</p>";
                $message .= "<p>Vui lòng bỏ qua nếu quý khách không phải là chủ của tài khoản này!</p>";
                Mail::send($dataFields['email'], $subject, $message);

                // chuyển hướng đến trang active-account
                Session::flash('registerSuccess', $active_content = [
                    'content' => '✅ Đăng ký tài khoản thành công! Bạn cần truy cập email và nhấn vào link xác thực để kích hoạt tài khoản trước khi đăng nhập và sử dụng dịch vụ.',
                    'active_action_content' => 'Gửi lại email xác thực!',
                    'active_action_link' => 'javascript:void(0)',
                ]);
                return redirect('/auth/active-account');
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

    public function activeAccount() {
        $this->data['params']['page_title'] = 'Kích hoạt tài khoản';
        $this->data['content'] = 'login/active-account';
        $this->data['page_title'] = 'Kích hoạt tài khoản';

        if (Session::data('registerSuccess')) {
            $this->data['params']['activePage'] = Session::data('registerSuccess');
        } elseif (Session::data('activeSuccess')) {
            $this->data['params']['activePage'] = Session::flash('activeSuccess');
        }

        $this->render('layouts/auth', $this->data);
    }

    public function active() {
        // lấy token
        $request = new Request();
        $activeToken = $request->getFieldGet('token', 'string', '');
        $dbObject = new DB();
        $user_active = $dbObject->db->select('*')
            ->table('users')
            ->where('active_token', '=', $activeToken)
            ->where('active_token', '!=', '')
            ->where('status', '!=', 1)
            ->first();

        if ($user_active) {
            // xóa active_token
            $this->userModel->updateUser($user_active['id'], [
                'active_token' => '',
                'status' => 1
            ]);

            // xóa session đăng ký 
            if (Session::data('registerSuccess')) {
                Session::destroy('registerSuccess');
            }
            if (Session::data('userActive')) {
                Session::destroy('userActive');
            }

            // redirect sang trang active-account
            Session::flash('activeSuccess', $active_content = [
                'content' => '✅ Kích hoạt tài khoản thành công! Quý khách vui lòng đăng nhập để sử dụng dịch vụ!',
                'active_action_content' => 'Đăng nhập',
                'active_action_link' => '/auth/login',
            ]);
            return redirect('/auth/active-account');
        } else {
            return redirect('/auth/login');
        }
    }
}