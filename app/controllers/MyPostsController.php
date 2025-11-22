<?php
require_once MODEL_PATH . "/PostModel.php";

class MyPostsController {

    public function render() {

        if (session_status() === PHP_SESSION_NONE) session_start();

        // jen autor (role_id = 4)
        if ($_SESSION["user"]["roles_id"] != 4) {
            header("Location: index.php?page=home");
            exit;
        }

        $user = $_SESSION["user"];

        $model = new PostModel();
        $posts = $model->getPostsByAuthor($user["id_user"]);

        (new MyApplication())->renderTwig("myPosts.twig", [
            "currentPage" => "myPosts",
            "user"  => $user,
            "posts" => $posts
        ]);
    }
}
