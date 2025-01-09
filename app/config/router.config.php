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

$router->addRoute(
    path: '/partners',
    roles: ["admin"],
    methods: ['GET'],
    controller: App\Controllers\Partners::class,
    action: 'index',
);

$router->addRoute(
    path: '/partners/create',
    roles: ["admin"],
    methods: ['GET', 'POST'],
    controller: App\Controllers\Partners::class,
    action: 'create',
);

$router->addRoute(
    path: '/partners/{id}',
    roles: ["admin", "partner"],
    methods: ['GET'],
    controller: App\Controllers\Partners::class,
    action: 'details',
);

$router->addRoute(
    path: '/partners/{id}/edit',
    roles: ["admin", "partner"],
    methods: ['GET', 'POST'],
    controller: App\Controllers\Partners::class,
    action: 'edit',
);

$router->addRoute(
    path: '/partners/{id}/delete',
    roles: ["admin", "partner"],
    methods: ['GET'],
    controller: App\Controllers\Partners::class,
    action: 'delete',
);

$router->addRoute(
    path: '/activities',
    roles: ["admin"],
    methods: ['GET'],
    controller: App\Controllers\Activities::class,
    action: 'index',
);

$router->addRoute(
    path: '/activities/create',
    roles: ["admin"],
    methods: ['GET', 'POST'],
    controller: App\Controllers\Activities::class,
    action: 'create',
);

$router->addRoute(
    path: '/activities/{id}',
    roles: ["admin"],
    methods: ['GET'],
    controller: App\Controllers\Activities::class,
    action: 'details',
);

$router->addRoute(
    path: '/activities/{id}/edit',
    roles: ["admin"],
    methods: ['GET', 'POST'],
    controller: App\Controllers\Activities::class,
    action: 'edit',
);

$router->addRoute(
    path: '/activities/{id}/delete',
    roles: ["admin"],
    methods: ['GET'],
    controller: App\Controllers\Activities::class,
    action: 'delete',
);

$router->addRoute(
    path: '/news',
    roles: ["admin"],
    methods: ['GET'],
    controller: App\Controllers\News::class,
    action: 'index',
);

$router->addRoute(
    path: '/news/create',
    roles: ["admin"],
    methods: ['GET', 'POST'],
    controller: App\Controllers\News::class,
    action: 'create',
);

$router->addRoute(
    path: '/news/{id}',
    roles: ["admin"],
    methods: ['GET'],
    controller: App\Controllers\News::class,
    action: 'details',
);

$router->addRoute(
    path: '/news/{id}/edit',
    roles: ["admin"],
    methods: ['GET', 'POST'],
    controller: App\Controllers\News::class,
    action: 'edit',
);

$router->addRoute(
    path: '/news/{id}/delete',
    roles: ["admin"],
    methods: ['GET'],
    controller: App\Controllers\News::class,
    action: 'delete',
);

$router->addRoute(
    path: '/diaporama',
    roles: ["admin"],
    methods: ['GET', 'POST'],
    controller: App\Controllers\Diaporama::class,
    action: 'index',
);

$router->addRoute(
    path: '/diaporama/{id}/delete',
    roles: ["admin"],
    methods: ['GET'],
    controller: App\Controllers\Diaporama::class,
    action: 'delete',
);
