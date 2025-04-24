<?php

namespace App\Application\Controllers;

use App\Core\Models\Address;
use App\Core\Models\Customer;
use App\Core\Services\ValidationService;
use App\UseCases\UpdateCustomerUseCase;

class ApiUpdateCustomer extends Controller {
    private array $customerValidations = [
        'name:required',
        'name:minLength:3',
        'name:maxLenght:50',
        'email:required',
        'email:email',
        'email:minLength:15',
        'email:maxLenght:50',
    ];
    private array $addressValidations = [
        'address:required',
        'address:minLength:10',
        'address:maxLenght:100',
    ];

    public function __construct(
        private UpdateCustomerUseCase $updateCustomerUseCase,
        private ValidationService $validationService,
    ) {}

    public function __invoke(int $id): void
    {
        $request = self::getJsonRequestData();
        $customerData = $request['customer'];
        $this->validationService->validate($customerData, $this->customerValidations);
        $addressData = $request['address'];
        $this->validationService->validate($addressData, $this->addressValidations);
        $customer = (new Customer())
            ->setName($customerData['name'])
            ->setEmail($customerData['email'])
            ->setId($id);
        $customer->setAddress(
            (new Address())
                ->setId($addressData['id'])
                ->setAddress($addressData['address'])
                ->setCustomer($customer)
        );

        $this->updateCustomerUseCase->__invoke($customer);
        self::toJson(
            [
                'message' => 'Customer updated successfully.'
            ],
            201
        );
    }
}