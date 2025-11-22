<?php
require_once MODEL_PATH . "/UserModel.php";

class RegistrationController
{
    public function render()
    {
        $error = null;

        if ($_SERVER["REQUEST_METHOD"] === "POST") {

            // 1) kontrola že je vyplněný všechno
            $required = ["username", "name", "email", "password", "confirmPassword"];
            foreach ($required as $field) {
                if (empty($_POST[$field])) {
                    $error = "❌ Vyplňte všechna pole.";
                    return $this->showForm($error);
                }
            }

            // 2) Kontrola hesel
            if ($_POST["password"] !== $_POST["confirmPassword"]) {
                $error = "❌ Hesla se neshodují.";
                return $this->showForm($error);
            }

            // 3) Kontrola e-mailu
            if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
                $error = "❌ Neplatná e-mailová adresa.";
                return $this->showForm($error);
            }

            // 4) registrace
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

            $error = "❌ Uživatelské jméno nebo e-mail už existuje.";
        }

        $this->showForm($error);
    }


    private function showForm($error = null)
    {
        $app = new MyApplication();
        $app->renderTwig("registration.twig", [
            "currentPage" => "registration",
            "error" => $error
        ]);
    }
}
