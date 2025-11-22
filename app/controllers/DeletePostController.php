<?php
require_once MODEL_PATH . "/PostModel.php";
require_once MODEL_PATH . "/ReviewModel.php";


class DeletePostController {

    public function render() {

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION["user"])) {
            header("Location: index.php?page=login");
            exit;
        }

        $user = $_SESSION["user"];

        // Jen autor
        if ($user["roles_id"] != 4) {
            header("Location: index.php?page=home");
            exit;
        }

        $postId = $_GET["id"] ?? null;

        if (!$postId) {
            die("❌ Chybí ID článku.");
        }

        $postModel = new PostModel();
        $post = $postModel->getPostById($postId);

        if (!$post) {
            die("❌ Článek neexistuje.");
        }

        if ($post["author_id"] != $user["id_user"]) {
            die("❌ Tento článek není váš.");
        }

        if ($post["status_id"] == 2) {
            die("❌ Publikovaný článek nelze mazat.");
        }

        $reviewModel = new ReviewModel();
        $reviewModel->deleteReviewsForPost($postId);

        $postModel->deletePost($postId, $user["id_user"]);

        // smažeme PDF soubor
        $path = __DIR__ . "/../../uploads/" . $post["file_path"];
        if (file_exists($path)) {
            unlink($path);
        }

        // smažeme DB záznam
        $postModel->deletePost($postId, $user["id_user"]);

        header("Location: index.php?page=myPosts&deleted=1");
        exit;
    }
}
