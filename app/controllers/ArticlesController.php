<?php
require_once MODEL_PATH . "/ArticleModel.php";

class ArticlesController {

    public function render()
    {
        $app = new MyApplication();
        $app->renderTwig("articles.twig", [
            "currentPage" => "articles",
            "uploadPath" => UPLOAD_URL,
            "user" => $_SESSION["user"] ?? null
        ]);
    }
}
