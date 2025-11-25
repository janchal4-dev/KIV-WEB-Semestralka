<?php
require_once MODEL_PATH . "/PostModel.php";
require_once __DIR__ . "/../../vendor/autoload.php";

class EditPostController
{
    public function render()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION["user"]) || $_SESSION["user"]["roles_id"] != 4) {
            header("Location: index.php?page=home");
            exit;
        }

        $user = $_SESSION["user"];
        $postId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if (!$postId) {
            die("❌ Chybí nebo je neplatné ID článku.");
        }

        $postModel = new PostModel();
        $post = $postModel->getPostById($postId);

        if (!$post) {
            die("❌ Článek neexistuje.");
        }

        if ($post["author_id"] != $user["id_user"]) {
            die("❌ Tento článek není váš.");
        }

        if ($post["status_id"] == 2) {
            die("❌ Publikovaný článek nelze upravovat.");
        }

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $this->saveChanges($postId, $user["id_user"]);
            return;
        }

        $app = new MyApplication();
        $app->renderTwig("editPost.twig", [
            "currentPage" => "editPost",
            "post" => $post,
            "user" => $user
        ]);
    }


    private function saveChanges(int $postId, int $authorId)
    {
        // Název článku – neobsahuje HTML takže stačí htmlspecialchars
        $name = trim($_POST["name"] ?? "");
        $name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');

        // Abstrakt přes CKEditor
        $abstractRaw = $_POST["abstract"] ?? "";

        $config = HTMLPurifier_Config::createDefault();
        $purifier = new HTMLPurifier($config);
        $abstract = $purifier->purify($abstractRaw);

        if ($name === "") {
            return $this->renderError("Musíte zadat název článku.", $postId);
        }

        $newPdfName = null;

        if (!empty($_FILES["pdfFile"]["name"])) {

            if ($_FILES["pdfFile"]["error"] !== UPLOAD_ERR_OK) {
                return $this->renderError("Chyba při nahrávání PDF.", $postId);
            }

            $tmp = $_FILES["pdfFile"]["tmp_name"];
            if (mime_content_type($tmp) !== "application/pdf") {
                return $this->renderError("Soubor musí být PDF.", $postId);
            }

            // aby byl zas unikátní
            $ext = pathinfo($_FILES["pdfFile"]["name"], PATHINFO_EXTENSION);
            $newPdfName = uniqid("pdf_", true) . "." . $ext;

            $uploadDir = __DIR__ . "/../../uploads/";
            move_uploaded_file($tmp, $uploadDir . $newPdfName);
        }

        $model = new PostModel();
        $ok = $model->updatePost($postId, $name, $abstract, $newPdfName);

        if (!$ok) {
            return $this->renderError("Nepodařilo se uložit změny.", $postId);
        }

        header("Location: index.php?page=articles");
        exit;
    }


    private function renderError(string $msg, int $postId)
    {
        $postModel = new PostModel();
        $post = $postModel->getPostById($postId);

        $app = new MyApplication();
        $app->renderTwig("editPost.twig", [
            "currentPage" => "editPost",
            "error" => $msg,
            "post" => $post
        ]);
    }
}
