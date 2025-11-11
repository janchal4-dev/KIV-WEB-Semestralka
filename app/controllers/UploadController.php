<?php

class UploadController {

    public function render()
    {
        if (!isset($_SESSION["user"])) {
            header("Location: index.php?page=home");
            exit;
        }

        $app = new MyApplication();
        $app->renderTwig("upload.twig", [
            "currentPage" => "upload",
            "user" => $_SESSION["user"] ?? null
        ]);
    }
}