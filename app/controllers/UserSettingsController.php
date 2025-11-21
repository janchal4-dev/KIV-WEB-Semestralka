<?php
require_once MODEL_PATH . "/UserModel.php";

class UserSettingsController {
    public function render() {

        // Spustí session jen pokud ještě neběží
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Kontrola přihlášení
        if (empty($_SESSION["user"])) {
            header("Location: index.php?page=login");
            exit;
        }
        // jen pro (super)adminy
        if ($_SESSION["user"]["roles_id"] > 2) {
        header("Location: index.php?page=home");
        exit;
    }

        $current = $_SESSION["user"];
        $model = new UserModel();
        $users = $model->getAllUsers();

        foreach ($users as &$u) {

            // Výchozí nastavení
            $u["can_edit_role"] = false;
            $u["can_block"] = false;
            $u["can_unblock"] = false;

            // SuperAdmin = role 1
            if ($current["roles_id"] == 1) {

                if ($u["roles_id"] != 1) {
                    $u["can_edit_role"] = true;
                    if ($u["blocked"]) $u["can_unblock"] = true;
                    else $u["can_block"] = true;
                }

            }

            // Admin = role 2
            if ($current["roles_id"] == 2) {

                // Admin může měnit role jen u uživatelů 3 a 4
                if ($u["roles_id"] > 2) {
                    $u["can_edit_role"] = true;
                    if ($u["blocked"]) $u["can_unblock"] = true;
                    else $u["can_block"] = true;
                }

            }
        }

        // Vykreslení šablony
        $app = new MyApplication();
        $app->renderTwig("userSettings.twig", [
            "currentPage" => "userSettings",
            "user" => $_SESSION["user"],
            "users" => $users
        ]);
    }




}
