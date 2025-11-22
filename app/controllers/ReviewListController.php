<?php
require_once MODEL_PATH . "/PostModel.php";
require_once MODEL_PATH . "/ReviewModel.php";


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
        $app = new MyApplication();

        $postModel = new PostModel();
        $reviewModel = new ReviewModel();

        $posts   = $postModel->getPostsForReviewer($user["id_user"]);
        $reviews = $reviewModel->getReviewsByReviewer($user["id_user"]);

        foreach ($posts as &$p) {
            $p["my_review"] = $reviewModel->getReviewByUserAndPost($user["id_user"], $p["id_post"]);
        }
        unset($p);

        $app->renderTwig("reviewList.twig", [
            "user"        => $user,
            "currentPage" => "reviewList",
            "posts"       => $posts,
            "reviews"     => $reviews,
        ]);

    }
}
