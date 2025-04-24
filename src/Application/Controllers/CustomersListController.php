<?php

namespace App\Application\Controllers;

use App\UseCases\GetCustomersUseCase;

class CustomersListController extends Controller {
    public function __construct(
        private GetCustomersUseCase $getCustomersUseCase
    ) {}
    public function __invoke(): void 
    {
        $this->renderView('/customers/list.phtml', ['customers' => $this->getCustomersUseCase->__invoke()]);
    }
}