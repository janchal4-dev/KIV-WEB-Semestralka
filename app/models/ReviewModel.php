<?php

class ReviewModel {
    private PDO $db;

    public function __construct() {
        $this->db = getDB();
    }

    // ✔️ Tvoje metoda pro vytvoření recenze (neměníme)
    public function createReview($postId, $userId, $q, $l, $o, $comment): bool {
        $sql = "INSERT INTO review (post_id, user_id, rev_quality, rev_language, rev_originality, comment, date_created, published)
                VALUES (?, ?, ?, ?, ?, ?, NOW(), 0)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$postId, $userId, $q, $l, $o, $comment]);
    }

//     ✔️ 1) Načtení všech recenzí (pro admina / superadmina)
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

    // ✔️ 2) Načtení recenzí jednoho článku (pokud budeš potřebovat)
    public function getReviewsForPost(int $postId): array {
        $sql = "
            SELECT r.*, u.name AS reviewer_name
            FROM review r
            JOIN user u ON r.user_id = u.id_user
            WHERE r.post_id = ?
            ORDER BY r.date_created DESC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$postId]);
        return $stmt->fetchAll();
    }

    // ✔️ 3) Aktualizace statusu recenze (0 čeká, 1 schválena, 2 zamítnuta)
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


}
