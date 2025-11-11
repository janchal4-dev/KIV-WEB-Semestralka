<?php
//
//class RegistrationController {
//
//    public function render()
//    {
//        $app = new MyApplication();
//        $app->renderTwig("registration.twig", [
//            "currentPage" => "registration",
//            "user" => $_SESSION["user"] ?? null
//        ]);
//    }
//}


require_once MODEL_PATH . "/UserModel.php";

class RegistrationController
{

//    public function render()
//    {
//
//        if ($_SERVER["REQUEST_METHOD"] === "POST") {
//
//            $model = new UserModel();
//
//            $username = trim($_POST["username"]);
//            $name = trim($_POST["name"]);
//            $email = trim($_POST["email"]);
//            $password = trim($_POST["password"]);
//
//            if ($model->register($username, $name, $email, $password)) {
//                header("Location: index.php?page=login&registered=1");
//                exit;
//            }
//
//            $error = "Uživatel se nepodařilo vytvořit.";
//        }
//
//        (new MyApplication())->renderTwig("registration.twig", [
//            "currentPage" => "registration",
//            "error" => $error ?? null
//        ]);
//    }
    public function render()
    {

        if ($_SERVER["REQUEST_METHOD"] === "POST") {

            $model = new UserModel();

            $success = $model->register(
                $_POST["username"],
                $_POST["name"],
                $_POST["email"],
                $_POST["password"]
            );

            if ($success) {
                header("Location: index.php?page=home&registered=1");
                exit;
            }

            // Pokud registrace selže → uživatel existuje nebo chyba DB
            $error = "❌ Uživatelské jméno nebo e-mail už existuje.";
        }

        // ✅ Create MyApplication instance
        $app = new MyApplication();

        // ✅ Render s daty (včetně chyby a currentPage)
        $app->renderTwig("registration.twig", [
            "currentPage" => "registration",
            "error" => $error ?? null
        ]);
    }
}