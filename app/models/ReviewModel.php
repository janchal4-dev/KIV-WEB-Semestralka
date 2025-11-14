<?php

class ReviewModel {
    private PDO $db;

    public function __construct() {
        $this->db = getDB();
    }

    public function createReview($postId, $userId, $q, $l, $o, $comment): bool {
        $sql = "INSERT INTO review (post_id, user_id, rev_quality, rev_language, rev_originality, comment, date_created, published)
                VALUES (?, ?, ?, ?, ?, ?, NOW(), 0)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$postId, $userId, $q, $l, $o, $comment]);
    }
}
