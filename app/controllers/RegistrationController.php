<?php
require_once MODEL_PATH . "/UserModel.php";

class RegistrationController
{
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