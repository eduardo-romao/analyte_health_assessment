<?php

namespace App\Core\Repositories;

use App\Core\Models\Address;

interface AddressRepositoryInterface {
    public function create(Address $address): Address;
    public function update(Address $address): Address;
    public function findOneBy(array $criteria): ?Address;
    public function deleteBy(array $criteria): void;
}