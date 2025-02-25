<?php

namespace Post;

use Engine\Base;
use Service\Database;


/**
 * Sample of how a model class is
 */
class PostModel extends Base
{
    public $database;
    public function __construct()
    {
        $this->database = new Database('localhost', 'root', 'travel_blog', '', $this->console);
        $this->database->initialize();
    }


    public function getPosts($limit = 10, $offset = 0, $status = 'published')
    {
        // 1. Lấy thông tin cơ bản của bài viết và thông tin người dùng
        $sql = "SELECT p.*, u.username, u.full_name, u.profile_picture
                FROM posts p
                JOIN users u ON p.user_id = u.user_id
                WHERE p.status = ?
                ORDER BY p.published_at DESC
                LIMIT ? OFFSET ?";

        $posts = $this->database->query($sql, [$status, $limit, $offset]);

        if (!$posts || empty($posts)) {
            return [];
        }

        // 2. Lấy thông tin thẻ và danh mục cho từng bài viết
        foreach ($posts as &$post) {
            // Lấy danh sách thẻ
            $tagSql = "SELECT t.tag_id, t.name, t.slug
                      FROM tags t
                      JOIN post_tags pt ON t.tag_id = pt.tag_id
                      WHERE pt.post_id = ?";

            $post['tags'] = $this->database->query($tagSql, [$post['post_id']]) ?: [];

            // Lấy danh sách danh mục
            $categorySql = "SELECT c.category_id, c.name, c.slug
                           FROM categories c
                           JOIN post_categories pc ON c.category_id = pc.category_id
                           WHERE pc.post_id = ?";

            $post['categories'] = $this->database->query($categorySql, [$post['post_id']]) ?: [];

            // Lấy số lượng bình luận
            $commentSql = "SELECT COUNT(*) as comment_count 
                          FROM comments 
                          WHERE post_id = ?";

            $commentCount = $this->database->query($commentSql, [$post['post_id']]);
            $post['comment_count'] = $commentCount ? $commentCount[0]['comment_count'] : 0;
        }

        return $posts;
    }


    public function create($user_id, $title, $content, $location_id = null, $featured_image = null, $tags = "", $status)
    {

        if (empty($title) || empty($content)) {
            return [
                'status' => 'error',
                'message' => 'Vui lòng điền đầy đủ thông tin.'
            ];
        }

        date_default_timezone_set('Asia/Ho_Chi_Minh');

        $slug = $this->createSlug($title);

        $published_at = ($status == 'published') ? date('Y-m-d H:i:s') : null;

        $check = $this->database->query("SELECT * FROM posts WHERE slug = ?", [$slug]);
        if ($check && !empty($check)) {
            $slug = $slug . '-' . rand(100, 999);
        }

        try {
            $sql = "INSERT INTO posts (user_id, title, slug, content, featured_image, published_at, location_id, `status`) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $post = $this->database->query($sql, [$user_id, $title, $slug, $content, $featured_image, $published_at, $location_id, $status]);


            if ($post == 0) {
                return [
                    'status' => 'error',
                    'message' => 'Tạo bài viết thất bại, vui lòng thử lại sau.'
                ];
            }

            $post_id = $post;

            if (!empty($tags)) {
                $tag_array = array_map('trim', explode(',', $tags));

                foreach ($tag_array as $tag_name) {
                    if (empty($tag_name)) continue;

                    $tag_slug = $this->createSlug($tag_name);

                    $sql = "SELECT tag_id FROM tags WHERE name = ?";
                    $tag = $this->database->query($sql, [$tag_name]);

                    if ($tag) {
                        $tag_id = $tag['tag_id'];
                    } else {
                        $exist = $this->slugExists($tag_slug);
                        if ($exist) {
                            $tag_slug = $tag_slug . '-' . rand(100, 999);
                        }
                        $sql = "INSERT INTO tags (name, slug) VALUES (?, ?)";
                        $new_tag = $this->database->query($sql, [$tag_name, $tag_slug]);
                        $tag_id = $new_tag;
                    }

                    $sql = "INSERT INTO post_tags (post_id, tag_id) VALUES (?, ?)";
                    $this->database->query($sql, [$post_id, $tag_id]);
                }
            }

            return [
                'status' => 'success',
                'message' => 'Bài viết đã được tạo.' . intval($post)
            ];
        } catch (\Exception $e) {
            //throw $th;
            return [
                "status" => "error",
                "message" => "Có lỗi xảy ra, vui lòng thử lại sau. " . $e->getMessage()
            ];
        }
    }

    private function createSlug($string)
    {
        $string = $this->removeAccents($string);

        $string = strtolower($string);

        $string = str_replace(' ', '-', $string);

        $string = preg_replace('/[^a-z0-9\-]/', '', $string);

        $string = preg_replace('/-+/', '-', $string);

        if (strlen($string) > 200) {
            $string = substr($string, 0, 200);
        }

        $string = trim($string, '-');

        return $string;
    }

    // Hàm kiểm tra slug đã tồn tại chưa
    private function slugExists($slug)
    {
        $sql = "SELECT COUNT(*) FROM posts WHERE slug = ?";
        $result = $this->database->query($sql, [$slug]);
        return $result['count'] > 0;
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
