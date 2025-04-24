<?php

namespace App\Core\Repositories;

use App\Core\Models\Customer;

interface CustomerRepositoryInterface {
    public function create(Customer $customer): Customer;
    public function update(Customer $customer): Customer;
    public function delete(Customer $customer): void;
    /**
     * @return Customer[]
     */
    public function findAll(): array;
    public function find(int $id): ?Customer;
}