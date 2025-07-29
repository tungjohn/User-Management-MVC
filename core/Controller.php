<?php
class Controller {

    public $db;

    public function model($model) {
        if (file_exists(_DIR_ROOT . '/app/models/' . $model . '.php')) {
            require_once _DIR_ROOT . '/app/models/' . $model . '.php';
            if (class_exists($model)) {
                $model = new $model();
                return $model;
            } 
        } 
        return false;
    }

    public function render($view, $data = []) {

        // lấy dữ liệu dùng chung
        if (!empty(View::$dataShare)) {
            $data = array_merge($data, View::$dataShare);
        }

        if (!empty($data)) {
            extract($data);
        }

        // ob_start();
        // if (file_exists(_DIR_ROOT . '/app/views/' . $view . '.php')) {
        //     require_once _DIR_ROOT . '/app/views/' . $view . '.php';
        // } 
        // $contentView = ob_get_contents();
        // ob_end_clean();
        $contentView = null;
        // layouts chung không sử dụng template
        if (preg_match('/^layouts/', $view)) {
            if (file_exists(_DIR_ROOT . '/app/views/' . $view . '.php')) {
                require_once _DIR_ROOT . '/app/views/' . $view . '.php';
            }
        } else {
            // layouts sử dụng template
            if (file_exists(_DIR_ROOT . '/app/views/' . $view . '.php')) {
                $contentView = file_get_contents(_DIR_ROOT . '/app/views/' . $view . '.php');
            } 

            $template = new Template();
            $template->run($contentView, $data);
        }
    }

    public function flashMessage($action, $status, $icon, $message) {
        Session::flash('action', $action);
        Session::flash('status', $status);
        Session::flash('icon', $icon);
        Session::flash('message', $message);
    }
}