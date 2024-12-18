<?php

class Router
{
    private array $routes = [];

    public function addRoute(
        string $method,
        string $path,
        string $controller,
        string $action,
    ) {
        $this->routes[] = [
            'method' => strtoupper($method),
            'path' => trim($path, '/'),
            'controller' => $controller,
            'action' => $action,
        ];
    }

    public function dispatch()
    {
        $url = trim(parse_url($_GET['url'] ?? "/", PHP_URL_PATH), '/');

        foreach ($this->routes as $route) {
            if ($route['method'] !== $_SERVER['REQUEST_METHOD']) continue;

            $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[^/]+)', $route['path']);

            if (preg_match("#^$pattern$#", $url, $matches)) {
                $params = array_filter(
                    $matches,
                    fn($key) => !is_numeric($key),
                    ARRAY_FILTER_USE_KEY
                );

                if (class_exists($route['controller']) && method_exists($route['controller'], $route['action'])) {
                    call_user_func_array([new $route['controller'], $route['action']], $params);
                    return;
                } else {
                    http_response_code(500);
                    echo "Controller or action not found.";
                    return;
                }
            }
        }

        http_response_code(404);
        echo "Route not found.";
    }
}
