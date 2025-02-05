<?php

namespace App\Core\Database;
use PDO;
use PDOException;

class DB {
    private static ?PDO $connection = null;

    public static function connect(): PDO {
        if (!self::$connection) {
            try {
                self::$connection = new PDO(
                    dsn: "mysql:host={$_ENV["DB_HOST"]};dbname={$_ENV["DB_DATABASE"]}",
                    username: $_ENV["DB_USERNAME"],
                    password: $_ENV["DB_PASSWORD"],
                    options: [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
                );
            } catch (PDOException $e) {
                die("Database connection failed: " . $e->getMessage());
            }
        }
        return self::$connection;
    }
}