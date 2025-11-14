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
}
