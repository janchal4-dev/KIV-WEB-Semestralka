<?php

require_once MODEL_PATH . "/PostModel.php";

class ReviewController {
    public function render() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION["user"]) || $_SESSION["user"]["roles_id"] != 3) {
            header("Location: index.php?page=home");
            exit;
        }

        $postId = $_GET["id"] ?? null;
        if (!$postId) {
            die("❌ Chybí ID článku k recenzi.");
        }

        $model = new PostModel();
        $post = $model->getPostById($postId);

        (new MyApplication())->renderTwig("review.twig", [
            "currentPage" => "review",
            "user" => $_SESSION["user"],
            "post" => $post
        ]);
    }
}
