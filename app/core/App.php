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

    public function run(): void
    {
        $this->router->dispatch();
    }

    public static function redirect(string $url): never
    {
        $url = trim($url, "/");
        header("Location: " . BASE_URL . $url);
        exit;
    }

    public static function refresh(): never
    {
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit;
    }

    private function setRoutes(): void
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
