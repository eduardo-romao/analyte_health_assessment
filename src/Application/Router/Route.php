<?php

namespace App\Application\Router;

use App\Application\Enums\HttpMethodEnum;

class Route {
    public function __construct (
        public HttpMethodEnum $method,
        public string $uri,
        public string $controller,
        public ?string $action = null,
        public ?string $name = null,
        public array $middlewares = [],
    ) {}
}