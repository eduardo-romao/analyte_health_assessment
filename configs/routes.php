<?php

use App\Application\Controllers\ApiCreateCustomer;
use App\Application\Controllers\ApiDeleteCustomers;
use App\Application\Controllers\ApiUpdateCustomer;
use App\Application\Controllers\CustomersListController;
use App\Application\Enums\HttpMethodEnum;
use App\Application\Router\Route;

return [
    new Route(
        HttpMethodEnum::GET, 
        '/',
        CustomersListController::class,
    ),
    new Route(
        HttpMethodEnum::GET,
        '/customers',
        CustomersListController::class,
    ),
    new Route(
        HttpMethodEnum::PUT,
        '/api/v1/customers/{id}',
        ApiUpdateCustomer::class,
    ),
    new Route(
        HttpMethodEnum::DELETE,
        '/api/v1/customers/{id}',
        ApiDeleteCustomers::class,
    ),
    new Route(
        HttpMethodEnum::POST,
        '/api/v1/customers',
        ApiCreateCustomer::class,
    ),
];