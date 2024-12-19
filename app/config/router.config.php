<?php

require_once __DIR__ . "/../controllers/Home.php";

use App\Core\Router;

$router = new Router();

$router->addRoute(
    'GET',
    '/',
    App\Controllers\Home::class,
    'index'
);

$router->addRoute(
    'GET',
    '/home',
    App\Controllers\Home::class,
    'index'
);
