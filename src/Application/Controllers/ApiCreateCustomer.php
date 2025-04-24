<?php

namespace App\Application\Controllers;

use App\Core\Models\Address;
use App\Core\Models\Customer;
use App\Core\Services\ValidationService;
use App\UseCases\CreateCustomerUseCase;

class ApiCreateCustomer extends Controller {
    private array $customerValidations = [
        'name:required',
        'name:minLength:3',
        'name:maxLenght:50',
        'email:required',
        'email:email',
        'email:minLength:10',
        'email:maxLenght:50',
    ];
    private array $addressValidations = [
        'address:required',
        'address:minLength:10',
        'address:maxLenght:100',
    ];

    public function __construct(
        private CreateCustomerUseCase $createCustomerUseCase,
        private ValidationService $validationService,
    ) {}

    public function __invoke(): void
    {
        $request = self::getJsonRequestData();
        $customerData = $request['customer'];
        $this->validationService->validate($customerData, $this->customerValidations);
        $addressData = $request['address'];
        $this->validationService->validate($addressData, $this->addressValidations);
        $customer = (new Customer())
            ->setName($customerData['name'])
            ->setEmail($customerData['email']);
        $customer->setAddress(
            (new Address())
                ->setAddress($addressData['address'])
                ->setCustomer($customer)
        );

        $this->createCustomerUseCase->__invoke($customer);
        self::toJson(
            [
                'message' => 'Customer created successfully.'
            ],
            201
        );
    }
}