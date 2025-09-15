<?php
class AuthMiddleware extends Middlewares {

    public function handle() {
        // Middleware handle() xử lý trước khi vào controller
        $request = new Request();
        $path = $request->getPath();
        
        $excludedPaths = [
            // Thêm các đường dẫn không cần xác thực tại đây
            'auth/login',
            'auth/register',
            'auth/do-login',
            'auth/do-register',
            'auth/active-account',
            'auth/active',
            '(auth\/active)\/*\?*.+',
            'auth/resend-email-active',
            'auth/forget-password',
            'auth/send-token-reset-password',
            'auth/reset-password',
            'auth/submit-reset-password',
        ];
        
        // duy trì đăng nhập bằng cookie
        // check xem có cookie đăng nhập không
        if (Cookie::data('remember_token')) {
            $remember_token = Cookie::data('remember_token');
            $userModel = Load::model('UserModel');
            $userInfo = $userModel->getUser('remember_token', '=', md5($remember_token));
            if ($userInfo) {
                if ($userInfo['remember_token_expired'] > time()) {
                    if ($userInfo['status'] == 1) {
                        // Lưu thông tin người dùng vào session
                        if (empty(Session::data('userLogin'))) {
                            $userLogin = [
                                'id' => $userInfo['id'],
                            ];
                            Session::data('userLogin', $userLogin);
                        }

                        // lưu session id khi user đăng nhập
                        if (empty($userInfo['session_id']) || $userInfo['session_id'] != Session::id()) {
                            $userModel->updateUser($userLogin['id'], [
                                'session_id' => Session::id(),
                                'update_at' => date('Y-m-d H:i:s')
                            ]);
                        }
                    }
                } else {
                    // xóa cookie hết hạn
                    $userModel->updateUser($userInfo['id'], [
                        'remember_token' => '',
                        'remember_token_expired	' => '0',
                        'update_at' => date('Y-m-d H:i:s')
                    ]);
                }
            }
        }

        if (Session::data('userLogin')) {
            // đưa userInfo vào view để hiển thị
            $userLoginId = Session::data('userLogin')['id'];
            $userModel = Load::model('UserModel');
            $userInfo = $userModel->getUserById($userLoginId);
            if ($userInfo && $userInfo['status'] == 1 && $userInfo['session_id'] == Session::id()) {
                // share thông tin user qua các view
                View::share(['userInfo' => $userInfo]);

                if (in_array($path, $excludedPaths)) {
                    // Nếu đang ở trang đăng nhập hoặc đăng ký, redirect về trang chủ
                    redirect('/');
                }
                // Nếu đã đăng nhập, không cần redirect
                return;
            }
            // nếu không tìm thấy thông tin hoặc user chưa kích hoạt -> redirect trang đăng nhập
            Session::destroy('userLogin');
            return redirect('auth/login');
        }

        // check xem user đã đăng ký chưa
        if ($path == 'auth/active') {
            if (!Session::data('userActive')) {
                redirect('auth/register');
            }
        }

        // Nếu chưa đăng nhập và đường dẫn không phải là các đường dẫn được loại trừ thì redirect đến trang đăng nhập
        if (!in_array($path, $excludedPaths)) {
            foreach ($excludedPaths as $valuePath) {
                if (preg_match('~' . $valuePath . '~is', $path)) {
                    return;
                }
            }
            return redirect('auth/login');
        }

        return;
    }
}