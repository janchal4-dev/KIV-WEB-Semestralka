<?php

class ArticleModel {

    public function getPublishedArticles() {
        $files = array_filter(glob("uploads/*.pdf"), 'is_file');
        rsort($files); // nejnovější první

        return $files;
    }
}
