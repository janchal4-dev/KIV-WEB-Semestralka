<?php
require_once MODEL_PATH . "/UserModel.php";

class LoginController {

    public function render() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $model = new UserModel();
            $username = trim($_POST["username"]);
            $password = trim($_POST["password"]);

            $user = $model->login($username, $password);

            if ($user) {
                $_SESSION["user"] = $user;
                header("Location: index.php?page=home");
                exit;
            }

            // 🔴 když login selže
            $error = "Neplatné přihlašovací údaje nebo účet je zablokován.";
        }

        $app = new MyApplication();
        $app->renderTwig("home.twig", [
            "currentPage" => "login",
            "error" => $error ?? null
        ]);
    }
}
