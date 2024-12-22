<?php

namespace Core\Http\Middleware;

use Exception;

final class MiddlewareStack
{
    private static array $middleware_stack = [];

    public function add(string $alias, string $middleware_class): void
    {
        self::$middleware_stack[$alias] = $middleware_class;
    }

    public static function has(string $middleware): string | bool
    {
        return self::$middleware_stack[$middleware] ?? throw new Exception("Middleware not found in the stack.");
    }
}
