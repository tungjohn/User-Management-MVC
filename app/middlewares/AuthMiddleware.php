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
        
        if (Session::data('userLogin')) {
            // đưa userInfo vào view để hiển thị
            $userLoginId = Session::data('userLogin')['id'];
            $userModel = Load::model('UserModel');
            $userInfo = $userModel->getUserById($userLoginId);
            if ($userInfo && $userInfo ['status'] == 1) {
                View::share(['userInfo' => $userInfo]);

                // Nếu đã đăng nhập, không cần redirect
                if (in_array($path, $excludedPaths)) {
                    // Nếu đang ở trang đăng nhập hoặc đăng ký, redirect về trang chủ
                    redirect('/');
                }
                return;
            }
            // nếu không tìm thấy thông tin hoặc user chưa kích hoạt -> redirect trang đăng nhập
            Session::destroy('userInfo');
            return redirect('auth/login');
        }

        // Nếu chưa đăng nhập và đường dẫn không phải là các đường dẫn được loại trừ thì redirect đến trang đăng nhập
        if (!in_array($path, $excludedPaths)) {
            return redirect('auth/login');
        }

        return;
    }
}