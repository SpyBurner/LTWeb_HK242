<?php

namespace core;
class Router
{
    private $routes = [];

    public function addRoute($uri, $controller, $method)
    {
        $this->routes[$uri] = ['controller' => $controller, 'method' => $method];
    }

    public function dispatch()
    {
        $route = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

        $this->handleWebRoute($route);
    }

    private function handleWebRoute($route)
    {
        if (isset($this->routes[$route])) {
            $controllerName = $this->routes[$route]['controller'];
            $methodName = $this->routes[$route]['method'];

            require_once __DIR__ . "/../controller/$controllerName.php";
            $controller = new $controllerName();
            return $controller->$methodName();
        }

        http_response_code(404);
        require_once __DIR__ . '/../view/404.php';

        return null;
    }
}
