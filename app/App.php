<?php
class App {

    private $__controller;
    private $__action;
    private $__params;
    private $__routes;
    private $__db;

    public static $app;

    public function __construct() {
        global $routes, $config;

        self::$app = $this;

        $this->__routes = new Route();

        $this->__controller = 'HomeController';
        if (!empty($routes['default_controller'])) {
            $this->__controller = $routes['default_controller'];
        }
        
        $this->__action = 'index';
        $this->__params = [];

        // khởi tạo global query builder
        if (class_exists('DB')) {
            $dbObject = new DB();
            $this->__db = $dbObject->db;
        }
        

        $this->handleUrl();
    }

    public function getUrl() {
        if (!empty($_GET['url'])) {
            $url = $_GET['url'];
        } else {
            $url = '/';
        }
        return $url;
    }

    /**
     * Xử lý url, gọi controller, action, params tương ứng
     */

    public function handleUrl() {
        // lấy url
        $url = $this->getUrl();

        // Xử lý route (rewrite url)
        $url = $this->__routes->handleRoute($url);

        // Middleware App
        // require các routeMiddleware trong configs/app.php
        $this->handleRouteMiddleware($this->__routes->getKeyRoute(), $this->__db);
        $this->handleGlobalMiddleware($this->__db);

        // require các service provider trong configs/app.php (AppServiceProvider)
        $this->handleAppServiceProvider($this->__db);

        $urlArr = array_filter(explode('/', $url));
        $urlArr = array_values($urlArr);

        // xử lý admin url
        $fileCheck = $urlCheck = '';
        foreach ($urlArr as $key => $value) {
            $urlCheck .= $value . '/' ;
            $fileCheck = rtrim($urlCheck, '/');
            $fileArr = explode('/', $fileCheck);
            $fileArr[count($fileArr) - 1] = ucfirst($fileArr[count($fileArr) - 1]);
            $fileCheck = implode('/', $fileArr);

            unset($urlArr[$key - 1]);
            if (file_exists('app/controllers/' . $fileCheck . '.php')) {
                $urlCheck = $fileCheck;
                break;
            }
        }
        
        $urlArr = array_values($urlArr);
        // xử lý khi urlCheck rỗng
        if (empty($urlCheck)) {
            $urlCheck = $this->__controller;
        }

        // xử lý Controller
        $this->__controller = ucfirst($this->__controller);
        if (!empty($urlArr[0])) {
            $this->__controller = ucfirst($urlArr[0]);
        }
        // kiểm tra file controller có tồn tại không
        if (file_exists('app/controllers/' . $urlCheck . '.php')) {
            require_once 'controllers/' . $urlCheck . '.php';
            // kiểm tra class controller có tồn tại không
            if (class_exists($this->__controller)) {
                $this->__controller = new $this->__controller();
            } else {
                return $this->loadError();
            }
            
            // 
            if (!empty($this->__db)) {
                $this->__controller->db = $this->__db;
            }
            
            // $this->__controller->index();
            unset($urlArr[0]);
        } else {
            return $this->loadError();
        }

        // Xử lý Action
        if (!empty($urlArr[1])) {
            $this->__action = $urlArr[1];
            unset($urlArr[1]);
        }
        if (!method_exists($this->__controller, $this->__action)) {
            return $this->loadError();
        } 
        
        // Xử lý Params
        $this->__params = array_values($urlArr);

        // unset url
        unset($_GET['url']);
        
        // Gọi phương thức của controller với các tham số
        // 
        call_user_func_array([$this->__controller, $this->__action], $this->__params);
    }

    public function loadError($name = '404', $data = []) {
        extract($data);
        require_once 'errors/' . $name . '.php';
        return false;
    }

    public function handleRouteMiddleware($routeKey, $db) {
        global $config;
        if (!empty($config['app']['routeMiddleware'])) {
            $routeMiddlewareArr = $config['app']['routeMiddleware'];
            foreach ($routeMiddlewareArr as $key => $routeMiddleware) {
                if ($routeKey == trim($key) && file_exists('app/middlewares/' . $routeMiddleware . '.php')) {
                    require_once 'app/middlewares/' . $routeMiddleware . '.php';
                    if (class_exists($routeMiddleware)) {
                        $middlewareObject = new $routeMiddleware();
                        if (!empty($db)) {
                            $middlewareObject->db = $db;
                        }
                        if (method_exists($middlewareObject, 'handle')) {
                            $middlewareObject->handle();
                        }
                    }
                }
            }
        }
    }

    public function handleGlobalMiddleware($db) {
        global $config;
        if (!empty($config['app']['globalMiddleware'])) {
            $globalMiddlewareArr = $config['app']['globalMiddleware'];
            foreach ($globalMiddlewareArr as $key => $globalMiddleware) {
                if (file_exists('app/middlewares/' . $globalMiddleware . '.php')) {
                    require_once 'app/middlewares/' . $globalMiddleware . '.php';
                    if (class_exists($globalMiddleware)) {
                        $middlewareObject = new $globalMiddleware();
                        if (!empty($db)) {
                            $middlewareObject->db = $db;
                        }
                        if (method_exists($middlewareObject, 'handle')) {
                            $middlewareObject->handle();
                        }
                    }
                }
            }
        }
    }

    public function handleAppServiceProvider($db) {
        global $config;
        if (!empty($config['app']['boot'])) {
            $serviceProviderArr = $config['app']['boot'];
            foreach ($serviceProviderArr as $serviceName) {
                if (file_exists('app/core/' . $serviceName . '.php')) {
                    require_once 'app/core/' . $serviceName . '.php';
                    if (class_exists($serviceName)) {
                        $serviceObject = new $serviceName();
                        if (!empty($db)) {
                            $serviceObject->db = $db;
                        }
                        if (method_exists($serviceObject, 'boot')) {
                            $serviceObject->boot();
                        }
                    }
                }
            }
        }
    }
}