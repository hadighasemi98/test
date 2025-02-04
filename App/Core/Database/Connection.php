<?php

namespace App\Core\Database;
use PDO;
use PDOException;

class Connection
{
    public function __construct()
    {
        try {
            $dsn = 'mysql:host=' . $_ENV["DB_HOST"] . ';dbname=' . $_ENV["DB_DATABASE"];
            $username = $_ENV["DB_USERNAME"];
            $password = $_ENV["DB_PASSWORD"];
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION // set the PDO error mode to exception
            ];

            new PDO($dsn, $username, $password, $options);
        } catch (PDOException $e) {
            echo($e->getMessage());
        }
    }
}