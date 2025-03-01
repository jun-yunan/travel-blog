<?php

namespace Post;

use Engine\Base;

/**
 * Default's controller, this shows the demo pages when you run for first time
 * this project
 */
class PostController extends Base
{


    public function home(): void
    {
        $data = [];
        $post_model = new PostModel();

        $data['posts'] = $post_model->getPosts(10, 0);

        $this->output->load("post/home",  $data);
    }

    public function schedule(): void
    {
        $data = ["title" => "Schedule"];
        $this->output->load("post/schedule",  $data);
    }

    public function saved(): void
    {
        $data = ["title" => "Saved"];
        $this->output->load("post/saved",  $data);
    }

    public function detail(): void
    {
        $data = [];
        if (!isset($_GET['slug'])) {
            $this->output->load("post/404");
            return;
        }
        $slug = $_GET['slug'] ?? '';
        $post_model = new PostModel();
        $post = $post_model->getPostBySlug($slug);

        if ($post['status'] === 'success') {
            $data = $post['data'];

            $this->output->load("post/detail",  $data);
        } else {
            $this->output->load("post/404");
        }
    }

    public function search(): void
    {
        header('Content-Type: application/json');

        $query = $_GET['q'] ?? '';
        $post_model = new PostModel();
        $result = $post_model->search($query, 10);

        echo json_encode($result['status'] === 'success' ? $result['data'] : $result);
        exit();
    }

    public function create(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (!isset($_SESSION['user'])) {
                $_SESSION['toast'] = [
                    'message' => 'Bạn cần đăng nhập để thực hiện chức năng này.',
                    'type' => 'error'
                ];
                header('Location: /login');
                exit();
            }

            $user_id = $_SESSION['user']['user_id'];
            $title = $_POST['title'];
            $content = $_POST['content'];
            $featured_image = $_POST['featured_image'];
            // $category = $_POST['category'];
            $location_id = $_POST['location_id'];
            $status = $_POST['status'] ?? "draft";
            $tags =  $_POST['tags'];

            $post_model = new PostModel();
            $result = $post_model->create($user_id, $title, $content, $location_id, $featured_image, $tags, $status);

            if ($result['status'] === 'success') {
                $_SESSION['toast'] = [
                    'message' => $result['message'],
                    'type' => 'success'
                ];
            } else {
                $_SESSION['toast'] = [
                    'message' => $result['message'],
                    'type' => 'error'
                ];
            }

            header('Location: /');
            exit();
        }
    }

    // public function create_comment(): void
    // {
    //     $data = array();

    //     session_start();

    //     if (!isset($_SESSION['user'])) {
    //         $_SESSION['toast'] = [
    //             'message' => 'Bạn cần đăng nhập để thực hiện chức năng này.',
    //             'type' => 'error'
    //         ];
    //         header('Location: /login');
    //         exit();
    //     }

    //     if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //         $user_id = $_SESSION['user']['user_id'];
    //         $post_id = $_POST['post_id'];
    //         $content = $_POST['content'];

    //         if (empty($content)) {
    //             $_SESSION['toast'] = [
    //                 'message' => 'Nội dung không được để trống.',
    //                 'type' => 'error'
    //             ];
    //             return;
    //         }

    //         $post_model = new PostModel();
    //         $result = $post_model->createComment($user_id, $post_id, $content);

    //         if ($result['status'] === 'success') {
    //             $_SESSION['toast'] = [
    //                 'message' => $result['message'],
    //                 'type' => 'success'
    //             ];
    //             header('Location: /');
    //             exit();
    //         } else {
    //             $_SESSION['toast'] = [
    //                 'message' => $result['message'],
    //                 'type' => 'error'
    //             ];
    //         }
    //     }
    // }

    public function create_comment(): void
    {
        header('Content-Type: application/json');

        session_start();
        if (!isset($_SESSION['user'])) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Bạn cần đăng nhập để bình luận.'
            ]);
            exit();
        }

        $data = json_decode(file_get_contents('php://input'), true);

        if (!isset($data['post_id']) || !isset($data['content'])) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Dữ liệu không hợp lệ. Vui lòng cung cấp post_id và nội dung bình luận.'
            ]);
            exit();
        }

        $post_id = (int)$data['post_id'];
        $user_id = $_SESSION['user']['user_id'];
        $content = trim($data['content']);

        if (empty($content)) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Nội dung bình luận không được để trống.'
            ]);
            exit();
        }

        $post_model = new PostModel();

        try {
            $result = $post_model->createComment($user_id, $post_id, $content);

            if ($result['status'] === 'success') {
                echo json_encode([
                    'status' => 'success',
                    'message' => $result['message'],
                    'comment_id' => $result['comment_id'] ?? null
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => $result['message']
                ]);
            }
        } catch (\Exception $e) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Lỗi khi tạo bình luận: ' . $e->getMessage()
            ]);
        }
        exit();
    }

    public function get_comments(): void
    {
        header('Content-Type: application/json');

        // Lấy post_id từ query string
        $post_id = (int)($_GET['post_id'] ?? 0);

        if ($post_id <= 0) {
            echo json_encode([
                'status' => 'error',
                'message' => 'ID bài viết không hợp lệ.'
            ]);
            exit();
        }

        // Khởi tạo PostModel
        $post_model = new PostModel();

        try {
            // Gọi hàm lấy bình luận từ PostModel
            $result = $post_model->getComments($post_id);

            if ($result['status'] === 'success') {
                echo json_encode([
                    'status' => 'success',
                    'data' => $result['data'],
                    'message' => 'Lấy danh sách bình luận thành công.'
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => $result['message']
                ]);
            }
        } catch (\Exception $e) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Lỗi khi lấy bình luận: ' . $e->getMessage()
            ]);
        }
        exit();
    }
}
