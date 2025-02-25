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
    "/posts/search" => "post/post/search",
];
