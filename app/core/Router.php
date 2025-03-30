<?php

namespace core;

class Router
{
    private array $routes = [];

    public function addRoute($uri, $controller, $method): void
    {
        $this->routes[$uri] = ['controller' => $controller, 'method' => $method];
    }

    public function dispatch(): void
    {
        $route = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

        $this->handleWebRoute($route);
    }

    private function handleWebRoute($route): void
    {
        if (isset($this->routes[$route])) {
            $controllerName = $this->routes[$route]['controller'];
            $methodName = $this->routes[$route]['method'];

            // Include the controller file
            require_once __DIR__ . "/../controller/$controllerName.php";

            // Add namespace prefix
            $fullyQualifiedController = "controller\\$controllerName";

            // Instantiate the controller
            $controller = new $fullyQualifiedController();
            $controller->$methodName();
            return;
        }

        http_response_code(404);
        require_once __DIR__ . '/../view/error/not-found-404.php';
    }
}
