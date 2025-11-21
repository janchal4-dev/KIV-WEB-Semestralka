<?php

require_once __DIR__ . "/../models/PostModel.php";
require_once __DIR__ . "/../models/ReviewModel.php";

class ArticlesController {

    public function render() {

        if (session_status() === PHP_SESSION_NONE) session_start();

        $user = $_SESSION["user"] ?? null;

        $model = new PostModel();

        // pouze SCHVÁLENÉ články pro každého
        $posts = $model->getApprovedPosts();


        $reviewM = new ReviewModel();
        foreach ($posts as &$p) {
            $p["reviews"] = $reviewM->getApprovedReviews($p["id_post"]);
        }


        (new MyApplication())->renderTwig("articles.twig", [
            "currentPage" => "articles",
            "posts" => $posts,
            "user" => $user,
        ]);
    }
}
