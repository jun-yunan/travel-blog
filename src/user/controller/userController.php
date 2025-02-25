<?php

namespace User;

use Engine\Base;

/**
 * Default's controller, this shows the demo pages when you run for first time
 * this project
 */
class UserController extends Base
{

    public function login(): void
    {
        $user_model = new UserModel();
        $data = [];


        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            $result =  $user_model->login($email, $password);

            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }

            if ($result['status'] === 'success') {
                $_SESSION['logged_in'] = true;
                $_SESSION['user'] = $result['user'];
                $_SESSION['toast'] = [
                    'type' => 'success',
                    'message' => 'Đăng nhập thành công!'
                ];
                header('Location: /');
                exit;
            } else {
                $_SESSION['toast'] = [
                    'type' => 'error',
                    'message' => $result['message']
                ];
                header('Location: /');
                exit;
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $this->output->loadNotHeaderFooter("user/login");
        }

        // $this->output->load("movie/home", $data);
    }

    public function register(): void
    {
        $user_model = new UserModel();
        $data = [];

        // Kiểm tra nếu form được submit
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fullname = $_POST['fullname'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            // Gọi hàm register từ UserModel
            $result = $user_model->register($fullname, $email, $password);

            // Bắt đầu session nếu chưa có
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }

            if ($result['status'] === 'success') {
                $_SESSION['toast'] = [
                    'type' => 'success',
                    'message' => 'Đăng ký thành công!. Vui lòng đăng nhập.'
                ];
                header('Location: /login');
                exit;
            } else {
                $data['message'] = $result['message'];
                $data['success'] = false;
                $_SESSION['toast'] = [
                    'type' => 'error',
                    'message' => 'Đăng ký thất bại. Email đã tồn tại!'
                ];
                header('Location: /register');
                exit;
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $this->output->loadNotHeaderFooter("user/register");
        }
        // $this->output->load("movie/home", $data);
    }


    public function logout(): void
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Xóa trạng thái đăng nhập
        unset($_SESSION['logged_in']);
        unset($_SESSION['user']);
        $_SESSION['toast'] = [
            'type' => 'success',
            'message' => 'Đăng xuất thành công!'
        ];

        header('Location: /login');
        exit;
    }
}
