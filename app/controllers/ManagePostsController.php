<?php
require_once MODEL_PATH . "/PostModel.php";
require_once MODEL_PATH . "/UserModel.php";

class ManagePostsController {
    public function render() {

        if (session_status() === PHP_SESSION_NONE) session_start();

        if ($_SESSION["user"]["roles_id"] > 2) {
            header("Location: index.php?page=home");
            exit;
        }

        $postModel = new PostModel();
        $userModel = new UserModel();

        $posts = $postModel->getAllPosts();
        $reviewers = $userModel->getReviewers(); // users s role_id = 3

        $app = new MyApplication();
        $app->renderTwig("managePosts.twig", [
            "currentPage" => "managePosts",
            "posts" => $posts,
            "reviewers" => $reviewers
        ]);
    }
}
