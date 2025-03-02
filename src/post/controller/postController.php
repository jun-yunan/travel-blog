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

    // public function schedule(): void
    // {
    //     $data = ["title" => "Schedule"];
    //     $this->output->load("post/schedule",  $data);
    // }

    public function saved(): void
    {
        session_start();
        if (!isset($_SESSION['user'])) {
            $_SESSION['toast'] = [
                'message' => 'Bạn cần đăng nhập để xem bài viết đã lưu.',
                'type' => 'error'
            ];
            header('Location: /login');
            exit();
        }

        $user = $_SESSION['user'];
        $post_model = new PostModel();

        // Lấy danh sách bài viết đã lưu của user
        $user_id = $user['user_id'];
        $bookmarks = $post_model->getUserBookmarks($user_id, 10, 0); // Giới hạn 10 bookmarks, offset 0

        $data = [
            'title' => 'Bài viết đã lưu - ' . htmlspecialchars($user['full_name']),
            'bookmarks' => $bookmarks
        ];

        $this->output->load('post/saved', $data);
    }

    public function toggle_bookmark(): void
    {
        header('Content-Type: application/json');

        session_start();
        if (!isset($_SESSION['user'])) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Bạn cần đăng nhập để lưu bài viết.'
            ]);
            exit();
        }

        // Đọc dữ liệu JSON từ request body
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);

        if (!isset($data['post_id']) || !is_numeric($data['post_id'])) {
            echo json_encode([
                'status' => 'error',
                'message' => 'ID bài viết không hợp lệ.'
            ]);
            exit();
        }

        $user_id = $_SESSION['user']['user_id'];
        $post_id = (int)$data['post_id'];

        $post_model = new PostModel();

        try {
            $result = $post_model->toggleBookmark($user_id, $post_id);

            if ($result['status'] === 'success') {
                echo json_encode($result);
            } else {
                echo json_encode($result);
            }
        } catch (\Exception $e) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Lỗi khi xử lý bookmark: ' . $e->getMessage()
            ]);
        }
        exit();
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


    public function toggle_like(): void
    {
        header('Content-Type: application/json');

        session_start();
        if (!isset($_SESSION['user'])) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Bạn cần đăng nhập để thích bài viết.'
            ]);
            exit();
        }

        // Đọc dữ liệu JSON từ request body
        $input = file_get_contents('php://input');
        $data = json_decode($input, true); // Chuyển JSON thành mảng PHP

        if (!isset($data['post_id']) || !is_numeric($data['post_id'])) {
            echo json_encode([
                'status' => 'error',
                'message' => 'ID bài viết không hợp lệ.'
            ]);
            exit();
        }

        $user_id = $_SESSION['user']['user_id'];
        $post_id = (int)$data['post_id'];

        $post_model = new PostModel();

        try {
            $result = $post_model->toggleLike($user_id, $post_id);

            if ($result['status'] === 'success') {
                // Cập nhật số lượt thích
                $likeCountResult = $post_model->getLikeCount($post_id);
                $result['like_count'] = $likeCountResult['like_count'];
                echo json_encode($result);
            } else {
                echo json_encode($result);
            }
        } catch (\Exception $e) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Lỗi khi xử lý lượt thích: ' . $e->getMessage()
            ]);
        }
        exit();
    }


    public function toggle_share(): void
    {
        header('Content-Type: application/json');

        session_start();
        if (!isset($_SESSION['user'])) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Bạn cần đăng nhập để chia sẻ bài viết.'
            ]);
            exit();
        }

        // Đọc dữ liệu JSON từ request body
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);

        if (!isset($data['post_id']) || !is_numeric($data['post_id'])) {
            echo json_encode([
                'status' => 'error',
                'message' => 'ID bài viết không hợp lệ.'
            ]);
            exit();
        }

        $user_id = $_SESSION['user']['user_id'];
        $post_id = (int)$data['post_id'];

        $post_model = new PostModel();

        try {
            $result = $post_model->toggleShare($user_id, $post_id);

            if ($result['status'] === 'success') {
                // Cập nhật số lượt chia sẻ
                $shareCountResult = $post_model->getShareCount($post_id);
                $result['share_count'] = $shareCountResult['share_count'];
                echo json_encode($result);
            } else {
                echo json_encode($result);
            }
        } catch (\Exception $e) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Lỗi khi xử lý lượt chia sẻ: ' . $e->getMessage()
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


    public function schedule(): void
    {
        session_start();
        if (!isset($_SESSION['user'])) {
            $_SESSION['toast'] = [
                'message' => 'Bạn cần đăng nhập để xem lịch trình.',
                'type' => 'error'
            ];
            header('Location: /login');
            exit();
        }

        $user = $_SESSION['user'];
        $post_model = new PostModel();

        // Lấy danh sách lịch trình của user
        $user_id = $user['user_id'];
        $schedules = $post_model->getUserSchedules($user_id, 10, 0); // Giới hạn 10 schedules, offset 0

        $data = [
            'title' => 'Lịch trình - ' . htmlspecialchars($user['full_name']),
            'schedules' => $schedules
        ];

        $this->output->load('post/schedule', $data);
    }


    public function create_schedule(): void
    {
        header('Content-Type: application/json');

        session_start();
        if (!isset($_SESSION['user'])) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Bạn cần đăng nhập để tạo lịch trình.'
            ]);
            exit();
        }

        $user_id = $_SESSION['user']['user_id'];
        $post_model = new PostModel();

        $data = json_decode(file_get_contents('php://input'), true);

        if (!isset($data['title']) || !isset($data['start_date']) || !isset($data['end_date'])) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Dữ liệu không hợp lệ. Vui lòng cung cấp tiêu đề, ngày bắt đầu và ngày kết thúc.'
            ]);
            exit();
        }

        $title = trim($data['title']);
        $start_date = $data['start_date'];
        $end_date = $data['end_date'];
        $location_id = $data['location_id'] ?? null;
        $description = $data['description'] ?? '';
        $status = $data['status'] ?? 'pending';

        if (empty($title)) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Tiêu đề không được để trống.'
            ]);
            exit();
        }

        $result = $post_model->createSchedule($user_id, $title, $start_date, $end_date, $location_id, $description, $status);

        echo json_encode($result);
        exit();
    }


    public function update_schedule(): void
    {
        header('Content-Type: application/json');

        session_start();
        if (!isset($_SESSION['user'])) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Bạn cần đăng nhập để cập nhật lịch trình.'
            ]);
            exit();
        }

        $user_id = $_SESSION['user']['user_id'];
        $post_model = new PostModel();

        $data = json_decode(file_get_contents('php://input'), true);

        if (!isset($data['schedule_id']) || !isset($data['title']) || !isset($data['start_date']) || !isset($data['end_date'])) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Dữ liệu không hợp lệ. Vui lòng cung cấp ID lịch trình, tiêu đề, ngày bắt đầu và ngày kết thúc.'
            ]);
            exit();
        }

        $schedule_id = (int)$data['schedule_id'];
        $title = trim($data['title']);
        $start_date = $data['start_date'];
        $end_date = $data['end_date'];
        $location_id = $data['location_id'] ?? null;
        $description = $data['description'] ?? '';
        $status = $data['status'] ?? 'pending';

        if (empty($title)) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Tiêu đề không được để trống.'
            ]);
            exit();
        }

        $result = $post_model->updateSchedule($schedule_id, $user_id, $title, $start_date, $end_date, $location_id, $description, $status);

        echo json_encode($result);
        exit();
    }

    public function delete_schedule(): void
    {
        header('Content-Type: application/json');

        session_start();
        if (!isset($_SESSION['user'])) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Bạn cần đăng nhập để xóa lịch trình.'
            ]);
            exit();
        }

        $user_id = $_SESSION['user']['user_id'];
        $post_model = new PostModel();

        $data = json_decode(file_get_contents('php://input'), true);

        if (!isset($data['schedule_id'])) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Dữ liệu không hợp lệ. Vui lòng cung cấp ID lịch trình.'
            ]);
            exit();
        }

        $schedule_id = (int)$data['schedule_id'];

        $result = $post_model->deleteSchedule($schedule_id, $user_id);

        echo json_encode($result);
        exit();
    }

    public function get_schedule($schedule_id): void
    {
        header('Content-Type: application/json');

        session_start();
        if (!isset($_SESSION['user'])) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Bạn cần đăng nhập để xem lịch trình.'
            ]);
            exit();
        }

        $user_id = $_SESSION['user']['user_id'];
        $post_model = new PostModel();

        $schedule = $post_model->getScheduleById($schedule_id, $user_id);

        if ($schedule['status'] === 'success') {
            echo json_encode([
                'status' => 'success',
                'data' => $schedule['data'],
                'message' => 'Lấy thông tin lịch trình thành công.'
            ]);
        } else {
            echo json_encode($schedule);
        }
        exit();
    }


    public function admin(): void
    {
        session_start();
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            $_SESSION['toast'] = [
                'message' => 'Bạn không có quyền truy cập trang quản trị.',
                'type' => 'error'
            ];
            header('Location: /');
            exit();
        }

        $post_model = new PostModel();

        // Lấy danh sách posts (mặc định, có thể lọc qua GET)
        $limit = (int)($_GET['limit'] ?? 10);
        $page = (int)($_GET['page'] ?? 1);
        $offset = ($page - 1) * $limit;
        $status = $_GET['status'] ?? null;
        $search = $_GET['search'] ?? '';

        $posts = $post_model->getAllPosts($limit, $offset, $status, $search);

        $data = [
            'title' => 'Quản trị - Danh sách bài viết',
            'posts' => $posts,
            'limit' => $limit,
            'page' => $page,
            'search' => $search,
            'status' => $status
        ];

        $this->output->load('post/admin', $data);
    }


    // API để quản lý posts, users, comments, schedules (tùy chọn)
    public function admin_update_post(): void
    {
        header('Content-Type: application/json');

        session_start();
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            echo json_encode([
                'status' => 'error',
                'message' => 'Bạn không có quyền thực hiện thao tác này.'
            ]);
            exit();
        }

        $data = json_decode(file_get_contents('php://input'), true);

        if (!isset($data['post_id']) || !isset($data['action'])) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Dữ liệu không hợp lệ.'
            ]);
            exit();
        }

        $post_id = (int)$data['post_id'];
        $action = $data['action']; // 'update', 'delete', v.v.

        $post_model = new PostModel();

        try {
            if ($action === 'delete') {
                $result = $post_model->deletePost($post_id);
            } elseif ($action === 'update' && isset($data['status'])) {
                $result = $post_model->updatePostStatus($post_id, $data['status']);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Hành động không hợp lệ.'
                ]);
                exit();
            }

            echo json_encode($result);
        } catch (\Exception $e) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Lỗi khi xử lý: ' . $e->getMessage()
            ]);
        }
        exit();
    }

    public function get_post($post_id): void
    {
        header('Content-Type: application/json');

        session_start();
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            echo json_encode([
                'status' => 'error',
                'message' => 'Bạn không có quyền truy cập.'
            ]);
            exit();
        }

        $post_model = new PostModel();
        $post = $post_model->getPostById($post_id);

        if ($post['status'] === 'success') {
            echo json_encode([
                'status' => 'success',
                'data' => $post['data'],
                'message' => 'Lấy thông tin bài viết thành công.'
            ]);
        } else {
            echo json_encode($post);
        }
        exit();
    }
}
