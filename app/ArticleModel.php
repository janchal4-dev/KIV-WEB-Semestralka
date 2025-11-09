<?php
class ArticleModel {

    public function getAllArticles() {
        global $db;
        return $db->query("SELECT * FROM articles")->fetch_all(MYSQLI_ASSOC);
    }
}

?>