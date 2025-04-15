<?php
class Router
{
    private array $routes = [];

    public function get(string $path, callable $handler): void
    {
        $this->addRoute('GET', $path, $handler);
    }

    public function post(string $path, callable $handler): void
    {
        $this->addRoute('POST', $path, $handler);
    }

    private function addRoute(string $method, string $path, callable $handler): void
    {
        $this->routes[$method][$path] = $handler;
    }

    public function dispatch(): void
    {
        $uri = strtok(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '?');
        $method = $_SERVER['REQUEST_METHOD'];
        $routesForMethod = $this->routes[$method] ?? [];

        // Exact match
        if (isset($routesForMethod[$uri])) {
            call_user_func($routesForMethod[$uri]);
            return;
        }

        // Dynamic routes
        foreach ($routesForMethod as $route => $handler) {
            $regex = preg_replace('#\{[^/]+\}#', '([^/]+)', $route);
            $pattern = "#^{$regex}$#";

            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches); // remove full match
                call_user_func_array($handler, $matches);
                return;
            }
        }

        // No route matched
        $this->handleNotFound();
    }

    private function handleNotFound(): void
    {
        http_response_code(404);
        include __DIR__.'/../views/error/404.php';
    }
}
