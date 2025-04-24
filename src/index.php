<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Application\Container\Container;
use App\Application\Kernel;
use App\Application\Enums\HttpMethodEnum;
use App\Application\Router\Router;

$kernel = new Kernel(getenv('APP_ENV') ?? 'dev');
$kernel->run();
$router = new Router(
    Container::getInstance(),
    require '../configs/routes.php'
);
$router->route(
    HttpMethodEnum::from($_SERVER['REQUEST_METHOD']),
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
);
