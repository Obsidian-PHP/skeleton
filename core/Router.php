<?php

declare(strict_types=1);

namespace Core;

use Core\Http\Exception\ForbiddenException;
use Core\Http\Exception\MethodNotAllowedException;
use Core\Http\Exception\NotFoundException;
use Core\Http\Exception\NotImplementedException;
use Core\Http\Middleware\MiddlewareStack;
use Core\Http\Request;

final class Router
{
    private Request $request;

    private array $routes = [];

    private array $middlewares = [];

    public function __construct()
    {
        $this->request = new Request();

        $this->init_routes();
    }

    private function init_routes(): void
    {
        foreach ($this->request->methods() as $method) $this->routes[$method] = null;
    }

    private function add(string $method, string $path, callable | string | array $action, string | array | null $middlewares = null): void
    {
        $this->routes[$method][$path] = $action;
        $this->middlewares[$method][$path] = $middlewares;
    }

    public function __call($name, $arguments): void
    {
        if (array_key_exists($name, $this->routes)) {
            $this->add($name, ...$arguments);
        } elseif ($name === 'any') {
            foreach ($this->request->methods() as $method)
                $this->add($method, ...$arguments);
        } else {
            throw new NotImplementedException();
        }
    }

    public function get_route($method): array
    {
        return $this->routes[$method] ?? [];
    }

    public function get_action(string $path, string $method): mixed
    {
        // Trim slashes
        $path = trim($path, '/');

        // Get all routes for current request method
        $routes = $this->get_route($method);

        $routeParams = false;

        // Start iterating registed routes
        foreach ($routes as $route => $callback) {
            // Trim slashes
            $route = trim($route, '/');
            $routeNames = [];

            if (!$route) continue;

            // Find all route names from route and save in $routeNames
            if (preg_match_all('/\{([a-zA-Z0-9]+(?:-[a-zA-Z0-9]+)*)(:[^}]+)?}/', $route, $matches)) {
                $routeNames = $matches[1];
            }

            // Convert route name into regex pattern
            $routeRegex = "@^" . preg_replace_callback('/\{([a-zA-Z0-9]+(?:-[a-zA-Z0-9]+)*)(:[^}]+)?}/', fn ($m) => isset($m[2]) ? "({$m[2]})" : '(\w+)', $route) . "$@";

            // Test and match current route against $routeRegex
            if (preg_match_all($routeRegex, $path, $valueMatches)) {
                $values = [];
                for ($i = 1; $i < count($valueMatches); $i++) {
                    $values[] = $valueMatches[$i][0];
                }
                $routeParams = array_combine($routeNames, $values);

                $this->request->set_route_params($routeParams);
                return $callback;
            }
        }

        return false;
    }

    public function resolve(): string | view | null
    {
        $path = $this->request->get_path();
        $method = $this->request->get_method();

        $action = $this->routes[$method][$path] ?? false;
        $middleware = $this->middlewares[$method][$path] ?? null;

        if (!$action) {
            $action = $this->get_action($path, $method);

            if ($action === false) {
                $routes = array_diff_key($this->routes, [$method => null]);

                foreach ($routes as $route) {
                    if (is_array($route)) {
                        foreach ($route as $key => $_value) {
                            if ($key === $path)
                                throw new MethodNotAllowedException();
                        }
                    }
                }

                throw new NotFoundException();
            }
        }

        if (is_array($action)) $action[0] = new $action[0]();

        $this->verify($middleware);

        return is_string($action) ? dump($action) : call_user_func($action, $this->request);
    }

    private function verify(string | array | null $middlewares): void
    {
        if (isset($middlewares)) {
            $middlewares = is_array($middlewares) ? $middlewares : [$middlewares];

            foreach ($middlewares as $middleware) {
                $middleware = MiddlewareStack::has($middleware);

                $middleware = new $middleware();
                call_user_func([$middleware, 'handle']) ?: throw new ForbiddenException();
            }
        }
    }
    
}
