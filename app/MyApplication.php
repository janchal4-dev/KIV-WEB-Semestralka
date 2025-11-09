<?php
class MyApplication {

    private array $allowed_pages = [
        'home', 'articles', 'program', 'login', 'registration', 'userSettings', 'upload'
    ];

    public function run() {

        $page = $_GET['page'] ?? 'home';

        if (!in_array($page, $this->allowed_pages)) {
            $page = 'home';
        }

        $controllerName = ucfirst($page) . "Controller";
        require "../app/controllers/$controllerName.php";

        $controller = new $controllerName();
        $controller->render();
    }
}




?>