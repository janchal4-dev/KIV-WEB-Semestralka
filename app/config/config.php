<?php

// cesty
define("APP_PATH", dirname(__DIR__)); // = D:/xampp/htdocs/.../app
define("TWIG_TEMPLATE_PATH", APP_PATH . "/views/twig");
define("CONTROLLER_PATH", APP_PATH . "/controllers");
define("MODEL_PATH", APP_PATH . "/models");
define("BASE_URL", "/KIV-WEB/kiv_web_semestralka");
define("UPLOAD_URL", BASE_URL . "/uploads/");


// DB konfigurace
define("DB_HOST", "localhost");
define("DB_NAME", "konference");
define("DB_USER", "root");
define("DB_PASS", "");

// Připojení k databázi jako singleton (aby bylo 1 spojení)
function getDB(): PDO {
    static $pdo = null;

    if ($pdo === null) {
        $pdo = new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
            DB_USER,
            DB_PASS,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]
        );
    }

    return $pdo;
}

// cesty pro databázi
//define("MODEL_PATH", APP_PATH . "/models");
define("CONFIG_PATH", APP_PATH . "/config");
//define("TWIG_TEMPLATE_PATH", APP_PATH . "/views/twig");
