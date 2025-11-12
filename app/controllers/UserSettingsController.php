<?php

class UserSettingsController {
    public function render() {

        // ✅ Spustí session jen pokud ještě neběží
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // ✅ Kontrola přihlášení
        if (empty($_SESSION["user"])) {
            header("Location: index.php?page=login");
            exit;
        }

        // ✅ Vykreslení šablony
        $app = new MyApplication();
        $app->renderTwig("userSettings.twig", [
            "currentPage" => "userSettings",
            "user" => $_SESSION["user"]
        ]);
    }
}
