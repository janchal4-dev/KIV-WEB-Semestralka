<?php

require_once __DIR__ . "/../models/PostModel.php";
require_once __DIR__ . "/../models/ReviewModel.php";

class ArticlesController {
    public function render() {

        $postModel = new PostModel();
        $reviewModel = new ReviewModel();

        $posts = $postModel->getAllPosts();

        // Připojíme recenze
        foreach ($posts as &$p) {
            $p["reviews"] = $reviewModel->getReviewsForPost($p["id_post"]);
        }

        $app = new MyApplication();
        $app->renderTwig("articles.twig", [
            "currentPage" => "articles",
            "posts" => $posts,
            "user" => $_SESSION["user"] ?? null
        ]);
    }
}
