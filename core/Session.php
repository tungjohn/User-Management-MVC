<?php
class Session {

    /**
     * data($key, $value) => set session
     * data($key) => get session
     */

    public static function data($key='', $value='') {
        
        $sessionKey = self::isValidSession();

        if (empty($key)) {
            if (isset($_SESSION[$sessionKey])) {
                return $_SESSION[$sessionKey]; // get all session
            } 
        } else {
            if (!empty($value)) {
                $_SESSION[$sessionKey][$key] = $value; // set session
                return true;
            } else {
                if (isset($_SESSION[$sessionKey][$key])) {
                    return $_SESSION[$sessionKey][$key]; // get session
                } 
            }
        }
        return false;
    }

    /**
     * destroy($key) => destroy session
     * destroy() => destroy all session
     */
    public static function destroy($key = null) {
        $sessionKey = self::isValidSession();
        
        if (!empty($key)) {
            if (isset($_SESSION[$sessionKey][$key])) {
                unset($_SESSION[$sessionKey][$key]); // destroy session
                return true;
            }
        } else {
            unset($_SESSION[$sessionKey]); // destroy all session
            return true;
        }
        return false;
    }

    /**
     * Flash Data
     * - set flash data => giống set session
     * - get flash data => giống get session, xóa luôn session sau khi get
     */
    public static function flash($key = '', $value = '') {
        $dataFlash = self::data($key, $value); // set/get flash data
        if (empty($value)) {
            self::destroy($key); // destroy flash data
        }
        return $dataFlash;
    }

    public static function showErrors($message) {
        $data = ['message' => $message];
        App::$app->loadError('exception', $data);
        die();
    }

    public static function isValidSession() {
        global $config;

        if (!empty($config['session'])) {
            $sessionConfig = $config['session'];
            if (!empty($sessionConfig['session_key'])) {
                $sessionKey = $sessionConfig['session_key'];
                return $sessionKey;
            } else {
                self::showErrors('Session key is empty');
            }
        } else {
            self::showErrors('Session key is empty');
        }
    }

    public static function id() {
        return session_id();
    }
}