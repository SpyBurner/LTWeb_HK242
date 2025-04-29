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
        foreach ($this->routes as $uri => $action) {
            // Chuyển route pattern thành regex, ví dụ: products/detail/:id => products\/detail\/([^\/]+)
            $pattern = preg_replace('#:([\w]+)#', '([^/]+)', $uri);
            $pattern = "#^{$pattern}$#";
    
            if (preg_match($pattern, $route, $matches)) {
                array_shift($matches); // Bỏ match đầu tiên (cả chuỗi)
                $controllerName = $action['controller'];
                $methodName = $action['method'];
    
                require_once __DIR__ . "/../controller/$controllerName.php";
                $fullyQualifiedController = "controller\\$controllerName";
                $controller = new $fullyQualifiedController();
    
                // Gọi method và truyền tham số
                call_user_func_array([$controller, $methodName], $matches);
                return;
            }
        }
    
        http_response_code(404);
        require_once __DIR__ . '/../view/error/not-found-404.php';
    }
}
