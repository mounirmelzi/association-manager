<?php

use App\Core\Router;

$router = new Router();

$router->addRoute(
    path: '/',
    methods: ['GET'],
    controller: App\Controllers\Home::class,
    action: 'home',
);

$router->addRoute(
    path: '/home',
    methods: ['GET'],
    controller: App\Controllers\Home::class,
    action: 'home',
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
    path: '/logout',
    roles: ['admin', 'partner', 'member'],
    methods: ['GET'],
    controller: App\Controllers\Auth::class,
    action: 'logout',
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

$router->addRoute(
    path: '/navbar',
    roles: ["admin"],
    methods: ['GET', 'POST'],
    controller: App\Controllers\Navbar::class,
    action: 'index',
);

$router->addRoute(
    path: '/navbar/{id}/delete',
    roles: ["admin"],
    methods: ['GET'],
    controller: App\Controllers\Navbar::class,
    action: 'delete',
);

$router->addRoute(
    path: '/cards/types',
    roles: ["admin"],
    methods: ['GET'],
    controller: App\Controllers\CardTypes::class,
    action: 'index',
);

$router->addRoute(
    path: '/cards/types/create',
    roles: ["admin"],
    methods: ['GET', 'POST'],
    controller: App\Controllers\CardTypes::class,
    action: 'create',
);

$router->addRoute(
    path: '/cards/types/{id}/edit',
    roles: ["admin"],
    methods: ['GET', 'POST'],
    controller: App\Controllers\CardTypes::class,
    action: 'edit',
);

$router->addRoute(
    path: '/cards/types/{id}/delete',
    roles: ["admin"],
    methods: ['GET'],
    controller: App\Controllers\CardTypes::class,
    action: 'delete',
);

$router->addRoute(
    path: '/helps/types',
    roles: ["admin"],
    methods: ['GET'],
    controller: App\Controllers\HelpTypes::class,
    action: 'index',
);

$router->addRoute(
    path: '/helps/types/create',
    roles: ["admin"],
    methods: ['GET', 'POST'],
    controller: App\Controllers\HelpTypes::class,
    action: 'create',
);

$router->addRoute(
    path: '/helps/types/{id}/edit',
    roles: ["admin"],
    methods: ['GET', 'POST'],
    controller: App\Controllers\HelpTypes::class,
    action: 'edit',
);

$router->addRoute(
    path: '/helps/types/{id}/delete',
    roles: ["admin"],
    methods: ['GET'],
    controller: App\Controllers\HelpTypes::class,
    action: 'delete',
);

$router->addRoute(
    path: '/discounts/offers',
    roles: ["admin"],
    methods: ['GET'],
    controller: App\Controllers\DiscountOffers::class,
    action: 'index',
);

$router->addRoute(
    path: '/discounts/offers/create',
    roles: ["admin"],
    methods: ['GET', 'POST'],
    controller: App\Controllers\DiscountOffers::class,
    action: 'create',
);

$router->addRoute(
    path: '/discounts/offers/{id}/edit',
    roles: ["admin"],
    methods: ['GET', 'POST'],
    controller: App\Controllers\DiscountOffers::class,
    action: 'edit',
);

$router->addRoute(
    path: '/discounts/offers/{id}/delete',
    roles: ["admin"],
    methods: ['GET'],
    controller: App\Controllers\DiscountOffers::class,
    action: 'delete',
);

$router->addRoute(
    path: '/discounts/limited/offers',
    roles: ["admin"],
    methods: ['GET'],
    controller: App\Controllers\LimitedDiscountOffers::class,
    action: 'index',
);

$router->addRoute(
    path: '/discounts/limited/offers/create',
    roles: ["admin"],
    methods: ['GET', 'POST'],
    controller: App\Controllers\LimitedDiscountOffers::class,
    action: 'create',
);

$router->addRoute(
    path: '/discounts/limited/offers/{id}/edit',
    roles: ["admin"],
    methods: ['GET', 'POST'],
    controller: App\Controllers\LimitedDiscountOffers::class,
    action: 'edit',
);

$router->addRoute(
    path: '/discounts/limited/offers/{id}/delete',
    roles: ["admin"],
    methods: ['GET'],
    controller: App\Controllers\LimitedDiscountOffers::class,
    action: 'delete',
);
