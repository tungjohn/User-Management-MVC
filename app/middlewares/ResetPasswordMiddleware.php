<?php
class ResetPasswordMiddleware extends Middlewares {

    public function handle() {
        // Middleware handle() xử lý trước khi vào controller
        $request = new Request();
        $reset_token = $request->getFieldGet('token', 'string', '');
        if (empty($reset_token)) {
            // nếu không có token thì redirect về trang login
            return redirect('auth/login');
        }

        $dbObject = new DB();
        // kiểm tra token có trong database không
        $user = $dbObject->db->select('*')->table('users')->where('reset_token', '=', $reset_token)->first();
        if (empty($user)) {
            // nếu không tồn tại token thì redirect về trang login
            return redirect('auth/login');
        }

        return;
    }
}