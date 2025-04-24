<?php

namespace App\UseCases;

use App\Core\Models\Customer;
use App\Core\Repositories\AddressRepositoryInterface;
use App\Core\Repositories\CustomerRepositoryInterface;

class CreateCustomerUseCase {

    public function __construct(
        private CustomerRepositoryInterface $customerRepository,
        private AddressRepositoryInterface $addressRepository,
    ) {}

    public function __invoke(Customer $customer): Customer
    {
        $this->customerRepository->create($customer);
        if ($customer->getAddress()) {
            $this->addressRepository->create($customer->getAddress());
        }

        return $customer;
    }
}