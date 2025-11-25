<?php

require_once MODEL_PATH . "/ReviewModel.php";

class ManageReviewsController {

    public function render() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        // aby se tam dostal jen super/admin
        if ($_SESSION["user"]["roles_id"] != 2 && $_SESSION["user"]["roles_id"] != 1) {
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
