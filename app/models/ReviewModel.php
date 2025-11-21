<?php

class ReviewModel {
    private PDO $db;

    public function __construct() {
        $this->db = getDB();
    }

    // vrátí recenze
    public function getAllReviews(): array {
        $sql = "SELECT r.*,
       u.name AS reviewer_name,
       p.name AS post_name,
       p.file_path,
       a.name AS author_name
FROM review r
JOIN user u ON r.user_id = u.id_user
JOIN post p ON r.post_id = p.id_post
JOIN user a ON p.author_id = a.id_user
ORDER BY r.date_created DESC
";
        return $this->db->query($sql)->fetchAll();
    }
    // změní stav recenze když do toho admin hrábne
    public function updateReviewStatus(int $reviewId, int $status): bool {
        $sql = "
            UPDATE review
            SET published = ?
            WHERE id_review = ?
        ";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$status, $reviewId]);
    }

    // vrátí jen schválené recenze přiřazené k článkům
    public function getApprovedReviews(int $postId): array {
        $sql = "
        SELECT r.*, u.name AS reviewer_name
        FROM review r
        JOIN user u ON r.user_id = u.id_user
        WHERE r.post_id = ?
          AND r.published = 2
        ORDER BY r.date_created DESC
    ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$postId]);
        return $stmt->fetchAll();
    }

    // vytvoření nebo úprava recenze
    public function createOrUpdateReview($postId, $userId, $q, $l, $o, $comment): bool {

        // 1) Zjisti, jestli recenze už existuje
        $sql = "SELECT id_review FROM review WHERE post_id = ? AND user_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$postId, $userId]);
        $existing = $stmt->fetch();

        // 2️) UPDATE existující recenze
        if ($existing) {
            $sql = "UPDATE review 
                SET rev_quality = ?, rev_language = ?, rev_originality = ?, 
                    comment = ?, date_created = NOW(), published = 0
                WHERE id_review = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$q, $l, $o, $comment, $existing["id_review"]]);
        }

        // 3)  INSERT nové recenze
        $sql = "INSERT INTO review 
            (post_id, user_id, rev_quality, rev_language, rev_originality, comment, date_created, published)
            VALUES (?, ?, ?, ?, ?, ?, NOW(), 0)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$postId, $userId, $q, $l, $o, $comment]);
    }



}
