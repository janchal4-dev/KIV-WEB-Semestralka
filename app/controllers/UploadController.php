<?php

require_once __DIR__ . "/../models/PostModel.php";

class UploadController {
    public function render() {

        // session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // přihlášení required
        if (empty($_SESSION["user"])) {
            header("Location: index.php?page=login");
            exit;
        }

        $user = $_SESSION["user"];

        // přístup jen pro autory
        if ($user["roles_id"] != 4) {
            die("Nemáte oprávnění nahrávat články.");
        }

        // při odeslání formuláře
        if ($_SERVER["REQUEST_METHOD"] === "POST") {

            if (!isset($_FILES["pdfFile"]) || $_FILES["pdfFile"]["error"] !== UPLOAD_ERR_OK) {
                $error = "Soubor nebyl nahrán.";
            } else {

                $fileTmp = $_FILES["pdfFile"]["tmp_name"];
                $origName = $_FILES["pdfFile"]["name"];
                $ext = strtolower(pathinfo($origName, PATHINFO_EXTENSION));

                if ($ext !== "pdf") {
                    $error = "Povolený je pouze PDF soubor.";
                } else {

                    // unikátní název – bonus 2 body :)
                    $newName = uniqid("pdf_", true) . ".pdf";
                    $target = __DIR__ . "/../../uploads/" . $newName;

                    if (!is_dir(__DIR__ . "/../../uploads/")) {
                        mkdir(__DIR__ . "/../../uploads/", 0777, true);
                    }

                    if (move_uploaded_file($fileTmp, $target)) {

                        $postModel = new PostModel();
                        $ok = $postModel->createPost(
                            $_POST["name"] ?? "Nepojmenovaný článek",
                            "uploads/" . $newName,
                            $user["id_user"]
                        );

                        if ($ok) {
                            header("Location: index.php?page=articles&uploadSuccess=1");
                            exit;
                        } else {
                            $error = "Upload proběhl, ale nepodařilo se uložit do databáze.";
                        }

                    } else {
                        $error = "Chyba při přesunu souboru.";
                    }
                }
            }
        }

        // vykreslí šablonu
        $app = new MyApplication();
        $app->renderTwig("upload.twig", [
            "currentPage" => "upload",
            "user" => $user,
            "error" => $error ?? null
        ]);
    }
}
