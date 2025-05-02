<?php
$routes['default_controller'] = 'HomeController'; // Controller mặc định
/**
 * Đường dẫn ảo => Đường dẫn thật
 *
 */
$routes['trang-chu'] = 'home';
// $routes['tin-tuc/.+-(\d+).html'] = 'news/category/$1';
$routes['users'] = 'UserController';
$routes['auth/login'] = 'AuthController/login';
$routes['auth/register'] = 'AuthController/register';

?>