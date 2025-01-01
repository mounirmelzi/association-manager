<?php

namespace App\Core;

require_once __DIR__ . "/../config/app.config.php";

class App
{
    private Router $router;

    public function __construct(Router $router)
    {
        $this->setRouter($router);
    }

    public function __destruct()
    {
        Database::close();
    }

    public function getRouter(): Router
    {
        return $this->router;
    }

    public function setRouter(Router $router): void
    {
        $this->router = $router;
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
}
