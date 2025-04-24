<?php

namespace App\UseCases;

use App\Core\Repositories\CustomerRepositoryInterface;
use Exception;

class DeleteCustomerUseCase {
    public function __construct(
        private CustomerRepositoryInterface $customerRepository
    ) {}

    public function __invoke(int $id): void 
    {
        $customer = $this->customerRepository->find($id);
        if (!$customer) {
            throw new Exception(
                'Invalid customer.',
                400
            );
        }
        $this->customerRepository->delete($customer);
    }
}