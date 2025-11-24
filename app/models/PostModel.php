<?php

class PostModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = getDB();
    }

    // komu byl článek přiřazen k recenzi
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

    // vytvoření článku
    public function createPost(string $name, string $uniqueFileName, int $authorId, string $abstract): bool
    {
        $sql = "INSERT INTO post (name, file_path, author_id, status_id, date_uploaded, abstract)
            VALUES (?, ?, ?, 1, NOW(), ?)";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$name, $uniqueFileName, $authorId, $abstract]);
    }


    // aktualizace článku
    public function updatePost($postId, $name, $abstract, $newPdfName = null)
    {
        // znovunahrání pdf není povinné - když je tak se přepíše
        if ($newPdfName) {
            $sql = "UPDATE post 
                SET name = ?, abstract = ?, file_path = ? 
                WHERE id_post = ?";
            $params = [$name, $abstract, $newPdfName, $postId];

        } else {
            $sql = "UPDATE post 
                SET name = ?, abstract = ?
                WHERE id_post = ?";
            $params = [$name, $abstract, $postId];
        }

        $stmt = getDB()->prepare($sql);
        return $stmt->execute($params);
    }




    // vrací všechny bez ohlkedu na stav
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

    // vrací články podle id (pro seřazení popořadě)
    public function getPostById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM post WHERE id_post = ?");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    // čeká, přijat, zamítnut
    public function updateStatus(int $postId, int $statusId): bool
    {
        $sql = "UPDATE post SET status_id = ?, date_changed = NOW() WHERE id_post = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$statusId, $postId]);
    }


    // jen schválené
    public function getApprovedPosts(): array {
        $sql = "SELECT p.*, u.name AS author_name
            FROM post p
            JOIN user u ON p.author_id = u.id_user
            WHERE p.status_id = 2
            ORDER BY p.date_uploaded DESC";
        return $this->db->query($sql)->fetchAll();
    }

    // články pro recenzenta
    public function getPostsForReviewer(int $reviewerId): array
    {
        $sql = "
        SELECT 
            p.id_post,
            p.name AS title,              -- titulek článku
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

    // ať autor vidí své články
    public function getPostsByAuthor(int $authorId): array {
        $sql = "SELECT p.*, s.name AS status_name
            FROM post p
            JOIN status s ON s.id_status = p.status_id
            WHERE p.author_id = ?
            ORDER BY p.date_uploaded DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$authorId]);
        return $stmt->fetchAll();
    }

    public function deletePost(int $postId, int $authorId): bool {
        $sql = "DELETE FROM post WHERE id_post = ? AND author_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$postId, $authorId]);
    }





}
