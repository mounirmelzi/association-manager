<?php

require_once __DIR__ . "/../vendor/autoload.php";
require_once __DIR__ . "/../app/core/autoload.php";
require_once __DIR__ . "/../app/config/router.config.php";

use App\Core\App;

$app = new App($router);
$app->run();
