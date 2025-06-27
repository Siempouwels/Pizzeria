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

        if (isset($routesForMethod[$uri])) {
            if ($method === 'POST') {
                $csrfToken = $_POST['csrf_token'] ?? '';
                if (! \Auth::verifyCsrfToken($csrfToken)) {
                    http_response_code(403);
                    echo "Ongeldige CSRF-token.";
                    exit;
                }
            }

            call_user_func($routesForMethod[$uri]);
            return;
        }

        foreach ($routesForMethod as $route => $handler) {
            $regex = preg_replace('#\{[^/]+\}#', '([^/]+)', $route);
            $pattern = "#^{$regex}$#";

            if (preg_match($pattern, $uri, $matches)) {
                if ($method === 'POST') {
                    $csrfToken = $_POST['csrf_token'] ?? '';
                    if (! \Auth::verifyCsrfToken($csrfToken)) {
                        http_response_code(403);
                        echo "Ongeldige CSRF-token.";
                        exit;
                    }
                }

                array_shift($matches);
                call_user_func_array($handler, $matches);
                return;
            }
        }

        $this->handleNotFound();
    }

    private function handleNotFound(): void
    {
        http_response_code(404);
        include __DIR__.'/../views/error/404.php';
    }
}
