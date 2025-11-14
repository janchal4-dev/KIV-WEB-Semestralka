<?php

class PostModel {
    private PDO $db;

    public function __construct() {
        $this->db = getDB();
    }

    // načte všechny články (např. pro seznam)
    public function getAllPosts(): array {
        $sql = "SELECT * FROM post ORDER BY date_uploaded DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    // načte konkrétní článek podle ID (např. pro review)
    public function getPostById(int $id): ?array {
        $sql = "SELECT * FROM post WHERE id_post = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $post = $stmt->fetch();
        return $post ?: null;
    }

    // vytvoří nový článek – používají autoři
    public function createPost(string $name, string $filePath, int $authorId): bool
    {
        $sql = "INSERT INTO post (name, file_path, author_id, status_id)
                VALUES (?, ?, ?, 1)"; // 1 = čeká na schválení

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$name, $filePath, $authorId]);
    }

    // vrátí článek i s recenzema
    public function getPostWithReviews(): array {
        $sql = "SELECT p.*, u.name AS author_name, s.status_name
            FROM post p
            JOIN user u ON p.author_id = u.id_user
            JOIN post_status s ON p.status_id = s.id_status
            ORDER BY p.date_uploaded DESC";

        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    // vrátí recenze
    public function getReviewsForPost(int $postId): array {
        $sql = "SELECT r.*, u.name AS reviewer_name
            FROM review r
            JOIN user u ON r.user_id = u.id_user
            WHERE r.post_id = ? AND r.published = 1
            ORDER BY r.date_created DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$postId]);
        return $stmt->fetchAll();
    }


    public function updateStatus(int $postId, int $newStatus): bool {
        $sql = "UPDATE post SET status_id = ?, date_changed = NOW()
            WHERE id_post = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$newStatus, $postId]);
    }


}
