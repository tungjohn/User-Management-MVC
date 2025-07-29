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
        ];
        
        if (Session::data('user_login')) {
            // Nếu đã đăng nhập, không cần redirect
            if (in_array($path, $excludedPaths)) {
                // Nếu đang ở trang đăng nhập hoặc đăng ký, redirect về trang chủ
                redirect('/');
            }
            return;
        }

        // Nếu chưa đăng nhập và đường dẫn không phải là các đường dẫn được loại trừ thì redirect đến trang đăng nhập
        if (!in_array($path, $excludedPaths)) {
            redirect('auth/login');
        }
    }
}