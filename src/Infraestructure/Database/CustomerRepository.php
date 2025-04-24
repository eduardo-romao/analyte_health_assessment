<?php

namespace App\Infraestructure\Database;

use App\Core\Models\Customer;
use App\Core\Repositories\CustomerRepositoryInterface;
use App\Infraestructure\Entities\Customer as CustomerEntity;
use DateTime;

class CustomerRepository implements CustomerRepositoryInterface {
    public function create(Customer $customer): Customer
    {
        $customerEntity = (new CustomerEntity())
            ->setName($customer->getName())
            ->setEmail($customer->getEmail())
            ->create();
        return $customer
            ->setId($customerEntity->getId())
            ->setCreatedAt(new DateTime($customerEntity->getCreatedAt()));
    }
    
    public function update(Customer $customer): Customer
    {
        (new CustomerEntity())
            ->setId($customer->getId())
            ->setName($customer->getName())
            ->setEmail($customer->getEmail())
            ->update();

        return $customer;
    }

    public function delete(Customer $customer): void
    {
        CustomerEntity::delete($customer->getId());
    }
    /**
     * @return Customer[]
     */
    public function findAll(): array
    {
        return array_map(
            fn (CustomerEntity $customer) => $this->toModel($customer),
            CustomerEntity::findAll()
        );
    }

    public function find(int $id): ?Customer
    {
        $customerEntity = CustomerEntity::find($id);
        if (!$customerEntity) {
            return null;
        }
        return $this->toModel($customerEntity);
    }

    public function toModel(CustomerEntity $customerEntity): Customer
    {
        return (new Customer())
            ->setId($customerEntity->getId())
            ->setName($customerEntity->getName())
            ->setEmail($customerEntity->getEmail());
    }
}