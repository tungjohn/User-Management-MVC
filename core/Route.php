<?php

class Route
{
    private $__keyRoute = null;
    // private $__uri = '';
    public function handleRoute($url)
    {
        global $routes;
        unset($routes['default_controller']);

        $url = trim($url, '/');
        if (empty($url)) {
            $url = '/';
        }

        $handleUrl = $url;
        if (!empty($routes)) {
            foreach ($routes as $key => $route) {
                if (preg_match('~' . $key .'~is', $url)) {
                    $handleUrl = preg_replace('~' . $key .'~is', $route, $url);
                    $this->__keyRoute = $key;
                }
            }
        }
        return $handleUrl;
    }

    public function getKeyRoute()
    {
        return $this->__keyRoute;
    }

    public static function getFullUrl() {
        $uri = App::$app->getUrl();
        $url = _WEB_ROOT . '/' . $uri;
        return $url;
    }
}