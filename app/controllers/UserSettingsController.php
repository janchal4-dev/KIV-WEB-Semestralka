<?php

class UserSettingsController {

    public function render()
    {
        if (!isset($_SESSION["user"])) {
            header("Location: index.php?page=home");
            exit;
        }

        $app = new MyApplication();
        $app->renderTwig("userSettings.twig", [
            "currentPage" => "userSettings",
        ]);
    }
}
