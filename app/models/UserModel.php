<?php
require_once MODEL_PATH . "/Database.php";

class UserModel {

    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function register($username, $name, $email, $password): bool {

        // ✅ nejdřív ověřit zda username nebo email existuje
        $check = $this->db->prepare("SELECT id_user FROM user WHERE username = ? OR email = ?");
        $check->execute([$username, $email]);

        if ($check->fetch()) {
            return false; // už existuje → neprovádět insert
        }

        $sql = "INSERT INTO user (username, name, email, password, roles_id, blocked)
            VALUES (?, ?, ?, ?, 1, 0)";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            $username,
            $name,
            $email,
            password_hash($password, PASSWORD_BCRYPT, ['cost' => 12])
        ]);
    }

    public function login($username, $password) {
        $stmt = $this->db->prepare("SELECT * FROM user WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if (!$user) {
            return ["error" => "not_found"];
        }

        if ($user["blocked"]) {
            return ["error" => "blocked"];
        }

        // pro ten bcrypt
//    if (!password_verify($password, $user["password"])) {
//        return ["error" => "wrong_password"];
//    }

        // testování
        if ($password !== $user["password"]) {
            return ["error" => "wrong_password"];
        }

        return $user;
    }




    // vrátí věšechny uživatele a názvy jejich role
    public function getAllUsers() {
        $stmt = $this->db->query("
        SELECT u.id_user, u.username, u.name, u.email, u.blocked, r.role_name
        FROM user u
        JOIN roles r ON u.roles_id = r.id_role
        ORDER BY r.id_role ASC, u.username ASC
    ");
        return $stmt->fetchAll();
    }


    // kód pro api
    public function getUserById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM user WHERE id_user = ?");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function updateRole(int $id, int $newRole): bool {
        $stmt = $this->db->prepare("UPDATE user SET roles_id = ? WHERE id_user = ?");
        return $stmt->execute([$newRole, $id]);
    }



    public function blockUser(int $id): bool {
        $stmt = $this->db->prepare("UPDATE user SET blocked = 1 WHERE id_user = ?");
        return $stmt->execute([$id]);
    }

    public function unblockUser(int $id): bool {
        $stmt = $this->db->prepare("UPDATE user SET blocked = 0 WHERE id_user = ?");
        return $stmt->execute([$id]);
    }



}
