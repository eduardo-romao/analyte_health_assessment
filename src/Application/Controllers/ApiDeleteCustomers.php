<?php

namespace App\Application\Controllers;

use App\UseCases\DeleteCustomerUseCase;
use Exception;
use Throwable;

class ApiDeleteCustomers extends Controller {

    public function __construct(
        private DeleteCustomerUseCase $deleteCustomerUseCase
    ) {}

    public function __invoke(int $id): void
    {
        try {
            $this->deleteCustomerUseCase->__invoke($id);
            self::toJson(
                null,
                204
            );
        } catch (Throwable $e) {
            self::toJson(
                [
                    'message' => $e->getMessage()
                ],
                $e->getCode()
            );
        }
    }
}