<?php

require_once MODEL_PATH . "/PostModel.php";
require_once MODEL_PATH . "/ReviewModel.php";

class ReviewController {
    public function render() {

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // jen pro recezenty
        if (empty($_SESSION["user"]) || $_SESSION["user"]["roles_id"] != 3) {
            header("Location: index.php?page=home");
            exit;
        }

        // ❗ bezpečné získání ID
        $postId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if (!$postId) {
            die("❌ Chybí nebo je neplatné ID článku k recenzi.");
        }

        $reviewModel = new ReviewModel();
        $existing = $reviewModel->getReviewByUserAndPost($_SESSION["user"]["id_user"], $postId);

        if ($existing && $existing["published"] == 2) {
            die("❌ Tato recenze už byla schválena a nelze ji upravit.");
        }

        if ($existing && $existing["published"] == 3) {
            die("❌ Tato recenze byla zamítnuta a nelze ji upravit.");
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
