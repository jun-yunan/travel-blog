<?php

namespace User;

use Engine\Base;
use Service\Database;



class UserModel extends Base
{

    public $database;
    public function __construct()
    {
        $this->database = new Database('localhost', 'root', 'travel_blog', '', $this->console);
        $this->database->initialize();
    }

    public function login($email, $password)
    {
        if (empty($email) || empty($password)) {
            return [
                'status' => 'error',
                'message' => 'Vui lòng điền email và mật khẩu.'
            ];
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return [
                'status' => 'error',
                'message' => 'Email không hợp lệ.'
            ];
        }

        $sql = "SELECT * FROM users WHERE email = ?";
        $result = $this->database->query($sql, [$email]);

        if (empty($result)) {
            return [
                'status' => 'error',
                'message' => 'Email không tồn tại.'
            ];
        }

        $user = $result;

        if (password_verify($password, $user['password'])) {
            return [
                'status' => 'success',
                'message' => 'Đăng nhập thành công!',
                'user' => [
                    'user_id' => $user['user_id'],
                    'full_name' => $user['full_name'],
                    'email' => $user['email'],
                    "role" => $user['role']
                ]
            ];
        } else {
            return [
                'status' => 'error',
                'message' => 'Mật khẩu không đúng.'
            ];
        }
    }


    public function register($fullname, $email, $password)
    {
        if (empty($fullname) || empty($email) || empty($password)) {
            return [
                'status' => 'error',
                'message' => 'Vui lòng điền đầy đủ thông tin.'
            ];
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return [
                'status' => 'error',
                'message' => 'Email không hợp lệ.'
            ];
        }

        $checkEmail = $this->database->query("SELECT COUNT(*) as count FROM users WHERE email = ?", [$email]);

        if (intval($checkEmail['count']) > 0) {
            return [
                'status' => 'error',
                'message' => 'Email đã được sử dụng.'
            ];
        }

        $username = $this->generateUsername($fullname);

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        try {
            $result = $this->database->query(
                "INSERT INTO users (username, email, password, full_name) VALUES (?, ?, ?, ?)",
                [$username, $email, $hashed_password, $fullname]
            );

            return [
                'status' => 'success',
                'message' => 'Đăng ký thành công!',
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Đăng ký thất bại: ' . $e->getMessage()
            ];
        }
    }

    private function generateUsername($full_name)
    {
        $full_name = $this->removeAccents($full_name);

        $username = strtolower(str_replace(' ', '_', $full_name));

        $username = preg_replace('/[^a-z0-9_]/', '', $username);

        $check = $this->database->query("SELECT * FROM users WHERE username = ?", [$username]);

        if ($check && !empty($check)) {
            $username .= rand(100, 999);
        }

        return $username;
    }

    private function removeAccents($str)
    {
        $str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $str);
        $str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $str);
        $str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $str);
        $str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $str);
        $str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $str);
        $str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $str);
        $str = preg_replace("/(đ)/", 'd', $str);
        $str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $str);
        $str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $str);
        $str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $str);
        $str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $str);
        $str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $str);
        $str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $str);
        $str = preg_replace("/(Đ)/", 'D', $str);
        return $str;
    }
}
