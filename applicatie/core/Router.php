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
        $requestUri = strtok(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '?');
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        // Check op exacte match
        if (isset($this->routes[$requestMethod][$requestUri])) {
            $handler = $this->routes[$requestMethod][$requestUri];
            call_user_func($handler);
            return;
        }

        // Dynamische route matching (bv. /product/{naam})
        $definedRoutes = $this->routes[$requestMethod] ?? [];

        foreach ($definedRoutes as $routeDefinition => $handler) {
            // Vervang {param} door een regex-matchgroep (zoals ([^/]+))
            $regexifiedRoute = preg_replace('#\{[^/]+\}#', '([^/]+)', $routeDefinition);
            $regexPattern = "#^{$regexifiedRoute}$#";

            if (preg_match($regexPattern, $requestUri, $matches)) {
                array_shift($matches); // verwijder volledige match uit de resultaten
                call_user_func_array($handler, $matches);
                return;
            }
        }

        // Geen match gevonden
        http_response_code(404);
        include __DIR__.'/../views/error/404.php'; 
    }
}
