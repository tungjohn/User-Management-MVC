<?php
class Load {

    public function __construct() {
        
    }

    public static function model($model) {
        if (file_exists(_DIR_ROOT . '/app/models/' . $model . '.php')) {
            require_once _DIR_ROOT . '/app/models/' . $model . '.php';
            if (class_exists($model)) {
                $model = new $model();
                return $model;
            } 
        } 
        return false;
    }

    public static function view($view, $data = []) {
        if (!empty($data)) {
            extract($data);
        }

        if (file_exists(_DIR_ROOT . '/app/views/' . $view . '.php')) {
            require_once _DIR_ROOT . '/app/views/' . $view . '.php';
            
        } 
    }

    public static function helper($helper) {
        if (file_exists(_DIR_ROOT . '/app/helpers/' . $helper . '.php')) {
            require_once _DIR_ROOT . '/app/helpers/' . $helper . '.php';
            
        } 
    }

    public static function service($service) {
        if (file_exists(_DIR_ROOT . '/app/core/' . $service . '.php')) {
            require_once _DIR_ROOT . '/app/core/' . $service . '.php';
            
        } 
    }
}