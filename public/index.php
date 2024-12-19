<?php

require_once __DIR__ . "/../vendor/autoload.php";
require_once __DIR__ . "/../app/core/autoload.php";
require_once __DIR__ . "/../app/controllers/Home.php";

use App\Core\App;
use App\Core\Router;
use App\Controllers\Home;

$router = new Router();

$router->addRoute(
    'GET',
    '/',
    Home::class,
    'index'
);

$router->addRoute(
    'GET',
    '/home',
    Home::class,
    'index'
);

$app = new App($router);
$app->run();
