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


    public function getUserById($user_id)
    {
        try {
            $sql = "SELECT user_id, username, full_name, profile_picture, cover_image, bio, email, created_at 
                    FROM users 
                    WHERE user_id = ?";
            $user = $this->database->query($sql, [$user_id]);

            if (!$user || !is_array($user) || empty($user)) {
                return null;
            }

            return $user;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function updateUserCoverImage($user_id, $cover_image)
    {
        try {
            $sql = "UPDATE users SET cover_image = ? WHERE user_id = ?";
            $result = $this->database->query($sql, [$cover_image, $user_id]);

            if ($result) {
                return [
                    'status' => 'success',
                    'message' => 'Cập nhật ảnh bìa thành công.'
                ];
            } else {
                return [
                    'status' => 'error',
                    'message' => 'Cập nhật ảnh bìa thất bại.'
                ];
            }
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Lỗi khi cập nhật ảnh bìa: ' . $e->getMessage()
            ];
        }
    }


    public function updateUserProfile($user_id, $full_name, $username, $email, $profile_picture = null, $bio = null)
    {
        try {
            // Kiểm tra username không trùng lặp (trừ user hiện tại)
            $checkUsernameSql = "SELECT COUNT(*) as count FROM users WHERE username = ? AND user_id != ?";
            $usernameCount = $this->database->query($checkUsernameSql, [$username, $user_id]);

            if ($usernameCount && $usernameCount['count'] > 0) {
                return [
                    'status' => 'error',
                    'message' => 'Username đã tồn tại. Vui lòng chọn username khác.'
                ];
            }

            if (isset($profile_picture)) {
                $sql = "UPDATE users SET full_name = ?, username = ?, email = ?, profile_picture = ?, bio = ? WHERE user_id = ?";
                $params = [$full_name, $username, $email, $profile_picture, $bio, $user_id];

                $result = $this->database->query($sql, $params);

                return [
                    'status' => 'success',
                    'message' => 'Cập nhật hồ sơ thành công!'
                ];
            } else {
                $sql = "UPDATE users SET full_name = ?, username = ?, email = ?, bio = ? WHERE user_id = ?";
                $params = [$full_name, $username, $email, $bio, $user_id];
                $result = $this->database->query($sql, $params);
                return [
                    'status' => 'success',
                    'message' => 'Cập nhật hồ sơ thành công!'
                ];
            }
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Lỗi khi cập nhật hồ sơ: ' . $e->getMessage()
            ];
        }
    }

    public function getUserPosts($user_id, $limit = 10, $offset = 0)
    {
        try {
            $sql = "SELECT p.*, u.username, u.full_name, u.profile_picture
                    FROM posts p
                    JOIN users u ON p.user_id = u.user_id
                    WHERE p.user_id = ? AND p.status = 'published'
                    ORDER BY p.published_at DESC
                    LIMIT ? OFFSET ?";
            $posts = $this->database->query($sql, [$user_id, $limit, $offset]);

            if (!$posts || !is_array($posts)) {
                return [];
            }

            foreach ($posts as &$post) {
                $post['like_count'] = $this->getLikeCount($post['post_id'])['like_count'];
                $post['share_count'] = $this->getShareCount($post['post_id'])['share_count'];
                $post['comment_count'] = $this->getCommentsCount($post['post_id'])['comment_count'];

                // Lấy tags (giữ nguyên logic)
                $tagSql = "SELECT t.tag_id, t.name, t.slug
                          FROM tags t
                          JOIN post_tags pt ON t.tag_id = pt.tag_id
                          WHERE pt.post_id = ?";
                $tags = $this->database->query($tagSql, [$post['post_id']]);
                $post['tags'] = is_array($tags) ? $tags : [];

                // Lấy categories (giữ nguyên logic)
                $categorySql = "SELECT c.category_id, c.name, c.slug
                               FROM categories c
                               JOIN post_categories pc ON c.category_id = pc.category_id
                               WHERE pc.post_id = ?";
                $categories = $this->database->query($categorySql, [$post['post_id']]);
                $post['categories'] = is_array($categories) ? $categories : [];
            }

            return $posts;
        } catch (\Exception $e) {
            return [];
        }
    }


    public function getCommentsCount($post_id)
    {
        try {
            $sql = "SELECT COUNT(*) as comment_count FROM comments WHERE post_id = ?";
            $result = $this->database->query($sql, [$post_id]);

            if (!$result || !is_array($result) || empty($result)) {
                return [
                    'status' => 'success',
                    'comment_count' => 0
                ];
            }

            return [
                'status' => 'success',
                'comment_count' => (int)$result[0]['comment_count']
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Lỗi khi lấy số bình luận: ' . $e->getMessage(),
                'comment_count' => 0
            ];
        }
    }

    public function getLikeCount($post_id)
    {
        try {
            $sql = "SELECT COUNT(*) as like_count FROM post_likes WHERE post_id = ?";
            $result = $this->database->query($sql, [$post_id]);

            if (!$result || !is_array($result) || empty($result)) {
                return [
                    'status' => 'success',
                    'like_count' => 0
                ];
            }

            return [
                'status' => 'success',
                'like_count' => (int)$result['like_count']
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Lỗi khi lấy số lượt thích: ' . $e->getMessage(),
                'like_count' => 0
            ];
        }
    }

    public function getShareCount($post_id)
    {
        try {
            $sql = "SELECT COUNT(*) as share_count FROM post_shares WHERE post_id = ?";
            $result = $this->database->query($sql, [$post_id]);

            if (!$result || !is_array($result) || empty($result)) {
                return [
                    'status' => 'success',
                    'share_count' => 0
                ];
            }

            return [
                'status' => 'success',
                'share_count' => (int)$result['share_count']
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Lỗi khi lấy số lượt chia sẻ: ' . $e->getMessage(),
                'share_count' => 0
            ];
        }
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
