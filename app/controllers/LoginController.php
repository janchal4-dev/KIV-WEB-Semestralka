<?php
require_once MODEL_PATH . "/UserModel.php";

class LoginController {
    public function render() {

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $model = new UserModel();

            $username = trim($_POST["username"]);
            $password = trim($_POST["password"]);

            $user = $model->login($username, $password);

            if (isset($user["error"])) {
                $errorType = $user["error"];
                header("Location: index.php?page=loginError&type=" . urlencode($errorType));
                exit;
            }

            // když se povede
            $_SESSION["user"] = $user;
            header("Location: index.php?page=home");
            exit;
        }

        (new MyApplication())->renderTwig("home.twig", [
            "currentPage" => "login"
        ]);
    }
}
