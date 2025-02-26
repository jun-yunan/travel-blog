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
}
