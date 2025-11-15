<?php

class PostModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = getDB();
    }


    // vrátí článek i s recenzema
    public function getPostsWithReviews(?int $filterUserId = null, ?int $role = null): array {

        // ADMIN (1) / SUPERADMIN (2) → vidí všechno
        if ($role === 1 || $role === 2) {
            $sql = "SELECT p.*, u.name AS author_name
                FROM post p
                JOIN user u ON p.author_id = u.id_user
                ORDER BY p.date_uploaded DESC";
            return $this->db->query($sql)->fetchAll();
        }

        // RECENZENT (3) → vidí jen přidělené články, bez ohledu na status
        if ($role === 3) {
            $sql = "SELECT p.*, u.name AS author_name
                FROM post p
                JOIN post_reviewer r ON p.id_post = r.post_id
                JOIN user u ON p.author_id = u.id_user
                WHERE r.reviewer_id = ?
                ORDER BY p.date_uploaded DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$filterUserId]);
            return $stmt->fetchAll();
        }

        // AUTOR (4) → vidí jen své články (bez ohledu na status)
        if ($role === 4) {
            $sql = "SELECT p.*, u.name AS author_name
                FROM post p
                JOIN user u ON p.author_id = u.id_user
                WHERE p.author_id = ?
                ORDER BY p.date_uploaded DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$filterUserId]);
            return $stmt->fetchAll();
        }

        // VEŘEJNÉ ZOBRAZENÍ (role 5? nebo neregistrovaný)
        // → ukázat jen SCHVÁLENÉ články
        $sql = "SELECT p.*, u.name AS author_name
            FROM post p
            JOIN user u ON p.author_id = u.id_user
            WHERE p.status_id = 2
            ORDER BY p.date_uploaded DESC";
        return $this->db->query($sql)->fetchAll();
    }


    public function getReviewsForPost(int $postId): array
    {
        $sql = "SELECT r.*, u.name AS reviewer_name
            FROM review r
            JOIN user u ON r.user_id = u.id_user
            WHERE r.post_id = ?
            ORDER BY r.date_created DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$postId]);
        return $stmt->fetchAll();
    }


    public function getAllPostsWithStatus(): array
    {
        $sql = "SELECT p.*, s.status_name, u.name AS author_name
            FROM post p
            JOIN status s ON s.id_status = p.status_id
            JOIN user u ON u.id_user = p.author_id
            ORDER BY p.date_uploaded DESC";
        return $this->db->query($sql)->fetchAll();
    }

    public function getPostDetails(int $id): ?array
    {
        $sql = "SELECT p.*, s.status_name, u.name AS author_name
            FROM post p
            JOIN status s ON s.id_status = p.status_id
            JOIN user u ON u.id_user = p.author_id
            WHERE p.id_post = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $post = $stmt->fetch();

        if (!$post) return null;

        // přidáme seznam recenzentů
        $post["reviewers"] = $this->getAssignedReviewers($id);
        return $post;
    }

//    public function updateStatus(int $postId, int $statusId): bool {
//        $sql = "UPDATE post SET status_id = ?, date_changed = NOW() WHERE id_post = ?";
//        $stmt = $this->db->prepare($sql);
//        return $stmt->execute([$statusId, $postId]);
//    }

    public function assignReviewer(int $postId, int $uid): bool
    {
        // kontrola duplicity
        $check = $this->db->prepare("SELECT 1 FROM post_reviewer WHERE post_id = ? AND reviewer_id = ?");
        $check->execute([$postId, $uid]);
        if ($check->fetch()) return true;

        $sql = "INSERT INTO post_reviewer (post_id, reviewer_id) VALUES (?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$postId, $uid]);
    }

    public function removeReviewer(int $postId, int $uid): bool
    {
        $sql = "DELETE FROM post_reviewer WHERE post_id = ? AND reviewer_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$postId, $uid]);
    }

    public function getAssignedReviewers(int $postId): array
    {
        $sql = "SELECT u.id_user, u.name, u.email 
            FROM post_reviewer pr
            JOIN user u ON u.id_user = pr.reviewer_id
            WHERE pr.post_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$postId]);
        return $stmt->fetchAll();
    }


    public function createPost(string $name, string $uniqueFileName, int $authorId): bool
    {
        $sql = "INSERT INTO post (name, file_path, author_id, status_id, date_uploaded)
                VALUES (?, ?, ?, 1, NOW())";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$name, $uniqueFileName, $authorId]);
    }

    public function getAllPosts(): array {
        $sql = "
        SELECT 
            p.*, 
            u.name AS author_name
        FROM post p
        JOIN user u ON p.author_id = u.id_user
        ORDER BY p.date_uploaded DESC
    ";

        return $this->db->query($sql)->fetchAll();
    }


    public function getPostById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM post WHERE id_post = ?");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function getAllPostsFull(): array
    {

        $sql = "
        SELECT p.*, u.name AS author_name, s.status_name
        FROM post p
        JOIN user u ON u.id_user = p.author_id
        JOIN status s ON s.id_status = p.status_id
        ORDER BY p.date_uploaded DESC
    ";

        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function updateStatus(int $postId, int $statusId): bool
    {
        $sql = "UPDATE post SET status_id = ?, date_changed = NOW() WHERE id_post = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$statusId, $postId]);
    }



    public function getApprovedPosts(): array {
        $sql = "SELECT p.*, u.name AS author_name
            FROM post p
            JOIN user u ON p.author_id = u.id_user
            WHERE p.status_id = 2
            ORDER BY p.date_uploaded DESC";
        return $this->db->query($sql)->fetchAll();
    }


    public function getPostsForReviewer(int $reviewerId): array
    {
        $sql = "
        SELECT 
            p.id_post,
            p.name AS title,              -- alias pro název článku
            p.date_uploaded,
            u.name AS author_name         -- jméno autora
        FROM post_reviewer pr
        JOIN post p ON pr.post_id = p.id_post
        JOIN user u ON p.author_id = u.id_user
        WHERE pr.reviewer_id = ?
        ORDER BY p.date_uploaded DESC
    ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$reviewerId]);
        return $stmt->fetchAll();
    }


}
