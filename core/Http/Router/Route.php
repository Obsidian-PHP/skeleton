<?php

namespace Core\Http\Router;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class Route {
    public function __construct(private string $route, private string $method, private string $midleware)
    {
     /**
      * Code
      */
    }

    public function getRoute(): string
    {
        return $this->route;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getMiddleware(): string
    {
        return $this->midleware;
    }
}   