<?php

namespace App\Core;

use App\Utils\Request;
use App\Models\User;

class Router
{
    private array $routes = [];

    public function addRoute(
        array $methods,
        string $path,
        string $controller,
        string $action,
        array $roles = [],
    ): void {
        $this->routes[] = [
            'methods' => $methods,
            'path' => trim($path, '/'),
            'controller' => $controller,
            'action' => $action,
            'roles' => $roles,
        ];
    }

    public function dispatch(): bool
    {
        $url = trim(parse_url(Request::url(), PHP_URL_PATH), '/');

        foreach ($this->routes as $route) {
            if (!in_array(Request::method(), $route['methods'])) {
                continue;
            }

            $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[^/]+)', $route['path']);

            if (preg_match("#^$pattern$#", $url, $matches)) {
                if (!empty($route['roles'])) {
                    $user = User::current();
                    if (!$user) {
                        App::redirect("/login");
                        return false;
                    }

                    if (!in_array($user["role"], $route['roles'])) {
                        call_user_func_array([new \App\Controllers\Error(), "index"], ["error_code" => 403, "error_message" => "Forbidden"]);
                        return false;
                    }
                }

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
