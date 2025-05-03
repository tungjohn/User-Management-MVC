<?php
class Hash {

    public function __construct() {
        
    }

    public static function make($string, $type = 'default') {
        global $config;
        $type = !empty($config['hash']['type']) ? $config['hash']['type'] : 'default';
        switch ($type) {
            case 'default':
                return password_hash($string, PASSWORD_DEFAULT);
            case 'bcrypt':
                return password_hash($string, PASSWORD_BCRYPT);
            case 'argon2i':
                return password_hash($string, PASSWORD_ARGON2I);
            case 'argon2id':
                return password_hash($string, PASSWORD_ARGON2ID);
            default:
                return md5($string);
        }
    }

    public static function check($string, $hash) {
        return password_verify($string, $hash);
    }
}