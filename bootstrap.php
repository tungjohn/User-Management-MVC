<?php
define('_DIR_ROOT', __DIR__);

// xử lý http root
if (!empty($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on')) {
    $http = 'https://';
} else {
    $http = 'http://';
}
// WEB_ROOT
$folder_root = str_replace('\\', '/', _DIR_ROOT);
$folder_root = str_replace($_SERVER['DOCUMENT_ROOT'], '', $folder_root);
$web_root = $http . $_SERVER['HTTP_HOST'] . $folder_root;
define('_WEB_ROOT', $web_root);

// PUBLIC_ROOT
$public_root = $web_root . '/public';
define('_PUBLIC_ROOT', $public_root);

// _ASSET_DIR
$asset_dir = $public_root . '/assets';
define('_ASSET_DIR', $asset_dir);

// tự động load config
$configs_dir = scandir('configs');
if (!empty($configs_dir)) {
    foreach ($configs_dir as $item) {
        if ($item != '.' && $item != '..' && file_exists('configs/' . $item)) {
            require_once 'configs/' . $item;
        }
    }
}

// load all service (các helper)
if (!empty($config['app']['service'])) {
    $allSerives = $config['app']['service'];
    if (!empty($allSerives)) {
        foreach ($allSerives as $serviceName) {
            if (file_exists('app/core/' . $serviceName . '.php')) {
                require_once 'app/core/' . $serviceName . '.php';
            }
        }
    }
}

// Load Hash Class
require_once 'core/Hash.php'; // Load Hash Class

// Load ServiceProvider Class
require_once 'core/ServiceProvider.php'; // Load ServiceProvider Class

// Load View class
require_once 'core/View.php'; // Load View Class

require_once 'core/Load.php'; // Load Load

// Middlewares
require_once 'core/Middlewares.php'; // Load Middlewares


//require_once 'configs/routes.php'; // Load routes config
require_once 'core/Route.php'; // Load Route
require_once 'core/Session.php'; // Load Session

// kiểm tra config và load Database
if (!empty($config['database'])) {
    $db_config = $config['database'];

    if (!empty($db_config)) {
        require_once  'core/Connection.php';
        require_once  'core/QueryBuilder.php';
        require_once  'core/Database.php';
        require_once  'core/DB.php';

    }
}

require_once 'core/Helper.php'; // Load core Helper
// load all helper
$helpers_dir = scandir('app/helpers');
if (!empty($helpers_dir)) {
    foreach ($helpers_dir as $item) {
    
        if ($item != '.' && $item != '..' && file_exists('app/helpers/' . $item)) {
            require_once 'app/helpers/' . $item;
        }
    }
}

require_once 'app/App.php'; // load App
require_once 'core/Model.php'; // Load base Model
require_once 'core/Template.php'; // Load Template
require_once 'core/Controller.php'; // Load BaseController
require_once 'core/Request.php'; // Load Request
require_once 'core/Response.php'; // Load Response