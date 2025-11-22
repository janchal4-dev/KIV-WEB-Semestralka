<?php

require_once MODEL_PATH . "/PostModel.php";
require_once MODEL_PATH . "/ReviewModel.php";

class MyPostsController
{
    public function render()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION["user"])) {
            header("Location: index.php?page=login");
            exit;
        }

        // Jen autor (role 4)
        $user = $_SESSION["user"];
        if ($user["roles_id"] != 4) {
            header("Location: index.php?page=home");
            exit;
        }

        $postModel   = new PostModel();
        $reviewModel = new ReviewModel();

        // články autora
        $posts = $postModel->getPostsByAuthor($user["id_user"]);

        // ke každému článku přidej recenze
        foreach ($posts as &$post) {
            $post["reviews"] = $reviewModel->getReviewsForPost($post["id_post"]);
        }


        (new MyApplication())->renderTwig("myPosts.twig", [
            "currentPage" => "myPosts",
            "user"        => $user,
            "posts"       => $posts
        ]);
    }
}
