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
        
        // check xem user đã đăng nhập chưa
        if (Session::data('userLogin')) {
            // đưa userInfo vào view để hiển thị
            $userLoginId = Session::data('userLogin')['id'];
            $userModel = Load::model('UserModel');
            $userInfo = $userModel->getUserById($userLoginId);
            if ($userInfo && $userInfo['status'] == 1 && $userInfo['session_id'] == Session::id()) {
                // share thông tin user qua các view
                View::share(['userInfo' => $userInfo]);

                // Nếu đã đăng nhập, không cần redirect
                if (in_array($path, $excludedPaths)) {
                    // Nếu đang ở trang đăng nhập hoặc đăng ký, redirect về trang chủ
                    redirect('/');
                }
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