<?php

use App\Core\Router;

$router = new Router();

$router->addRoute(
    path: '/',
    methods: ['GET'],
    controller: App\Controllers\Home::class,
    action: 'index',
);

$router->addRoute(
    path: '/home',
    methods: ['GET'],
    controller: App\Controllers\Home::class,
    action: 'index',
);

$router->addRoute(
    path: '/login',
    methods: ['GET', 'POST'],
    controller: App\Controllers\Auth::class,
    action: 'login',
);

$router->addRoute(
    path: '/register',
    methods: ['GET', 'POST'],
    controller: App\Controllers\Auth::class,
    action: 'register',
);

$router->addRoute(
    path: '/members',
    roles: ["admin"],
    methods: ['GET'],
    controller: App\Controllers\Members::class,
    action: 'index',
);
