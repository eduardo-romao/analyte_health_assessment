<?php

namespace App\Application\Router;

use App\Application\Container\Container;
use App\Application\Enums\HttpMethodEnum;
use Exception;
use Throwable;

class Router {
    /** @var Route[] */
    private array $routes = [];
    
    public function __construct(
        private Container $container,
        array $routes = []
    ) {
        foreach ($routes as $route) {
            $this->routes[$route->method->value][$route->uri] = $route;
        }
    }

    public function route(HttpMethodEnum $method, string $uri): void
    {
        try {
            if (isset($this->routes[$method->value])) {
                foreach($this->routes[$method->value] as $route) {
                    $regexPattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[^/]+)', $route->uri);
                    $regexPattern = '#^' . str_replace('/', '\\/', $regexPattern) . '$#';
        
                    if (preg_match($regexPattern, $uri, $matches)) {
                        $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                        $params = array_map(
                            fn($val) => is_numeric($val) ? intval($val) : $val,
                            $params
                        );
                        if (!$this->container->has($route->controller)) {
                            throw new Exception(
                                sprintf(
                                    'Controller is not registered: %s',
                                    $route->controller
                                ),
                                500
                            );
                        }
                        $controller = $this->container->get($route->controller);
                        $controller->setContainer($this->container);
                        $action = $route->action;
                        if (!$action) {
                            $action = '__invoke';
                        }
                        
                        $controller->$action(...$params);
                        return;
                    }
                }
            }
            throw new Exception('Route not found.', 404);
        } catch (Throwable $e) {
            header('Content-Type: application/json');
            http_response_code(
                match ($e->getCode()) {
                    100 => 400,
                    101 => 400,
                    102 => 401,
                    103 => 403,
                    104 => 404,
                    105 => 405,
                    106 => 409,
                    107 => 422,
                    404 => 404,
                    500 => 500,
                    200 => 500,
                    201 => 501,
                    202 => 502,
                    203 => 503,
                    204 => 504,
                    default => 500,
                }
            );
            echo json_encode([
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            exit;
        }
    }
}