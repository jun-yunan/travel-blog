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

    public function profile(): void
    {
        session_start();
        if (!isset($_SESSION['user'])) {
            $_SESSION['toast'] = [
                'message' => 'Bạn cần đăng nhập để xem trang profile.',
                'type' => 'error'
            ];
            header('Location: /login');
            exit();
        }

        $user = $_SESSION['user'];
        $user_model = new UserModel();

        // Lấy thông tin chi tiết của user (nếu cần thêm từ database)
        $user_id = $user['user_id'];
        $user_info =  $user_model->getUserById($user_id);

        // Lấy danh sách posts của user
        $posts =  $user_model->getUserPosts($user_id, 10, 0); // Giới hạn 10 posts, offset 0

        $data = [
            'title' => 'Trang cá nhân - ' . htmlspecialchars($user['full_name']),
            'user' => $user_info,
            'posts' => $posts
        ];

        $this->output->load('user/profile', $data);
    }


    public function update_profile(): void
    {
        header('Content-Type: application/json');

        session_start();
        if (!isset($_SESSION['user'])) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Bạn cần đăng nhập để cập nhật hồ sơ.'
            ]);
            exit();
        }

        $user_id = $_SESSION['user']['user_id'];
        $user_model = new UserModel();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $full_name = trim($_POST['full_name'] ?? '');
            $username = trim($_POST['username'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $profile_picture = $_POST['profile_picture'] ?? null; // Có thể là Base64 hoặc đường dẫn

            if (empty($full_name) || empty($username) || empty($email)) {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Vui lòng điền đầy đủ thông tin.'
                ]);
                exit();
            }

            $result =  $user_model->updateUserProfile($user_id, $full_name, $username, $email, $profile_picture);

            if ($result['status'] === 'success') {
                // $_SESSION['user']['full_name'] = $full_name;
                // $_SESSION['user']['username'] = $username;
                // $_SESSION['user']['email'] = $email;
                // if ($profile_picture && strpos($profile_picture, 'data:image') === 0) {
                //     $_SESSION['user']['profile_picture'] = $profile_picture;
                // }

                echo json_encode([
                    'status' => 'success',
                    'message' => $result['message']
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => $result['message']
                ]);
            }
            exit();
        }

        echo json_encode([
            'status' => 'error',
            'message' => 'Phương thức không hợp lệ.'
        ]);
        exit();
    }

    public function get_me(): void
    {
        header('Content-Type: application/json');

        session_start();
        if (!isset($_SESSION['user'])) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Bạn cần đăng nhập để xem thông tin.'
            ]);
            exit();
        }

        $user_model = new UserModel();
        $user_id = $_SESSION['user']['user_id'];
        $user =  $user_model->getUserById($user_id);



        echo json_encode([
            'status' => 'success',
            'user' => $user
        ]);
        exit();
    }
}
