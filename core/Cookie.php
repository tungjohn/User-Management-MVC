<?php
class Cookie {

    /**
     * data($name, $value, $expires, $path) => set cookie
     * data($name) => get cookie
     */

    public static function data($name='', $value='', $expires=0, $path='/', $domain='', $secure=false, $httponly=true) {
        
        $cookieKey = self::isValidCookie();

        if (empty($name)) {
            if (isset($_COOKIE[$cookieKey])) {
                return $_COOKIE[$cookieKey]; // get all cookie
            } 
        } else {
            if (!empty($value)) {
                setcookie(
                    $cookieKey . "[" . $name . "]",         // Tên cookie
                    $value,                                 // Giá trị cookie
                    $expires,                               // Thời gian hết hạn (UNIX timestamp)
                                                            /**
                                                             * time() + 3600 → tồn tại 1 giờ.
                                                             * 0 hoặc không truyền → cookie là session cookie (mất khi đóng trình duyệt).
                                                             * time() - 3600 → xóa cookie.
                                                             */
                    $path,                                  // Đường dẫn (path) mà cookie hợp lệ
                                                            /**
                                                             * "/" → cookie dùng cho toàn bộ website.
                                                             * "/admin" → cookie chỉ gửi khi request tới /admin/*.
                                                             */
                    $domain,                                // Domain mà cookie hợp lệ (nếu để trống thì cookie chỉ hợp lệ trên domain hiện tại)
                                                            /**
                                                             * "example.com" → chỉ example.com.
                                                             * ".example.com" → cả example.com, www.example.com, sub.example.com.
                                                             */
                    $secure,                                // true: Chỉ gửi cookie qua HTTPS, false: gửi qua cả HTTP và HTTPS
                    $httponly                               // true: Chỉ cho phép truy cập cookie từ HTTP, JS không đọc được
                );
                return true;
            } else {
                if (isset($_COOKIE[$cookieKey][$name])) {
                    return $_COOKIE[$cookieKey][$name]; // get cookie
                } 
            }
        }
        return false;
    }

    /**
     * delete($name) => delete cookie
     * delete() => delete all cookie
     */
    public static function delete($name='', $value='', $path='/', $domain='', $secure=false, $httponly=true) {
        $cookieKey = self::isValidCookie();
        
        if (!empty($name)) {
            if (isset($_COOKIE[$cookieKey][$name])) {
                setcookie( // xóa 1 cookie
                    $cookieKey . "[" . $name . "]",        // Tên cookie
                    $value,  // Giá trị cookie
                    time() - 3600,    // Thời gian hết hạn (UNIX timestamp)
                    /**
                     * time() + 3600 → tồn tại 1 giờ.
                     * 0 hoặc không truyền → cookie là session cookie (mất khi đóng trình duyệt).
                     * time() - 3600 → xóa cookie.
                     */
                    $path,   // Đường dẫn (path) mà cookie hợp lệ
                    /**
                     * "/" → cookie dùng cho toàn bộ website.
                     * "/admin" → cookie chỉ gửi khi request tới /admin/*.
                     */
                    $domain, // Domain mà cookie hợp lệ (nếu để trống thì cookie chỉ hợp lệ trên domain hiện tại)
                    /**
                     * "example.com" → chỉ example.com.
                     * ".example.com" → cả example.com, www.example.com, sub.example.com.
                     */
                    $secure,// true: Chỉ gửi cookie qua HTTPS, false: gửi qua cả HTTP và HTTPS
                    $httponly // true: Chỉ cho phép truy cập cookie từ HTTP, JS không đọc được
                );
                return true;
            }
        } else {
            setcookie( // xóa tất cả cookie
                $cookieKey,        // Tên cookie
                $value,  // Giá trị cookie
                time() - 3600,    // Thời gian hết hạn (UNIX timestamp)
                /**
                 * time() + 3600 → tồn tại 1 giờ.
                 * 0 hoặc không truyền → cookie là session cookie (mất khi đóng trình duyệt).
                 * time() - 3600 → xóa cookie.
                 */
                $path,   // Đường dẫn (path) mà cookie hợp lệ
                /**
                 * "/" → cookie dùng cho toàn bộ website.
                 * "/admin" → cookie chỉ gửi khi request tới /admin/*.
                 */
                $domain, // Domain mà cookie hợp lệ (nếu để trống thì cookie chỉ hợp lệ trên domain hiện tại)
                /**
                 * "example.com" → chỉ example.com.
                 * ".example.com" → cả example.com, www.example.com, sub.example.com.
                 */
                $secure,// true: Chỉ gửi cookie qua HTTPS, false: gửi qua cả HTTP và HTTPS
                $httponly // true: Chỉ cho phép truy cập cookie từ HTTP, JS không đọc được
            );
            return true;
        }
        return false;
    }

    public static function showErrors($message) {
        $data = ['message' => $message];
        App::$app->loadError('exception', $data);
        die();
    }

    public static function isValidCookie() {
        global $config;

        if (!empty($config['cookie'])) {
            $cookieConfig = $config['cookie'];
            if (!empty($cookieConfig['cookie_key'])) {
                $cookieKey = $cookieConfig['cookie_key'];
                return $cookieKey;
            } else {
                self::showErrors('Cookie key is empty');
            }
        } else {
            self::showErrors('Cookie key is empty');
        }
    }
}