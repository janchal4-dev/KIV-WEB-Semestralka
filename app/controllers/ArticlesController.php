<?php
class ArticlesController {

    public function render() {

        require "../app/models/ArticleModel.php";
        $articleModel = new ArticleModel();
        $articles = $articleModel->getAllArticles();

        include "../app/views/articlesView.php";
    }
}

?>