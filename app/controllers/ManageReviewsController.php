<?php

require_once MODEL_PATH . "/ReviewModel.php";

class ManageReviewsController {

    public function render() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if ($_SESSION["user"]["roles_id"] > 2) {
            header("Location: index.php?page=home");
            exit;
        }

        $reviewModel = new ReviewModel();
        $reviews = $reviewModel->getAllReviews();

        $app = new MyApplication();
        $app->renderTwig("manageReviews.twig", [
            "currentPage" => "manageReviews",
            "reviews" => $reviews
        ]);
    }
}
