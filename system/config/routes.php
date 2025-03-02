<?php

/**
 * Array key => url
 * Array value => folder/controller-class/method (index method if none specified)
 */

return [
    "/" => "post/post/home",
    "/login" => "user/user/login",
    "/register" => "user/user/register",
    "/logout" => "user/user/logout",
    "/posts/create" => "post/post/create",
    "/schedule" => "post/post/schedule",
    "/saved" => "post/post/saved",
    "/api/toggle-bookmark" => "post/post/toggle_bookmark",
    "/profile" => "user/user/profile",
    "/api/profile/update" => "user/user/update_profile",
    "/api/users/me" => "user/user/get_me",
    "/posts/search" => "post/post/search",
    "/posts/view-post" => "post/post/detail",
    "/posts/comments/create" => "post/post/create_comment",
    "/api/comments/create" => "post/post/create_comment",
    "/api/comments/get" => "post/post/get_comments",
    "/api/toggle-like" => "post/post/toggle_like",
    "/api/toggle-share" => "post/post/toggle_share",
    "/api/schedules/create" => "post/post/create_schedule",
    "/api/schedules/update" => "post/post/update_schedule",
    "/api/schedules/delete" => "post/post/delete_schedule",
    "/api/schedules/get" => "post/post/get_schedule",
    "/api/locations" => "post/post/get_locations",
    "/api/posts/update" => "post/post/update_post",
    "/api/posts/delete" => "post/post/delete_post",
    "/api/posts/get" => "post/post/get_post_by_id",
    "/api/toggle-follow" => "post/post/toggle_follow",
    "/api/is-following" => "post/post/is_following",
    "/admin" => "post/post/admin",
    "/api/admin/update-post" => "post/post/admin_update_post",
    "/admin/posts" => "post/post/admin_posts",
    "/admin/users" => "user/user/admin_users",
];
