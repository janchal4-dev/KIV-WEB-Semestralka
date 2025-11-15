<?php

class MyApplication {

    private array $allowed_pages = [
        "home", "articles", "program", "upload", "userSettings", "registration", "login",
        "logout","loginError","review", "reviewList","managePosts","manageReviews"
    ];


    public function run() {

        $page = $_GET["page"] ?? "home";

        if (!in_array($page, $this->allowed_pages)) {
            $page = "home";
        }

        $controllerName = ucfirst($page) . "Controller";
        $controllerFile = CONTROLLER_PATH . "/" . $controllerName . ".php";

        if (!file_exists($controllerFile)) {
            die("❌ Controller nebyl nalezen: <br>$controllerFile");
        }

        require_once $controllerFile;

        if (!class_exists($controllerName)) {
            die("❌ Třída controlleru '$controllerName' neexistuje.");
        }

        $controller = new $controllerName();
        $controller->render();
    }

//    public function renderTwig(string $template, array $data = [])
//    {
//        $data["app_base"] = BASE_URL;
//        $data["session"] = $_SESSION ?? [];
//
//        $data["user"] = $_SESSION["user"] ?? null;
//
//        require_once __DIR__ . "/../vendor/autoload.php";
//
//        $loader = new \Twig\Loader\FilesystemLoader(TWIG_TEMPLATE_PATH);
//        $twig = new \Twig\Environment($loader, [
//            "debug" => true
//        ]);
//
//        echo $twig->render($template, $data);
//    }
    public function renderTwig(string $template, array $data = [])
    {
        require_once __DIR__ . "/../vendor/autoload.php";

        $loader = new \Twig\Loader\FilesystemLoader(TWIG_TEMPLATE_PATH);
        $twig = new \Twig\Environment($loader, [
            "debug" => true
        ]);

        // ✅ bezpečné spuštění session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // ✅ předání uživatele a základní cesty do Twigu
        $data["app_base"] = BASE_URL;
        $data["user"] = $_SESSION["user"] ?? null;




        echo $twig->render($template, $data);
    }



}
