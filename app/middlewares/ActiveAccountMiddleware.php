<?php
class ActiveAccountMiddleware extends Middlewares {

    public function handle() {
        // Middleware handle() xử lý trước khi vào controller
        if (empty(Session::data('registerSuccess')) && empty(Session::data('userActive'))) {
            return redirect('/auth/login');
        }

        return;
    }
}