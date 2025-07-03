<?php
$routes['default_controller'] = 'HomeController'; // Controller mặc định
/**
 * Đường dẫn ảo => Đường dẫn thật
 *
 */
$routes['trang-chu'] = 'HomeController';
// $routes['tin-tuc/.+-(\d+).html'] = 'news/category/$1';
$routes['users'] = 'UserController';
$routes['users/edit/(\d+)'] = 'UserController/edit/$1';
$routes['users/update/(\d+)'] = 'UserController/update/$1';
$routes['users/delete/(\d+)'] = 'UserController/delete/$1';
$routes['auth/login'] = 'AuthController/login';
$routes['auth/register'] = 'AuthController/register';

?>