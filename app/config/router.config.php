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
    path: '/dashboard',
    roles: ["admin"],
    methods: ['GET'],
    controller: App\Controllers\Home::class,
    action: 'dashboard',
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

$router->addRoute(
    path: '/members/{id}',
    roles: ["admin", "member", "partner"],
    methods: ['GET'],
    controller: App\Controllers\Members::class,
    action: 'details',
);

$router->addRoute(
    path: '/members/{id}/edit',
    roles: ["admin", "member"],
    methods: ['GET', 'POST'],
    controller: App\Controllers\Members::class,
    action: 'edit',
);

$router->addRoute(
    path: '/members/{id}/delete',
    roles: ["admin", "member"],
    methods: ['GET'],
    controller: App\Controllers\Members::class,
    action: 'delete',
);
