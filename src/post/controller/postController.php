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
        $data = ["title" => "Home"];
        $post_model = new PostModel();

        $this->output->load("post/home",  $data);
    }
}
