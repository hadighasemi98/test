<?php

use App\Core\Router\Router;

include "Bootstrap/init.php";

# ToDo: Rate limiter

$new = new Router();
$new->run();