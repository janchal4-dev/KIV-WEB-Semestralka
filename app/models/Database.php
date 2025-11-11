<?php

class Database {

    private static ?PDO $connection = null;

    public static function getConnection(): PDO
    {
        if (self::$connection === null) {

            $config = require APP_PATH . "/config/db.php";

            try {
                self::$connection = new PDO(
                    "mysql:host={$config['host']};dbname={$config['database']};charset=utf8mb4",
                    $config['user'],
                    $config['password'],
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    ]
                );

            } catch (PDOException $e) {
                die("❌ Chyba připojení k DB: " . $e->getMessage());
            }
        }

        return self::$connection;
    }
}
