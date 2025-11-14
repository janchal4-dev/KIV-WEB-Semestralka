<?php

require_once __DIR__ . "/../models/PostModel.php";
require_once __DIR__ . "/../models/ReviewModel.php";

class ArticlesController {

    public function render() {
        // Spustí session jen pokud ještě neběží
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION["user"])) {
            header("Location: index.php?page=login");
            exit;
        }

        $user = $_SESSION["user"];
        $model = new PostModel();

        // načti články podle role
        $posts = $model->getPostsWithReviews($user["id_user"], $user["roles_id"]);

        // načti recenze
        foreach ($posts as &$p) {
            $p["reviews"] = $model->getReviewsForPost($p["id_post"]);
        }

        $app = new MyApplication();
        $app->renderTwig("articles.twig", [
            "currentPage" => "articles",
            "posts" => $posts,
            "user" => $user
        ]);
    }
}
