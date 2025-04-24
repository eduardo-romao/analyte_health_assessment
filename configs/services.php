<?php

use App\Application\Controllers\ApiCreateCustomer;
use App\Application\Controllers\ApiDeleteCustomers;
use App\Application\Controllers\ApiUpdateCustomer;
use App\Application\Controllers\CustomersListController;
use App\Core\Repositories\AddressRepositoryInterface;
use App\Core\Repositories\CustomerRepositoryInterface;
use App\Core\Services\ValidationService;
use App\Infraestructure\Database\AddressRepository;
use App\Infraestructure\Database\CustomerRepository;
use App\UseCases\DeleteCustomerUseCase;
use App\UseCases\GetCustomersUseCase;
use App\UseCases\CreateCustomerUseCase;
use App\UseCases\UpdateCustomerUseCase;

return [
    ValidationService::class => [
        'class' => ValidationService::class,
        'arguments' => [],
        'autowire' => true
    ],
    CustomerRepositoryInterface::class => [
        'class' => CustomerRepository::class,
        'arguments' => [],
    ],
    AddressRepositoryInterface::class => [
        'class' => AddressRepository::class,
        'arguments' => [],
    ],
    CustomersListController::class => [
        'class' => CustomersListController::class,
        'arguments' => [
            GetCustomersUseCase::class,
        ],
    ],
    ApiCreateCustomer::class => [
        'class' => ApiCreateCustomer::class,
        'arguments' => [
            CreateCustomerUseCase::class,
            ValidationService::class,
        ],
    ],
    ApiUpdateCustomer::class => [
        'class' => ApiUpdateCustomer::class,
        'arguments' => [],
        'autowire' => true
    ],
    ApiDeleteCustomers::class => [
        'class' => ApiDeleteCustomers::class,
        'arguments' => [],
        'autowire' => true
    ],
    DeleteCustomerUseCase::class => [
        'class' => DeleteCustomerUseCase::class,
        'arguments' => [],
        'autowire' => true
    ],
    GetCustomersUseCase::class => [
        'class' => GetCustomersUseCase::class,
        'arguments' => [],
        'autowire' => true
    ],
    CreateCustomerUseCase::class => [
        'class' => CreateCustomerUseCase::class,
        'arguments' => [],
        'autowire' => true
    ],
    UpdateCustomerUseCase::class => [
        'class' => UpdateCustomerUseCase::class,
        'arguments' => [],
        'autowire' => true
    ],
];
