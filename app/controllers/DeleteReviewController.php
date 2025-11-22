<?php

require_once MODEL_PATH . "/ReviewModel.php";

class DeleteReviewController
{
    public function render()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION["user"]) || $_SESSION["user"]["roles_id"] != 3) {
            header("Location: index.php?page=home");
            exit;
        }

        $user = $_SESSION["user"];
        $reviewId = $_GET["id"] ?? null;

        if (!$reviewId) {
            die("❌ Chybí ID recenze.");
        }

        $model = new ReviewModel();
        $review = $model->getReviewById($reviewId);

        if (!$review) {
            die("❌ Recenze neexistuje.");
        }

        // povoleno mazat jen své vlastní
        if ($review["user_id"] != $user["id_user"]) {
            die("❌ Tuto recenzi nemůžete smazat.");
        }

        // publikované recenze mazat nesmí
        if ($review["published"] == 2 || $review["published"] == 3) {
            die("❌ Publikovanou nebo zamítnutou recenzi nelze mazat.");
        }

        // proveď mazání
        $model->deleteReview($reviewId);

        header("Location: index.php?page=reviewList");
        exit;
    }
}
