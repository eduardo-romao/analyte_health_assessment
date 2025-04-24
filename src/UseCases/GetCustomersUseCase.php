<?php

namespace App\UseCases;

use App\Core\Models\Customer;
use App\Core\Repositories\AddressRepositoryInterface;
use App\Core\Repositories\CustomerRepositoryInterface;

class GetCustomersUseCase {
    public function __construct(
        private CustomerRepositoryInterface $customerRepository,
        private AddressRepositoryInterface $addressRepository,
    ) {}

    public function __invoke(): array
    {
        return array_map(
            function (Customer $customer) {
                $address =  $this->addressRepository->findOneBy(
                    [
                        'customer_id' => sprintf(
                            'eq:%s',
                            $customer->getId()
                        ),
                    ]
                );
                $customer->setAddress($address);
                return $customer;
            },
            $this->customerRepository->findAll()
        );
    }
}