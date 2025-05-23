<?php


/**
 * Kết nối database
 * Singleton Pattern: bảo đảm rằng mỗi một lớp (class) chỉ có được một khởi tạo (instance) duy nhất và mọi tương tác đều thông qua instance này.
 * Private constructor (phương thức khởi tạo) của class để đảm bảo rằng class lớp khác không thể truy cập vào constructor và tạo ra instance mới
 * Tạo một biến private static là khởi tạo của class đó để đảm bảo rằng nó là duy nhất và chỉ được tạo ra trong class đó thôi.
 * Tạo một public static method trả về instance vừa khởi tạo bên trên
 */
class Connection
{
    private static $instance = null;

    // khai báo các thuộc tính kết nối csdl trong class Database bằng các hằng số trong file config.php
    
    private function __construct($config)
    {
        // Kết nối database
        try {
            if (class_exists('PDO')) {
                $dns = 'mysql:host=' . $config['host'] . ';dbname=' . $config['db'];
                $options = [
                    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // đẩy lỗi ngoại lệ khi truy vấn
                    // PDO::ATTR_EMULATE_PREPARES => false,
                ];

                if (self::$instance == null) {
                    self::$instance = new PDO($dns, $config['user'], $config['password'], $options);
                }
            }
            // var_dump(self::$conn);
        } catch (Exception $exception) {
            $mess = $exception->getMessage();
            App::$app->loadError('database', ['message' => $mess]);
            die();
        }
    }

    public static function getInstance($config)
    {
        if (self::$instance == null) {
            $conn = new Connection($config);
            // self::$instance = new Connection($config);
        }

        return self::$instance;
    }
}