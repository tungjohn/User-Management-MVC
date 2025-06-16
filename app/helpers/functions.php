<?php
function toSlug($str) {
    // chuyển đổi chuỗi sang chữ thường
    $str = mb_strtolower($str, 'UTF-8');
    // loại bỏ các ký tự đặc biệt, chỉ giữ lại chữ cái, số và dấu gạch ngang
    $str = preg_replace('/[^a-z0-9\s-]/', '', $str);
    // thay thế các khoảng trắng và dấu gạch ngang liên tiếp bằng một dấu gạch ngang duy nhất
    $str = preg_replace('/[\s-]+/', ' ', $str);
    $str = trim($str);
    $str = str_replace(' ', '-', $str);
    return $str;
}

function redirect($uri) {
    if (empty($uri)) {
        return false;
    }
    if (preg_match('#^(https|http)?://#i', $uri) === 1) {
        header('Location: ' . $uri);
        exit();
    }
    $url = _WEB_ROOT . '/' . ltrim($uri, '/');
    header('Location: ' . $url);
    exit();
}

function route($name, $params = []) {
    global $routes;
    if (isset($routes[$name])) {
        $url = $routes[$name];
        if (!empty($params)) {
            foreach ($params as $key => $value) {
                $url = str_replace('{' . $key . '}', $value, $url);
            }
        }
        return _WEB_ROOT . '/' . ltrim($url, '/');
    }
    return false;
}