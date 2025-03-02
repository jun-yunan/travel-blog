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


    // public function getPosts($limit = 10, $offset = 0, $status = 'published')
    // {
    //     try {
    //         // 1. Lấy thông tin cơ bản của bài viết và thông tin người dùng
    //         $sql = "SELECT p.*, u.username, u.full_name, u.profile_picture
    //                 FROM posts p
    //                 JOIN users u ON p.user_id = u.user_id
    //                 WHERE p.status = ?
    //                 ORDER BY p.published_at DESC
    //                 LIMIT ? OFFSET ?";
    //         $posts = $this->database->query($sql, [$status, $limit, $offset]);

    //         // Kiểm tra và chuẩn hóa $posts
    //         if (!$posts || !is_array($posts)) {
    //             return [];
    //         }

    //         // 2. Lấy thông tin thẻ và danh mục cho từng bài viết
    //         foreach ($posts as &$post) {
    //             // Lấy danh sách thẻ
    //             $tagSql = "SELECT t.tag_id, t.name, t.slug
    //                       FROM tags t
    //                       JOIN post_tags pt ON t.tag_id = pt.tag_id
    //                       WHERE pt.post_id = ?";
    //             $tags = $this->database->query($tagSql, [$post['post_id']]);
    //             $post['tags'] = is_array($tags) ? $tags : [];

    //             // Lấy danh sách danh mục
    //             $categorySql = "SELECT c.category_id, c.name, c.slug
    //                            FROM categories c
    //                            JOIN post_categories pc ON c.category_id = pc.category_id
    //                            WHERE pc.post_id = ?";
    //             $categories = $this->database->query($categorySql, [$post['post_id']]);
    //             $post['categories'] = is_array($categories) ? $categories : [];

    //             // Lấy số lượng bình luận
    //             $commentSql = "SELECT COUNT(*) as comment_count 
    //                           FROM comments 
    //                           WHERE post_id = ?";
    //             $commentCount = $this->database->query($commentSql, [$post['post_id']]);
    //             // Xử lý trường hợp $commentCount là scalar hoặc mảng

    //             if (is_array($commentCount) && isset($commentCount['comment_count'])) {
    //                 $post['comment_count'] = (int)$commentCount['comment_count'];
    //             } elseif (is_numeric($commentCount)) {
    //                 $post['comment_count'] = (int)$commentCount;
    //             } else {
    //                 $post['comment_count'] = 0;
    //             }
    //         }

    //         return $posts;
    //     } catch (\Exception $e) {

    //         return [];
    //     }
    // }


    public function getPosts($limit = 10, $offset = 0, $status = 'published')
    {
        try {
            // 1. Lấy thông tin cơ bản của bài viết và thông tin người dùng
            $sql = "SELECT p.*, u.username, u.full_name, u.profile_picture
                    FROM posts p
                    JOIN users u ON p.user_id = u.user_id
                    WHERE p.status = ?
                    ORDER BY p.published_at DESC
                    LIMIT ? OFFSET ?";
            $result = $this->database->query($sql, [$status, $limit, $offset]);

            $posts = is_array($result) && !isset($result[0]) ? [$result] : $result;

            // Kiểm tra và chuẩn hóa $posts
            if (!$posts || !is_array($posts)) {
                return [];
            }

            // 2. Lấy thông tin bổ sung cho từng bài viết
            foreach ($posts as &$post) {
                // Lấy danh sách thẻ
                $tagSql = "SELECT t.tag_id, t.name, t.slug
                          FROM tags t
                          JOIN post_tags pt ON t.tag_id = pt.tag_id
                          WHERE pt.post_id = ?";
                $tags = $this->database->query($tagSql, [$post['post_id']]);
                $post['tags'] = is_array($tags) ? $tags : [];

                // Lấy danh sách danh mục
                $categorySql = "SELECT c.category_id, c.name, c.slug
                               FROM categories c
                               JOIN post_categories pc ON c.category_id = pc.category_id
                               WHERE pc.post_id = ?";
                $categories = $this->database->query($categorySql, [$post['post_id']]);
                $post['categories'] = is_array($categories) ? $categories : [];

                // Lấy số lượng bình luận
                $commentSql = "SELECT COUNT(*) as comment_count 
                              FROM comments 
                              WHERE post_id = ?";
                $commentCount = $this->database->query($commentSql, [$post['post_id']]);

                // Xử lý $commentCount (sửa lỗi logic)
                if (is_array($commentCount) && isset($commentCount['comment_count'])) {
                    $post['comment_count'] = (int)$commentCount['comment_count'];
                } elseif (is_numeric($commentCount)) {
                    $post['comment_count'] = (int)$commentCount;
                } else {
                    $post['comment_count'] = 0;
                }

                // Lấy số lượt thích
                $likeCountResult = $this->getLikeCount($post['post_id']);
                $post['like_count'] = $likeCountResult['like_count'];

                // Kiểm tra xem user đã thích bài viết này chưa
                $isLikedResult = $this->isLikedByUser($post['user_id'], $post['post_id']);
                $post['liked'] = $isLikedResult['liked'];

                $isBookmarkedByUser = $this->isBookmarkedByUser($post['user_id'], $post['post_id']);
                $post['bookmarked'] = $isBookmarkedByUser['bookmarked'];

                $likeCountShared = $this->getShareCount($post['post_id']);
                $post['share_count'] = $likeCountShared['share_count'];
            }

            return $posts;
        } catch (\Exception $e) {
            // Log lỗi nếu cần
            // $this->console->log($e->getMessage());
            return [];
        }
    }



    public function toggleLike($user_id, $post_id)
    {
        try {
            // Kiểm tra bài post tồn tại và được publish
            $checkPostSql = "SELECT COUNT(*) as count FROM posts WHERE post_id = ? AND status = 'published'";
            $postCount = $this->database->query($checkPostSql, [$post_id]);

            if (!$postCount || !is_array($postCount) || empty($postCount) || $postCount['count'] == 0) {
                return [
                    'status' => 'error',
                    'message' => 'Bài viết không tồn tại hoặc chưa được công khai.'
                ];
            }

            // Kiểm tra user tồn tại
            $checkUserSql = "SELECT COUNT(*) as count FROM users WHERE user_id = ?";
            $userCount = $this->database->query($checkUserSql, [$user_id]);

            if (!$userCount || !is_array($userCount) || empty($userCount) || $userCount['count'] == 0) {
                return [
                    'status' => 'error',
                    'message' => 'Người dùng không hợp lệ.'
                ];
            }

            // Kiểm tra xem user đã thích bài post này chưa
            $checkLikeSql = "SELECT COUNT(*) as count FROM post_likes WHERE user_id = ? AND post_id = ?";
            $likeCount = $this->database->query($checkLikeSql, [$user_id, $post_id]);

            if (!$likeCount || !is_array($likeCount) || empty($likeCount)) {
                return [
                    'status' => 'error',
                    'message' => 'Không thể kiểm tra lượt thích.'
                ];
            }

            if ($likeCount['count'] > 0) {
                // Nếu đã thích, xóa lượt thích
                $deleteSql = "DELETE FROM post_likes WHERE user_id = ? AND post_id = ?";
                $this->database->query($deleteSql, [$user_id, $post_id]);
                return [
                    'status' => 'success',
                    'message' => 'Đã bỏ thích bài viết.',
                    'liked' => false
                ];
            } else {
                // Nếu chưa thích, thêm lượt thích
                $insertSql = "INSERT INTO post_likes (user_id, post_id) VALUES (?, ?)";
                $this->database->query($insertSql, [$user_id, $post_id]);
                return [
                    'status' => 'success',
                    'message' => 'Đã thích bài viết.',
                    'liked' => true
                ];
            }
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Lỗi khi xử lý lượt thích: ' . $e->getMessage()
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


    public function isLikedByUser($user_id, $post_id)
    {
        try {
            $sql = "SELECT COUNT(*) as count FROM post_likes WHERE user_id = ? AND post_id = ?";
            $result = $this->database->query($sql, [$user_id, $post_id]);

            if (!$result || !is_array($result) || empty($result)) {
                return [
                    'status' => 'success',
                    'liked' => false
                ];
            }

            return [
                'status' => 'success',
                'liked' => $result['count'] > 0
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Lỗi khi kiểm tra lượt thích: ' . $e->getMessage(),
                'liked' => false
            ];
        }
    }


    public function toggleShare($user_id, $post_id)
    {
        try {
            // Kiểm tra bài post tồn tại và được publish
            $checkPostSql = "SELECT COUNT(*) as count FROM posts WHERE post_id = ? AND status = 'published'";
            $postCount = $this->database->query($checkPostSql, [$post_id]);

            if (!$postCount || !is_array($postCount) || empty($postCount) || $postCount['count'] == 0) {
                return [
                    'status' => 'error',
                    'message' => 'Bài viết không tồn tại hoặc chưa được công khai.'
                ];
            }

            // Kiểm tra user tồn tại
            $checkUserSql = "SELECT COUNT(*) as count FROM users WHERE user_id = ?";
            $userCount = $this->database->query($checkUserSql, [$user_id]);

            if (!$userCount || !is_array($userCount) || empty($userCount) || $userCount['count'] == 0) {
                return [
                    'status' => 'error',
                    'message' => 'Người dùng không hợp lệ.'
                ];
            }

            // Kiểm tra xem user đã share bài post này chưa
            $checkShareSql = "SELECT COUNT(*) as count FROM post_shares WHERE user_id = ? AND post_id = ?";
            $shareCount = $this->database->query($checkShareSql, [$user_id, $post_id]);

            if (!$shareCount || !is_array($shareCount) || empty($shareCount)) {
                return [
                    'status' => 'error',
                    'message' => 'Không thể kiểm tra lượt chia sẻ.'
                ];
            }

            if ($shareCount['count'] > 0) {
                // Nếu đã share, xóa lượt share (nếu muốn cho phép bỏ share)
                $deleteSql = "DELETE FROM post_shares WHERE user_id = ? AND post_id = ?";
                $this->database->query($deleteSql, [$user_id, $post_id]);
                return [
                    'status' => 'success',
                    'message' => 'Đã bỏ chia sẻ bài viết.',
                    'shared' => false
                ];
            } else {
                // Nếu chưa share, thêm lượt share
                $insertSql = "INSERT INTO post_shares (user_id, post_id) VALUES (?, ?)";
                $this->database->query($insertSql, [$user_id, $post_id]);
                return [
                    'status' => 'success',
                    'message' => 'Đã chia sẻ bài viết.',
                    'shared' => true
                ];
            }
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Lỗi khi xử lý lượt chia sẻ: ' . $e->getMessage()
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


    public function isSharedByUser($user_id, $post_id)
    {
        try {
            $sql = "SELECT COUNT(*) as count FROM post_shares WHERE user_id = ? AND post_id = ?";
            $result = $this->database->query($sql, [$user_id, $post_id]);

            if (!$result || !is_array($result) || empty($result)) {
                return [
                    'status' => 'success',
                    'shared' => false
                ];
            }

            return [
                'status' => 'success',
                'shared' => $result['count'] > 0
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Lỗi khi kiểm tra lượt chia sẻ: ' . $e->getMessage(),
                'shared' => false
            ];
        }
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


        try {
            $check = $this->database->query("SELECT COUNT(*) as count FROM posts WHERE slug = ?", [$slug]);
            if ($check && isset($check['count']) && $check['count'] > 0) {
                $slug .= '-' . rand(100, 999);
            }


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

                    if ($tag && is_array($tag) && !empty($tag)) {
                        $tag_id = $tag[0]['tag_id'];
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

    public function createComment($user_id, $post_id, $content)
    {
        try {
            // Kiểm tra bài post tồn tại và được publish
            $checkPostSql = "SELECT COUNT(*) as count FROM posts WHERE post_id = ? AND status = 'published'";
            $post = $this->database->query($checkPostSql, [$post_id]);

            if (!$post || $post['count'] !== 1 || empty($post)) {
                return [
                    'status' => 'error',
                    'message' => 'Bài viết không tồn tại hoặc chưa được công khai.'
                ];
            }

            // Kiểm tra user tồn tại
            $checkUserSql = "SELECT COUNT(*) as count FROM users WHERE user_id = ?";
            $user = $this->database->query($checkUserSql, [$user_id]);

            if (!$user || $user['count'] !== 1 || empty($user)) {
                return [
                    'status' => 'error',
                    'message' => 'Người dùng không hợp lệ.'
                ];
            }

            // Lưu bình luận vào database
            $sql = "INSERT INTO comments (post_id, user_id, content) VALUES (?, ?, ?)";
            $comment_id = $this->database->query($sql, [$post_id, $user_id, $content]);

            return [
                'status' => 'success',
                'message' => 'Bình luận đã được gửi thành công!',
                'comment_id' => $comment_id
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Lỗi khi lưu bình luận: ' . $e->getMessage()
            ];
        }
    }

    // public function getComments($post_id, $limit = 5, $offset = 0)
    // {
    //     try {
    //         $sql = "SELECT c.*, u.username, u.full_name
    //             FROM comments c
    //             JOIN users u ON c.user_id = u.user_id
    //             WHERE c.post_id = ?
    //             ORDER BY c.created_at DESC
    //             LIMIT ? OFFSET ?";
    //         $comments = $this->database->query($sql, [$post_id, $limit, $offset]);
    //         return is_array($comments) ? $comments : [];
    //     } catch (\Exception $e) {
    //         return [];
    //     }
    // }

    public function getComments($post_id, $limit = 10, $offset = 0)
    {
        try {
            // Kiểm tra bài post tồn tại và được publish
            $checkPostSql = "SELECT COUNT(*) as count FROM posts WHERE post_id = ? AND status = 'published'";
            $postCount = $this->database->query($checkPostSql, [$post_id]);

            if (!$postCount  || empty($postCount) || $postCount['count'] == 0) {
                return [
                    'status' => 'error',
                    'message' => 'Bài viết không tồn tại hoặc chưa được công khai.'
                ];
            }

            // Lấy danh sách bình luận
            $sql = "SELECT c.comment_id, c.content, c.created_at, u.user_id, u.username, u.full_name, u.profile_picture
                    FROM comments c
                    JOIN users u ON c.user_id = u.user_id
                    WHERE c.post_id = ?
                    ORDER BY c.created_at DESC
                    LIMIT ? OFFSET ?";
            $result = $this->database->query($sql, [$post_id, $limit, $offset]);

            $comments = is_array($result) && !isset($result[0]) ? [$result] : $result;

            if (!$comments || !is_array($comments) || empty($comments)) {
                return [
                    'status' => 'success',
                    'data' => [],
                    'message' => 'Không có bình luận nào.'
                ];
            }


            return [
                'status' => 'success',
                'data' => $comments,
                'message' => 'Lấy danh sách bình luận thành công.'
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Lỗi khi lấy bình luận: ' . $e->getMessage()
            ];
        }
    }

    public function updatePost($post_id, $title, $content, $featured_image, $status)
    {
        try {
            $sql = "UPDATE posts SET title = ?, content = ?, featured_image = ?, status = ?, updated_at = CURRENT_TIMESTAMP WHERE post_id = ?";
            $result = $this->database->query($sql, [$title, $content, $featured_image, $status, $post_id]);


            return [
                'status' => 'success',
                'message' => 'Cập nhật bài viết thành công!'
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Lỗi khi cập nhật bài viết: ' . $e->getMessage()
            ];
        }
    }

    public function getPostBySlug($slug)
    {
        try {
            $sql = "SELECT p.*, u.username, u.full_name, u.profile_picture
                    FROM posts p
                    JOIN users u ON p.user_id = u.user_id
                    WHERE p.slug = ? AND p.status = 'published'
                    LIMIT 1";
            $posts = $this->database->query($sql, [$slug]);

            if (!$posts || !is_array($posts) || empty($posts)) {
                return null;
            }

            $post = $posts; // Lấy bài viết đầu tiên

            // Lấy tags
            $tagSql = "SELECT t.tag_id, t.name, t.slug
                      FROM tags t
                      JOIN post_tags pt ON t.tag_id = pt.tag_id
                      WHERE pt.post_id = ?";
            $tags = $this->database->query($tagSql, [$post['post_id']]);
            $post['tags'] = is_array($tags) ? $tags : [];

            // Lấy categories
            $categorySql = "SELECT c.category_id, c.name, c.slug
                           FROM categories c
                           JOIN post_categories pc ON c.category_id = pc.category_id
                           WHERE pc.post_id = ?";
            $categories = $this->database->query($categorySql, [$post['post_id']]);
            $post['categories'] = is_array($categories) ? $categories : [];

            // Lấy số lượng bình luận
            $commentSql = "SELECT COUNT(*) as comment_count 
                          FROM comments 
                          WHERE post_id = ?";
            $commentCount = $this->database->query($commentSql, [$post['post_id']]);
            $post['comment_count'] = is_array($commentCount) && isset($commentCount[0]['comment_count']) ? (int)$commentCount[0]['comment_count'] : (is_numeric($commentCount) ? (int)$commentCount : 0);

            return [
                'status' => 'success',
                'data' => $post
            ];
        } catch (\Exception $e) {
            return null;
        }
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

    public function search($query, $limit = 5)
    {
        if (empty($query)) {
            return [
                'status' => 'error',
                'message' => 'Vui lòng nhập từ khóa tìm kiếm.'
            ];
        }

        try {
            // Chuẩn bị từ khóa tìm kiếm
            $searchTerm = '%' . trim($query) . '%';

            // Truy vấn chính: Tìm kiếm trong title và content
            $sql = "SELECT p.post_id, p.title, p.slug, p.published_at, p.featured_image, 
                           u.username, u.full_name, u.profile_picture
                    FROM posts p
                    JOIN users u ON p.user_id = u.user_id
                    WHERE (p.title LIKE ? OR p.content LIKE ?)
                    AND p.status = 'published'
                    ORDER BY p.published_at DESC
                    LIMIT ?";
            $posts = $this->database->query($sql, [$searchTerm, $searchTerm, $limit]);

            if (!$posts || empty($posts)) {
                return [
                    'status' => 'success',
                    'data' => []
                ];
            }

            // Lấy thông tin bổ sung cho từng bài viết
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

            return [
                'status' => 'success',
                'data' => $posts
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Lỗi tìm kiếm: ' . $e->getMessage()
            ];
        }
    }


    public function toggleBookmark($user_id, $post_id)
    {
        try {
            // Kiểm tra bài post tồn tại và được publish
            $checkPostSql = "SELECT COUNT(*) as count FROM posts WHERE post_id = ? AND status = 'published'";
            $postCount = $this->database->query($checkPostSql, [$post_id]);

            if (!$postCount || !is_array($postCount) || empty($postCount) || $postCount['count'] == 0) {
                return [
                    'status' => 'error',
                    'message' => 'Bài viết không tồn tại hoặc chưa được công khai.'
                ];
            }

            // Kiểm tra user tồn tại
            $checkUserSql = "SELECT COUNT(*) as count FROM users WHERE user_id = ?";
            $userCount = $this->database->query($checkUserSql, [$user_id]);

            if (!$userCount || !is_array($userCount) || empty($userCount) || $userCount['count'] == 0) {
                return [
                    'status' => 'error',
                    'message' => 'Người dùng không hợp lệ.'
                ];
            }

            // Kiểm tra xem user đã bookmark bài post này chưa
            $checkBookmarkSql = "SELECT COUNT(*) as count FROM post_bookmarks WHERE user_id = ? AND post_id = ?";
            $bookmarkCount = $this->database->query($checkBookmarkSql, [$user_id, $post_id]);

            if (!$bookmarkCount || !is_array($bookmarkCount) || empty($bookmarkCount)) {
                return [
                    'status' => 'error',
                    'message' => 'Không thể kiểm tra bookmark.'
                ];
            }

            if ($bookmarkCount['count'] > 0) {
                // Nếu đã bookmark, xóa bookmark
                $deleteSql = "DELETE FROM post_bookmarks WHERE user_id = ? AND post_id = ?";
                $this->database->query($deleteSql, [$user_id, $post_id]);
                return [
                    'status' => 'success',
                    'message' => 'Đã bỏ lưu bài viết.',
                    'bookmarked' => false
                ];
            } else {
                // Nếu chưa bookmark, thêm bookmark
                $insertSql = "INSERT INTO post_bookmarks (user_id, post_id) VALUES (?, ?)";
                $this->database->query($insertSql, [$user_id, $post_id]);
                return [
                    'status' => 'success',
                    'message' => 'Đã lưu bài viết.',
                    'bookmarked' => true
                ];
            }
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Lỗi khi xử lý bookmark: ' . $e->getMessage()
            ];
        }
    }


    public function getBookmarkCount($post_id)
    {
        try {
            $sql = "SELECT COUNT(*) as bookmark_count FROM post_bookmarks WHERE post_id = ?";
            $result = $this->database->query($sql, [$post_id]);

            if (!$result || !is_array($result) || empty($result)) {
                return [
                    'status' => 'success',
                    'bookmark_count' => 0
                ];
            }

            return [
                'status' => 'success',
                'bookmark_count' => (int)$result['bookmark_count']
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Lỗi khi lấy số lượt lưu: ' . $e->getMessage(),
                'bookmark_count' => 0
            ];
        }
    }


    public function isBookmarkedByUser($user_id, $post_id)
    {
        try {
            $sql = "SELECT COUNT(*) as count FROM post_bookmarks WHERE user_id = ? AND post_id = ?";
            $result = $this->database->query($sql, [$user_id, $post_id]);

            if (!$result || !is_array($result) || empty($result)) {
                return [
                    'status' => 'success',
                    'bookmarked' => false
                ];
            }

            return [
                'status' => 'success',
                'bookmarked' => $result['count'] > 0
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Lỗi khi kiểm tra bookmark: ' . $e->getMessage(),
                'bookmarked' => false
            ];
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


    public function getUserBookmarks($user_id, $limit = 10, $offset = 0)
    {
        try {
            $sql = "SELECT p.*, u.username, u.full_name, u.profile_picture
                    FROM posts p
                    JOIN users u ON p.user_id = u.user_id
                    JOIN post_bookmarks pb ON p.post_id = pb.post_id
                    WHERE pb.user_id = ? AND p.status = 'published'
                    ORDER BY pb.created_at DESC
                    LIMIT ? OFFSET ?";
            $result = $this->database->query($sql, [$user_id, $limit, $offset]);

            $bookmarks = is_array($result) && !isset($result[0]) ? [$result] : $result;

            if (!$bookmarks || !is_array($bookmarks)) {
                return [];
            }

            foreach ($bookmarks as &$bookmark) {
                $bookmark['like_count'] = $this->getLikeCount($bookmark['post_id'])['like_count'];
                $bookmark['share_count'] = $this->getShareCount($bookmark['post_id'])['share_count'];
                $bookmark['comment_count'] = $this->getCommentsCount($bookmark['post_id'])['comment_count'];

                // Lấy tags
                $tagSql = "SELECT t.tag_id, t.name, t.slug
                          FROM tags t
                          JOIN post_tags pt ON t.tag_id = pt.tag_id
                          WHERE pt.post_id = ?";
                $tags = $this->database->query($tagSql, [$bookmark['post_id']]);
                $bookmark['tags'] = is_array($tags) ? $tags : [];

                // Lấy categories
                $categorySql = "SELECT c.category_id, c.name, c.slug
                               FROM categories c
                               JOIN post_categories pc ON c.category_id = pc.category_id
                               WHERE pc.post_id = ?";
                $categories = $this->database->query($categorySql, [$bookmark['post_id']]);
                $bookmark['categories'] = is_array($categories) ? $categories : [];
            }

            return $bookmarks;
        } catch (\Exception $e) {
            return [];
        }
    }


    public function getUserSchedules($user_id, $limit = 10, $offset = 0, $status = null)
    {
        try {
            $sql = "SELECT s.*, l.name as location_name
                    FROM schedules s
                    LEFT JOIN locations l ON s.location_id = l.location_id
                    WHERE s.user_id = ?";

            $params = [$user_id];

            if ($status) {
                $sql .= " AND s.status = ?";
                $params[] = $status;
            }

            $sql .= " ORDER BY s.start_date DESC
                     LIMIT ? OFFSET ?";
            $params[] = $limit;
            $params[] = $offset;

            $schedules = $this->database->query($sql, $params);

            if (!$schedules || !is_array($schedules)) {
                return [];
            }

            return $schedules;
        } catch (\Exception $e) {
            return [];
        }
    }

    public function createSchedule($user_id, $title, $start_date, $end_date, $location_id = null, $description = '', $status = 'pending')
    {
        try {
            $sql = "INSERT INTO schedules (user_id, title, start_date, end_date, location_id, description, status) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            $schedule_id = $this->database->query($sql, [$user_id, $title, $start_date, $end_date, $location_id, $description, $status]);

            // $schedule_id = $this->database->lastInsertId();

            return [
                'status' => 'success',
                'message' => 'Tạo lịch trình thành công!',
                'schedule_id' => $schedule_id
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Lỗi khi tạo lịch trình: ' . $e->getMessage()
            ];
        }
    }


    public function updateSchedule($schedule_id, $user_id, $title, $start_date, $end_date, $location_id = null, $description = '', $status = 'pending')
    {
        try {
            $sql = "UPDATE schedules 
                    SET title = ?, start_date = ?, end_date = ?, location_id = ?, description = ?, status = ?, updated_at = CURRENT_TIMESTAMP 
                    WHERE schedule_id = ? AND user_id = ?";
            $result = $this->database->query($sql, [$title, $start_date, $end_date, $location_id, $description, $status, $schedule_id, $user_id]);

            if ($result) {
                return [
                    'status' => 'success',
                    'message' => 'Cập nhật lịch trình thành công!'
                ];
            } else {
                return [
                    'status' => 'error',
                    'message' => 'Cập nhật lịch trình thất bại. Vui lòng kiểm tra quyền truy cập.'
                ];
            }
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Lỗi khi cập nhật lịch trình: ' . $e->getMessage()
            ];
        }
    }


    public function deleteSchedule($schedule_id, $user_id)
    {
        try {
            $sql = "DELETE FROM schedules WHERE schedule_id = ? AND user_id = ?";
            $result = $this->database->query($sql, [$schedule_id, $user_id]);

            if ($result) {
                return [
                    'status' => 'success',
                    'message' => 'Xóa lịch trình thành công!'
                ];
            } else {
                return [
                    'status' => 'error',
                    'message' => 'Xóa lịch trình thất bại. Vui lòng kiểm tra quyền truy cập.'
                ];
            }
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Lỗi khi xóa lịch trình: ' . $e->getMessage()
            ];
        }
    }

    public function getScheduleById($schedule_id, $user_id)
    {
        try {
            $sql = "SELECT s.*, l.name as location_name
                FROM schedules s
                LEFT JOIN locations l ON s.location_id = l.location_id
                WHERE s.schedule_id = ? AND s.user_id = ?";
            $schedule = $this->database->query($sql, [$schedule_id, $user_id]);

            if (!$schedule || !is_array($schedule) || empty($schedule)) {
                return [
                    'status' => 'error',
                    'message' => 'Lịch trình không tồn tại hoặc không thuộc về bạn.'
                ];
            }

            return [
                'status' => 'success',
                'data' => $schedule,
                'message' => 'Lấy thông tin lịch trình thành công.'
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Lỗi khi lấy thông tin lịch trình: ' . $e->getMessage()
            ];
        }
    }


    // ADMIN

    public function getAllPosts($limit = 10, $offset = 0, $status = null, $search = '')
    {
        try {
            $sql = "SELECT p.*, u.username, u.full_name, u.role
                    FROM posts p
                    JOIN users u ON p.user_id = u.user_id
                    WHERE 1=1";

            $params = [];

            if ($status) {
                $sql .= " AND p.status = ?";
                $params[] = $status;
            }

            if (!empty($search)) {
                $sql .= " AND (p.title LIKE ? OR p.content LIKE ?)";
                $params[] = "%" . $search . "%";
                $params[] = "%" . $search . "%";
            }

            $sql .= " ORDER BY p.published_at DESC
                     LIMIT ? OFFSET ?";
            $params[] = $limit;
            $params[] = $offset;

            $posts = $this->database->query($sql, $params);

            if (!$posts || !is_array($posts)) {
                return [];
            }

            foreach ($posts as &$post) {
                $post['like_count'] = $this->getLikeCount($post['post_id'])['like_count'];
                $post['share_count'] = $this->getShareCount($post['post_id'])['share_count'];
                $post['comment_count'] = $this->getCommentsCount($post['post_id'])['comment_count'];

                // Lấy tags
                $tagSql = "SELECT t.tag_id, t.name, t.slug
                          FROM tags t
                          JOIN post_tags pt ON t.tag_id = pt.tag_id
                          WHERE pt.post_id = ?";
                $tags = $this->database->query($tagSql, [$post['post_id']]);
                $post['tags'] = is_array($tags) ? $tags : [];

                // Lấy categories
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

    public function getAllUsers($limit = 10, $offset = 0, $search = '')
    {
        try {
            $sql = "SELECT user_id, username, full_name, email, role, created_at
                    FROM users
                    WHERE 1=1";

            $params = [];

            if (!empty($search)) {
                $sql .= " AND (username LIKE ? OR full_name LIKE ? OR email LIKE ?)";
                $params[] = "%" . $search . "%";
                $params[] = "%" . $search . "%";
                $params[] = "%" . $search . "%";
            }

            $sql .= " ORDER BY created_at DESC
                     LIMIT ? OFFSET ?";
            $params[] = $limit;
            $params[] = $offset;

            $users = $this->database->query($sql, $params);

            if (!$users || !is_array($users)) {
                return [];
            }

            return $users;
        } catch (\Exception $e) {
            return [];
        }
    }

    public function getAllComments($limit = 10, $offset = 0, $search = '')
    {
        try {
            $sql = "SELECT c.*, p.title as post_title, u.username, u.full_name
                    FROM comments c
                    JOIN posts p ON c.post_id = p.post_id
                    JOIN users u ON c.user_id = u.user_id
                    WHERE 1=1";

            $params = [];

            if (!empty($search)) {
                $sql .= " AND (c.content LIKE ? OR p.title LIKE ? OR u.username LIKE ?)";
                $params[] = "%" . $search . "%";
                $params[] = "%" . $search . "%";
                $params[] = "%" . $search . "%";
            }

            $sql .= " ORDER BY c.created_at DESC
                     LIMIT ? OFFSET ?";
            $params[] = $limit;
            $params[] = $offset;

            $comments = $this->database->query($sql, $params);

            if (!$comments || !is_array($comments)) {
                return [];
            }

            return $comments;
        } catch (\Exception $e) {
            return [];
        }
    }

    public function getAllSchedules($limit = 10, $offset = 0, $search = '')
    {
        try {
            $sql = "SELECT s.*, u.username, u.full_name, l.name as location_name
                    FROM schedules s
                    JOIN users u ON s.user_id = u.user_id
                    LEFT JOIN locations l ON s.location_id = l.location_id
                    WHERE 1=1";

            $params = [];

            if (!empty($search)) {
                $sql .= " AND (s.title LIKE ? OR s.description LIKE ? OR u.username LIKE ?)";
                $params[] = "%" . $search . "%";
                $params[] = "%" . $search . "%";
                $params[] = "%" . $search . "%";
            }

            $sql .= " ORDER BY s.start_date DESC
                     LIMIT ? OFFSET ?";
            $params[] = $limit;
            $params[] = $offset;

            $schedules = $this->database->query($sql, $params);

            if (!$schedules || !is_array($schedules)) {
                return [];
            }

            return $schedules;
        } catch (\Exception $e) {
            return [];
        }
    }


    public function deletePost($post_id)
    {
        try {
            $sql = "DELETE FROM posts WHERE post_id = ?";
            $result = $this->database->query($sql, [$post_id]);


            return [
                'status' => 'success',
                'message' => 'Xóa bài viết thành công!'
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Lỗi khi xóa bài viết: ' . $e->getMessage()
            ];
        }
    }


    public function updatePostStatus($post_id, $status)
    {
        try {
            $sql = "UPDATE posts SET status = ? WHERE post_id = ?";
            $result = $this->database->query($sql, [$status, $post_id]);


            return [
                'status' => 'success',
                'message' => 'Cập nhật trạng thái bài viết thành công!'
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Lỗi khi cập nhật trạng thái: ' . $e->getMessage()
            ];
        }
    }


    public function getPostById($post_id)
    {
        try {
            $sql = "SELECT p.*, u.username, u.full_name, u.role
                FROM posts p
                JOIN users u ON p.user_id = u.user_id
                WHERE p.post_id = ?";
            $post = $this->database->query($sql, [$post_id]);

            if (!$post || !is_array($post) || empty($post)) {
                return [
                    'status' => 'error',
                    'message' => 'Bài viết không tồn tại.'
                ];
            }

            // $post = $post[0];
            $post['like_count'] = $this->getLikeCount($post['post_id'])['like_count'];
            $post['share_count'] = $this->getShareCount($post['post_id'])['share_count'];
            $post['comment_count'] = $this->getCommentsCount($post['post_id'])['comment_count'];

            // Lấy tags
            $tagSql = "SELECT t.tag_id, t.name, t.slug
                  FROM tags t
                  JOIN post_tags pt ON t.tag_id = pt.tag_id
                  WHERE pt.post_id = ?";
            $tags = $this->database->query($tagSql, [$post['post_id']]);
            $post['tags'] = is_array($tags) ? $tags : [];

            // Lấy categories
            $categorySql = "SELECT c.category_id, c.name, c.slug
                       FROM categories c
                       JOIN post_categories pc ON c.category_id = pc.category_id
                       WHERE pc.post_id = ?";
            $categories = $this->database->query($categorySql, [$post['post_id']]);
            $post['categories'] = is_array($categories) ? $categories : [];

            return [
                'status' => 'success',
                'data' => $post,
                'message' => 'Lấy thông tin bài viết thành công.'
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Lỗi khi lấy bài viết: ' . $e->getMessage()
            ];
        }
    }


    public function getAllLocations($limit = 100, $offset = 0, $search = '')
    {
        try {
            $sql = "SELECT location_id, name, country, city
                    FROM locations
                    WHERE 1=1";

            $params = [];

            if (!empty($search)) {
                $sql .= " AND (name LIKE ? OR country LIKE ? OR city LIKE ?)";
                $params[] = "%" . $search . "%";
                $params[] = "%" . $search . "%";
                $params[] = "%" . $search . "%";
            }

            $sql .= " ORDER BY name ASC
                     LIMIT ? OFFSET ?";
            $params[] = $limit;
            $params[] = $offset;

            $locations = $this->database->query($sql, $params);

            if (!$locations || !is_array($locations)) {
                return [];
            }

            return $locations;
        } catch (\Exception $e) {
            return [];
        }
    }
}
