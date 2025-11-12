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

        // kvůli ověřování funkčnosti databáze - uživatelé nahráni sql skriptem
//        if ($user && password_verify($password, $user["password"])) {
//            return $user;
//        }
        if ($user && $password === $user["password"]) {
            return $user;
        }


        return false;
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

}
