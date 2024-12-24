<?php

namespace App\Core;

use App\Utils\Request;

class Router
{
    private array $routes = [];

    public function addRoute(
        string $method,
        string $path,
        string $controller,
        string $action,
    ): void {
        $this->routes[] = [
            'method' => strtoupper($method),
            'path' => trim($path, '/'),
            'controller' => $controller,
            'action' => $action,
        ];
    }

    public function dispatch(): bool
    {
        $url = trim(parse_url(Request::url(), PHP_URL_PATH), '/');

        foreach ($this->routes as $route) {
            if ($route['method'] !== Request::method()) continue;

            $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[^/]+)', $route['path']);

            if (preg_match("#^$pattern$#", $url, $matches)) {
                $params = array_filter(
                    $matches,
                    fn($key) => !is_numeric($key),
                    ARRAY_FILTER_USE_KEY
                );

                if (class_exists($route['controller']) && method_exists($route['controller'], $route['action'])) {
                    call_user_func_array([new $route['controller'](), $route['action']], $params);
                    return true;
                } else {
                    call_user_func_array([new \App\Controllers\Error(), "index"], ["error_code" => 500, "error_message" => "Controller or action not found"]);
                    return false;
                }
            }
        }

        call_user_func_array([new \App\Controllers\Error(), "index"], ["error_code" => 404, "error_message" => "Route not found"]);
        return false;
    }
}
