<?php
require_once MODEL_PATH . "/PostModel.php";

class ReviewListController {
    public function render() {
        // Spustí session jen pokud ještě neběží
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION["user"]) || $_SESSION["user"]["roles_id"] != 3) {
            header("Location: index.php?page=login");
            exit;
        }

        $model = new PostModel();
        $posts = $model->getAllPosts(); // TODO: později můžeš filtrovat jen ty, které má daný recenzent hodnotit

        $app = new MyApplication();
        $app->renderTwig("reviewList.twig", [
            "currentPage" => "reviewList",
            "user" => $_SESSION["user"],
            "posts" => $posts
        ]);
    }
}
