<?php
$routes['default_controller'] = 'HomeController'; // Controller mặc định
/**
 * Đường dẫn ảo => Đường dẫn thật
 *
 */
$routes['trang-chu'] = 'HomeController';
// $routes['tin-tuc/.+-(\d+).html'] = 'news/category/$1';
$routes['users'] = 'UserController';
$routes['auth'] = 'AuthController';
$routes['auth/do-login'] = 'AuthController/loginSubmit';
$routes['auth/do-register'] = 'AuthController/registerSubmit';
$routes['auth/active-account'] = 'AuthController/activeAccount';
$routes['auth/resend-email-active'] = 'AuthController/resendEmailActive';
$routes['auth/forget-password'] = 'AuthController/forgetPassword';
$routes['auth/send-token-reset-password'] = 'AuthController/sendTokenResetPassword';
$routes['auth/reset-password'] = 'AuthController/resetPassword';
$routes['auth/submit-reset-password'] = 'AuthController/submitResetPassword';

?>