<?php

require_once __DIR__ . "/../models/PostModel.php";
require_once __DIR__ . "/../models/ReviewModel.php";

class ArticlesController {

    public function render() {

        if (session_status() === PHP_SESSION_NONE) session_start();

        if (empty($_SESSION["user"])) {
            header("Location: index.php?page=login");
            exit;
        }

        $model = new PostModel();

        // pouze SCHVÁLENÉ články pro každého
        $posts = $model->getApprovedPosts();

        // načti recenze k článkům
        foreach ($posts as &$p) {
            $p["reviews"] = $model->getReviewsForPost($p["id_post"]);
        }

        (new MyApplication())->renderTwig("articles.twig", [
            "currentPage" => "articles",
            "posts" => $posts,
            "user" => $_SESSION["user"]
        ]);
    }
}
