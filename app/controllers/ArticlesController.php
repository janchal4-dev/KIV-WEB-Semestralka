<?php
require_once MODEL_PATH . "/ArticleModel.php";

class ArticlesController {

    public function render() {
        $articleModel = new ArticleModel();
        $publishedArticles = $articleModel->getPublishedArticles();

        require HEADER_FILE;
        require VIEW_PATH . "/articlesView.php";
        require FOOTER_FILE;
    }
}
