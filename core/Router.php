<?php
declare(strict_types=1);

class Router
{
    private array $routes = [];

    public function get(
        string $path,
        string $controller,
        string $method,
        bool $protected = false
    ): void {
        $this->addRoute('GET', $path, $controller, $method, $protected);
    }

    public function post(
        string $path,
        string $controller,
        string $method,
        bool $protected = false
    ): void {
        $this->addRoute('POST', $path, $controller, $method, $protected);
    }

    private function addRoute(
        string $verb,
        string $path,
        string $controller,
        string $method,
        bool $protected
    ): void {
        $this->routes[] = compact('verb', 'path', 'controller', 'method', 'protected');
    }

    public function dispatch(): void
    {
        $uri = trim(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH), '/');

        // Strip the subfolder prefix defined in index.php (e.g. "PhpProjs")
        $base = trim(BASE_URL, '/');
        if ($base !== '' && str_starts_with($uri, $base)) {
            $uri = trim(substr($uri, strlen($base)), '/');
        }

        $verb = strtoupper($_SERVER['REQUEST_METHOD']);

        foreach ($this->routes as $route) {
            [$matched, $params] = $this->matchPath($route['path'], $uri);

            if ($matched && $route['verb'] === $verb) {
                if ($route['protected']) {
                    require_once ROOT_PATH . '/app/Middleware/AuthMiddleware.php';
                    AuthMiddleware::handle();
                }

                $controllerClass = 'app\\Controllers\\' . $route['controller'];
                $controller = new $controllerClass();
                $controller->{$route['method']}(...$params);
                return;
            }
        }

        http_response_code(404);
        require_once ROOT_PATH . '/views/layout/header.php';
        echo '<h2>404 — Página não encontrada</h2><p><a href="' . BASE_URL . '/items">Voltar</a></p>';
        require_once ROOT_PATH . '/views/layout/footer.php';
    }

    // Converts "items/edit/{id}" to a regex, returns [matched, params[]]
    private function matchPath(string $routePath, string $uri): array
    {
        $pattern = preg_replace('/\{[a-z_]+\}/', '([^/]+)', $routePath);
        $pattern = '#^' . $pattern . '$#';

        if (preg_match($pattern, $uri, $matches)) {
            array_shift($matches);
            return [true, $matches];
        }

        return [false, []];
    }
}
