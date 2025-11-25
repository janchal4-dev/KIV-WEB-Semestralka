<?php
require_once __DIR__ . "/../config/config.php";
require_once __DIR__ . "/../models/PostModel.php";
require_once __DIR__ . "/../../vendor/autoload.php";

class UploadController {

    public function render() {

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION["user"])) {
            header("Location: index.php?page=home");
            exit;
        }

        $user = $_SESSION["user"];

        // Jen AUTOŘI
        if ($user["roles_id"] != 4) {
            $app = new MyApplication();
            $app->renderTwig("upload.twig", [
                "currentPage" => "upload",
                "error" => $error ?? null
            ]);

            return;
        }

        // Zpracování uploadu
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $this->handleUpload($user["id_user"]);
            return;
        }

        // GET = formulář
        $app = new MyApplication();
        $app->renderTwig("upload.twig", [
            "currentPage" => "upload"
        ]);
    }

    private function handleUpload(int $authorId) {

        $name = trim($_POST["name"] ?? "");
        $abstract = $_POST["abstract"] ?? "";


        // ošetření XSS
        $config = HTMLPurifier_Config::createDefault();
        $purifier = new HTMLPurifier($config);
        $abstract = $purifier->purify($abstract);



        if ($name === "") {
            return $this->renderError("Musíte zadat název článku.");
        }

        if (!isset($_FILES["pdfFile"]) || $_FILES["pdfFile"]["error"] !== UPLOAD_ERR_OK) {
            return $this->renderError("Nebyl nahrán žádný PDF soubor.");
        }

        $tmp = $_FILES["pdfFile"]["tmp_name"];
        $original = $_FILES["pdfFile"]["name"];

        if (mime_content_type($tmp) !== "application/pdf") {
            return $this->renderError("Povolen je pouze PDF soubor.");
        }

        // unikátní název
        $ext = strtolower(pathinfo($original, PATHINFO_EXTENSION));
        $unique = uniqid("pdf_", true) . "." . $ext;

        $uploadDir = __DIR__ . "/../../uploads/";

        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        $dest = $uploadDir . $unique;

        if (!move_uploaded_file($tmp, $dest)) {
            return $this->renderError("Chyba při ukládání PDF.");
        }

        // Uložení do DB
        $model = new PostModel();
        $ok = $model->createPost($name, $unique, $authorId, $abstract);


        if (!$ok) {
            return $this->renderError("Soubor se uložil, ale nezapsal se do databáze.");
        }

        // hotovo
        header("Location: index.php?page=articles");
        exit;
    }

    private function renderError(string $msg) {
        $app = new MyApplication();
        $app->renderTwig("upload.twig", ["error" => $msg]);
    }
}
