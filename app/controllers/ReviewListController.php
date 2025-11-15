<?php
require_once MODEL_PATH . "/PostModel.php";

class ReviewListController
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

        $user = $_SESSION["user"];

        // jen recenzent (role_id = 3)
        if ($user["roles_id"] != 3) {
            header("Location: index.php?page=home");
            exit;
        }

        $postModel = new PostModel();
        $posts = $postModel->getPostsForReviewer($user["id_user"]);

        $app = new MyApplication();
        $app->renderTwig("reviewList.twig", [
            "user" => $user,
            "currentPage" => "reviewList",
            "posts"       => $posts,
        ]);
    }
}
