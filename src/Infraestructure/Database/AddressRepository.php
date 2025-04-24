<?php

namespace App\Infraestructure\Database;

use App\Core\Models\Address;
use App\Core\Repositories\AddressRepositoryInterface;
use App\Infraestructure\Entities\Address as AddressEntity;
use DateTime;

class AddressRepository implements AddressRepositoryInterface {
    
    public function create(Address $address): Address
    {
        $addressEntity = (new AddressEntity())
            ->setCustomerId($address->getCustomer()->getId())
            ->setAddress($address->getAddress())
            ->create();
        return $address
            ->setId($addressEntity->getId())
            ->setCreatedAt(new DateTime($addressEntity->getCreatedAt()));
    }

    public function update(Address $address): Address
    {
        (new AddressEntity())
            ->setId($address->getId())
            ->setCustomerId($address->getCustomer()->getId())
            ->setAddress($address->getAddress())
            ->update();
        return $address;
    }

    public function findOneBy(array $criteria): ?Address
    {
        $addressEntity = AddressEntity::findOneBy($criteria);
        if (!$addressEntity) {
            return null;
        }
        return $this->toModel($addressEntity);
    }
    
    public function deleteBy(array $criteria): void
    {
        AddressEntity::deleteBy($criteria);
    }

    public function toModel(AddressEntity $addressEntity): Address
    {
        return (new Address())
            ->setId($addressEntity->getId())
            ->setAddress($addressEntity->getAddress())
            ->setCreatedAt(new DateTime($addressEntity->getCreatedAt()));
    }

}