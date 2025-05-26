<?php
class Router
{
    private array $routes = [];

    public function get(string $path, callable $handler): void
    {
        $this->routes['GET'][$path] = $handler;
    }

    public function post(string $path, callable $handler): void
    {
        $this->routes['POST'][$path] = $handler;
    }

    public function put(string $path, callable $handler): void
    {
        $this->routes['PUT'][$path] = $handler;
    }

    public function delete(string $path, callable $handler): void
    {
        $this->routes['DELETE'][$path] = $handler;
    }

    public function resolve($method, $uri): void
    {
        foreach ($this->routes[$method] as $route => $handler) {
            $routeRegex = preg_replace('/\{[a-zA-Z_]+\}/', '(\d+)', $route);
            $routeRegex = "~^" . $routeRegex . "$~";

            if (preg_match($routeRegex, $uri, $matches)) {
                array_shift($matches);
                call_user_func_array($handler, $matches);
                return;
            }
        }
        http_response_code(404);
        echo json_encode(["message" => "Route not found"]);
    }

}
