<?php
require_once MODEL_PATH . "/Database.php";

class UserModel {

    private PDO $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }
    // registrace
    public function register($username, $name, $email, $password): bool {
        //️ ochrana proti XSS
        $username = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');
        $name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);


        // nejdřív ověření že username nebo email existuje
        $check = $this->db->prepare("SELECT id_user FROM user WHERE username = ? OR email = ?");
        $check->execute([$username, $email]);

        if ($check->fetch()) {
            return false; // už existuje → neprovádět insert
        }


        $sql = "INSERT INTO user (username, name, email, password, roles_id, blocked)
            VALUES (?, ?, ?, ?, 4, 0)";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            $username,
            $name,
            $email,
            password_hash($password, PASSWORD_BCRYPT, ['cost' => 12])
        ]);
    }

    // přihlášení
    public function login($username, $password) {
        //️ ochrana proti XSS
        $username = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');
        $password = htmlspecialchars($password, ENT_QUOTES, 'UTF-8');

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
        if (!password_verify($password, $user["password"])) {
            return ["error" => "wrong_password"];
        }

        // testování
//        if ($password !== $user["password"]) {
//            return ["error" => "wrong_password"];
//        }

        return $user;
    }




    // vrátí věšechny uživatele a názvy jejich role
    public function getAllUsers() {
        $stmt = $this->db->query("
        SELECT u.id_user, u.username, u.name, u.email, u.blocked, r.role_name, u.roles_id
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


    public function getReviewers(): array {
        $sql = "SELECT id_user, username, name, email 
            FROM user 
            WHERE roles_id = 3 AND blocked = 0
            ORDER BY name";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function deleteUser(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM user WHERE id_user = ?");
        return $stmt->execute([$id]);
    }






}
