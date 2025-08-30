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

            // lưu session id khi user đăng nhập
            $this->userModel->updateUser($user['id'], [
                'session_id' => Session::id(),
                'update_at' => date('Y-m-d H:i:s')
            ]);

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
            $user = $this->userModel->getUserById(Session::data('userLogin')['id']);

            if (!empty($user)) {
                // xóa session id khi user đăng xuất
                $this->userModel->updateUser($user['id'], [
                    'session_id' => '',
                    'update_at' => date('Y-m-d H:i:s')
                ]);
                Session::destroy('userLogin');
                
                return redirect('auth/login');
            }
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
                'active_token_expired' => time() + 60,
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
                $mail = Mail::send($dataFields['email'], $subject, $message);
                // chuyển hướng đến trang active-account
                Session::flash('activePage', $active_content = [
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

        if (Session::data('activePage')) {
            $this->data['params']['activePage'] = Session::data('activePage');
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
            // kiểm tra token hết hạn
            if ($user_active['active_token_expired'] < time()) {
                // set lại userActive trước khi redirect
                // lưu session active
                Session::flash('userActive', $userActive = [
                    'id' => $user_active['id'],
                ]);
                // redirect sang trang active-account
                Session::flash('activePage', $active_content = [
                    'content' => '<i class="fa fa-times-circle text-danger" aria-hidden="true"></i> Mã kích hoạt hết hạn, vui lòng nhấn vào link dưới đây để gửi lại mã kích hoạt!',
                    'active_action_content' => 'Gửi lại email kích hoạt',
                    'active_action_link' => 'javascript:void(0)',
                    'active_token' => $activeToken
                ]);

                return redirect('/auth/active-account');
            }

            // xóa active_token sau khi active
            $this->userModel->updateUser($user_active['id'], [
                'active_token' => '',
                'active_token_expired' => 0,
                'status' => 1,
                'update_at' => date('Y-m-d H:i:s')
            ]);

            // xóa session đăng ký 
            if (Session::data('activePage')) {
                Session::destroy('activePage');
            }
            // if (Session::data('userActive')) {
            //     Session::destroy('userActive');
            // }

            // redirect sang trang active-account
            Session::flash('activePage', $active_content = [
                'content' => '✅ Kích hoạt tài khoản thành công! Quý khách vui lòng đăng nhập để sử dụng dịch vụ!',
                'active_action_content' => 'Đăng nhập',
                'active_action_link' => '/auth/login',
            ]);
            return redirect('/auth/active-account');
        } else {
            return redirect('/auth/login');
        }
    }

    public function resendEmailActive() {
        // lấy token
        $request = new Request();
        if (!$request->isPost()) {
            Session::flash('alertModal', $modal_detail = [
                'action' => 'Gửi lại email kích hoạt',
                'status' => 'error',
                'icon' => 'error',
                'message' => 'Invalid request method!'
            ]);
            return redirect('/auth/active-account');
        }

        $expired_token = $request->getFieldPost('active_token', 'string', '');

        // xử lý gửi lại email kích hoạt
        $user = $this->userModel->getUser('active_token', '=', $expired_token);
        if (!$user) {
            Session::flash('alertModal', $modal_detail = [
                'action' => 'Gửi lại email kích hoạt',
                'status' => 'error',
                'icon' => 'error',
                'message' => 'Không tìm thấy thông tin tài khoản!'
            ]);
            return redirect('/auth/active-account');
        }

        // tạo link kích hoạt
        $activeToken = uniqid();
        // tạo link kích hoạt
        $linkActive = _WEB_ROOT . '/auth/active/?token=' . $activeToken;

        // cập nhật active_token và active_token_expired
        $this->userModel->updateUser($user['id'], [
            'active_token' => $activeToken,
            'active_token_expired' => time() + 60,
        ]);

        // gửi mail kích hoạt tk
        $userName = $user['name'];
        $subject = "User mangagement - Gửi lại mã kích hoạt tài khoản $userName";
        $message = "<p>Quý khách đã đăng ký tài khoản <b>$userName</b> tại website <a href='" . _WEB_ROOT . "'>" . _WEB_ROOT . "</a></p>";
        $message .= "<p>Để kích hoạt và sử dụng tài khoản, quý khách vui lòng ấn vào <a href='$linkActive'>link kích hoạt</a>.</p>";
        $message .= "<p>Vui lòng bỏ qua nếu quý khách không phải là chủ của tài khoản này!</p>";
        $mail = Mail::send($user['email'], $subject, $message);
        // chuyển hướng đến trang active-account
        if (!$mail) {
            Session::flash('activePage', $active_content = [
                'content' => '<i class="fa fa-times-circle text-danger" aria-hidden="true"></i> Mã kích hoạt hết hạn, vui lòng nhấn vào link dưới đây để gửi lại mã kích hoạt!',
                'active_action_content' => 'Gửi lại email xác thực!',
                'active_action_link' => 'javascript:void(0)',
            ]);
            Session::flash('alertModal', $modal_detail = [
                'action' => 'Gửi lại email kích hoạt',
                'status' => 'error',
                'icon' => 'error',
                'message' => 'Có lỗi khi gửi lại email kích hoạt tài khoản! Quý khách vui lòng thử lại sau.',
            ]);
        } else {
            Session::flash('activePage', $active_content = [
                'content' => '✅ Gửi lại email kích hoạt tài khoản thành công! Bạn cần truy cập email và nhấn vào link xác thực để kích hoạt tài khoản trước khi đăng nhập và sử dụng dịch vụ.',
                'active_action_content' => 'Gửi lại email xác thực!',
                'active_action_link' => 'javascript:void(0)',
                'active_token' => $activeToken
            ]);
        }
        
        
        return redirect('/auth/active-account');
    }
    
    public function forgetPassword() {
        $this->data['params']['page_title'] = 'Quên mật khẩu';
        $this->data['content'] = 'login/forget-password';
        $this->data['page_title'] = 'Quên mật khẩu';

        if (Session::data('alertModal')) {
            $this->data['alertModal'] = Session::flash('alertModal');
        }
        if (Session::data('sendTokenStatus')) {
            $this->data['params']['sendTokenStatus'] = Session::flash('sendTokenStatus');
        }
        if (Session::data('sendSuccess')) {
            $this->data['params']['sendSuccess'] = Session::flash('sendSuccess');
        }
        // Render ra view
        $this->render('layouts/auth', $this->data);                  
    }

    public function sendTokenResetPassword() {
        // xử lý đăng ký
        $request = new Request();
        if ($request->isPost()) {
            $dataFields = $request->getFields();
            // Validate dữ liệu
            $request->rules([
                'email' => ['required', 'email', 'min:8'],
            ]);
            $request->message([
                'email.required' => 'Email không được để trống',
                'email.email' => 'Email không đúng định dạng',
                'email.min' => 'Email phải phải có ít nhất 8 ký tự',
            ]);
            // Validate dữ liệu
            $validate = $request->validate();

            if (!$validate) {
                // Thông báo lỗi
                Session::flash('alertModal', $modal_detail = [
                    'action' => 'Quên mật khẩu',
                    'status' => 'error',
                    'icon' => 'error',
                    'message' => 'Có lỗi xảy ra trong quá trình Gửi yêu cầu đặt lại mật khẩu'
                ]);
                return redirect('/auth/forget-password');
            }

            // xử lý gửi email token reset password
            $user = $this->userModel->getUser('email', '=', $dataFields['email']);
            if (!$user) {
                Session::flash('alertModal', $modal_detail = [
                    'action' => 'Quên mật khẩu',
                    'status' => 'error',
                    'icon' => 'error',
                    'message' => 'Không tìm thấy thông tin tài khoản!'
                ]);
                return redirect('/auth/forget-password');
            }

            // kiểm tra user đã kích hoạt chưa
            if ($user['status'] != 1) {
                Session::flash('alertModal', $modal_detail = [
                    'action' => 'Quên mật khẩu',
                    'status' => 'error',
                    'icon' => 'error',
                    'message' => 'User chưa kích hoạt! Bạn cần kích hoạt tài khoản trước khi đặt lại mật khẩu.'
                ]);
                return redirect('/auth/forget-password');
            }

            // kiểm tra user đã gửi token và token chưa hết hạn
            if ($user['reset_token'] != '' && $user['reset_token_expired'] > time()) {
                Session::flash('alertModal', $modal_detail = [
                    'action' => 'Quên mật khẩu',
                    'status' => 'error',
                    'icon' => 'error',
                    'message' => 'Bạn đã gửi yêu cầu đặt lại mật khẩu, vui lòng kiểm tra email hoặc thử lại sau 5 phút!'
                ]);
                return redirect('/auth/forget-password');
            }

            // tạo token đặt lại mật khẩu
            $resetToken = md5(uniqid());
            // tạo link đặt lại mật khẩu
            $linkReset = _WEB_ROOT . '/auth/reset-password/?token=' . $resetToken;

            $userData = [
                'reset_token' => $resetToken,
                'reset_token_expired' => time() + 5*60,
                'update_at' => date('Y-m-d H:i:s'),
            ];
            
            // cập nhật reset_token
            $this->userModel->updateUser($user['id'], $userData);

            // gửi mail token reset password
            $userName = $user['name'];
            $subject = "User mangagement - Yêu cầu đặt lại mật khẩu tài khoản $userName";
            $message = "<p>Quý khách đã yêu cầu đặt lại mật khẩu tài khoản <b>$userName</b> tại website <a href='" . _WEB_ROOT . "'>" . _WEB_ROOT . "</a></p>";
            $message .= "<p>Để tiến hành đặt lại mật khẩu, quý khách vui lòng ấn vào <a href='$linkReset'>đây</a>.</p>";
            $message .= "<p>Vui lòng bỏ qua nếu quý khách không phải là chủ của tài khoản này!</p>";
            $mail = Mail::send($user['email'], $subject, $message);
            // chuyển hướng đến trang forget-password
            if (!$mail) {
                Session::flash('alertModal', $modal_detail = [
                    'action' => 'Quên mật khẩu',
                    'status' => 'error',
                    'icon' => 'error',
                    'message' => 'Có lỗi khi gửi email yêu cầu đặt lại mật khẩu! Quý khách vui lòng thử lại sau.',
                ]);
            } else {
                Session::flash('alertModal', $modal_detail = [
                    'action' => 'Quên mật khẩu',
                    'status' => 'success',
                    'icon' => 'success',
                    'message' => 'Gửi lại yêu cầu cấp lại mật khẩu thành công! Bạn cần truy cập email và nhấn vào link để thực hiện đặt lại mật khẩu.',
                ]);
                return redirect('/auth/login');
            }
        }
        return redirect('/auth/forget-password');                  
    }

    public function resetPassword() {
        // hiển thị form đặt lại mật khẩu
        $request = new Request();
        $resetToken = $request->getFieldGet('token', 'string', '');
        $this->data['params']['page_title'] = 'Đặt lại mật khẩu';
        $this->data['content'] = 'login/reset-password';
        $this->data['page_title'] = 'Đặt lại mật khẩu';
        $this->data['params']['resetToken'] = $resetToken;

        if (Session::data('alertModal')) {
            $this->data['alertModal'] = Session::flash('alertModal');
        }
        if (Session::data('resetPassword')) {
            $this->data['params']['resetPassword'] = Session::flash('resetPassword');
        }
        
        // Render ra view
        $this->render('layouts/auth', $this->data);  
        
    }

    public function submitResetPassword() {
        // xử lý đặt lại mật khẩu
        $request = new Request();
        $dataFields = $request->getFields();

        if (!$request->isPost()) {
            Session::flash('alertModal', $modal_detail = [
                'action' => 'Đặt lại mật khẩu',
                'status' => 'error',
                'icon' => 'error',
                'message' => 'Invalid request method!'
            ]);
            return redirect('/auth/reset-password?token=' . ($dataFields['token'] ?? ''));
        }

        // Validate dữ liệu
        $request->rules([
            'password' => ['required', 'min:8'],
            'confirm_password' => ['required', 'min:8', 'match:password'],
            
        ]);
        $request->message([
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
                'action' => 'Đặt lại mật khẩu',
                'status' => 'error',
                'icon' => 'error',
                'message' => 'Có lỗi xảy ra trong quá trình đặt lại mật khẩu'
            ]);
            return redirect('/auth/reset-password?token=' . ($dataFields['token'] ?? ''));
        }

        // kiểm tra token
        if (empty($dataFields['token'])) {
            Session::flash('alertModal', $modal_detail = [
                'action' => 'Đặt lại mật khẩu',
                'status' => 'error',
                'icon' => 'error',
                'message' => 'Token không hợp lệ!'
            ]);
            return redirect('/auth/reset-password?token=' . ($dataFields['token'] ?? ''));
        }

        $user = $this->userModel->getUser('reset_token', '=', $dataFields['token']);
        if (!$user) {
            Session::flash('alertModal', $modal_detail = [
                'action' => 'Đặt lại mật khẩu',
                'status' => 'error',
                'icon' => 'error',
                'message' => 'Token không hợp lệ!'
            ]);
            return redirect('/auth/reset-password?token=' . ($dataFields['token'] ?? ''));
        }

        // kiểm tra token hết hạn
        if ($user['reset_token_expired'] < time()) {
            Session::flash('alertModal', $modal_detail = [
                'action' => 'Đặt lại mật khẩu',
                'status' => 'error',
                'icon' => 'error',
                'message' => 'Token đã hết hạn, vui lòng gửi lại yêu cầu đặt lại mật khẩu!'
            ]);
            return redirect('/auth/forget-password');
        }

        // cập nhật mật khẩu mới
        $newPassword = Hash::make($dataFields['password']);
        $this->userModel->updateUser($user['id'], [
            'password' => $newPassword,
            'reset_token' => '',
            'reset_token_expired' => 0,
            'update_at' => date('Y-m-d H:i:s')
        ]);

        // redirect sang trang login
        Session::flash('alertModal', $modal_detail = [
            'action' => 'Đặt lại mật khẩu',
            'status' => 'success',
            'icon' => 'success',
            'message' => 'Đặt lại mật khẩu thành công!'
        ]);
        return redirect('/auth/reset-password?token=' . ($dataFields['token'] ?? ''));
    }
}