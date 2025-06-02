<?php
function toSlug($str) {
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