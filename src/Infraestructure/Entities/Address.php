<?php

namespace App\Infraestructure\Entities;

use App\Infraestructure\ActiveRecord\ActiveRedord;

class Address extends ActiveRedord {

    protected static array $fields = [
        'id' => 'id',
        'customer_id' => 'customerId',
        'address' => 'address',
        'created_at' => 'createdAt',
    ];
    protected static array $fillable = [
        'address',
        'customerId',
    ];

    public static function getTableName(): string
    {
        return 'addresses';
    }

    protected int $id;
    protected ?int $customerId = null;
    protected string $address;
    protected string $createdAt;
    protected ?Customer $customer = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function getCustomer(): Customer
    {
        if (!$this->customer && $this->customerId) {
            $this->customer = Customer::find($this->customerId);
        }
        return $this->customer;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function setCustomerId(int $customerId): self
    {
        $this->customerId = $customerId;
        return $this;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;
        return $this;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }
}