<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Core\Database\Connection;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$c = new Connection();
