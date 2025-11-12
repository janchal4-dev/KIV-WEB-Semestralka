<?php

class LoginErrorController {
    public function render() {
        $type = $_GET["type"] ?? "unknown";

        $messages = [
            "blocked" => [
                "title" => "Účet zablokován",
                "text" => "Váš účet byl zablokován administrátorem. Kontaktujte podporu.",
                "class" => "danger"
            ],
            "not_found" => [
                "title" => "Uživatel neexistuje",
                "text" => 'Zadané uživatelské jméno nebylo nalezeno. <a href="?page=registration">Zaregistrujte se</a>.',
                "class" => "warning"
            ],
            "wrong_password" => [
                "title" => "Špatné heslo",
                "text" => "Zadané heslo není správné. Zkuste to znovu.",
                "class" => "danger"
            ],
            "unknown" => [
                "title" => "Neznámá chyba",
                "text" => "Nastala neočekávaná chyba při přihlašování.",
                "class" => "secondary"
            ]
        ];

        $error = $messages[$type] ?? $messages["unknown"];

        $app = new MyApplication();
        $app->renderTwig("loginError.twig", [
            "currentPage" => "loginError",
            "error" => $error
        ]);
    }
}
