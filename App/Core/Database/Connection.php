<?php

class Connection
{

    public function __construct(array $config)
    {
        try {
            new PDO(
                dsn: $config['connection'] . 'dbname=' . $config['name'],
                username: $config['username'],
                password: $config['password'],
                options: $config['options']
            );
        } catch (PDOException $e) {
            print_r($e->getMessage());
        }
    }
}