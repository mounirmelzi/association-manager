<?php

header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

require_once __DIR__ . "/../vendor/autoload.php";
require_once __DIR__ . "/../app/autoload.php";
require_once __DIR__ . "/../app/config/router.config.php";

use App\Core\App;

$app = new App($router);
$app->run();
