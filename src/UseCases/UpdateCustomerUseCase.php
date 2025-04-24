<?php

namespace App\UseCases;

use App\Core\Models\Customer;
use App\Core\Repositories\AddressRepositoryInterface;
use App\Core\Repositories\CustomerRepositoryInterface;

class UpdateCustomerUseCase {
    public function __construct(
        private CustomerRepositoryInterface $customerRepository,
        private AddressRepositoryInterface $addressRepository
    ) {}

    public function __invoke(Customer $customer): Customer
    {
        $this->customerRepository->update($customer);
        if ($customer->getAddress()?->getId()) {
            $this->addressRepository->update($customer->getAddress());
        } else if ($customer->getAddress()) {
            $this->addressRepository->create($customer->getAddress());
        } else {
            $this->addressRepository->deleteBy(
                [
                    'customer_id' => sprintf(
                        'eq:%s',
                        $customer->getId()
                    )
                ]
            );
        }

        return $customer;
    }
}