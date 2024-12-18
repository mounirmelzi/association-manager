<?php

require_once __DIR__ . "/../config/app.config.php";
require_once __DIR__ . "/../controllers/HomeController.php";

class App
{
    private Router $router;

    public function __construct()
    {
        $this->router = new Router();
        $this->setRoutes();
    }

    public function run()
    {
        $this->router->dispatch();
    }

    private function setRoutes()
    {
        $this->router->addRoute(
            'GET',
            '/',
            HomeController::class,
            'index'
        );

        $this->router->addRoute(
            'GET',
            '/home',
            HomeController::class,
            'index'
        );
    }
}
