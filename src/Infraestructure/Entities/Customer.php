<?php

namespace App\Infraestructure\Entities;

use App\Infraestructure\ActiveRecord\ActiveRedord;
use DateTime;

class Customer extends ActiveRedord {

    protected static array $fields = [
        'id' => 'id',
        'name' => 'name',
        'email' => 'email',
        'created_at' => 'createdAt',
    ];
    protected static array $fillable = [
        'name',
        'email',
    ];

    protected int $id;
    protected string $name;
    protected string $email;
    protected string $createdAt;
    protected ?Address $address = null;


    public static function getTableName(): string
    {
        return 'customers';
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }
    
    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getAddress(): ?Address
    {
        if (!$this->address) {
            $this->address = Address::findOneBy([
                'customer_id' => sprintf(
                    'eq:%s',
                    $this->getId()
                ),
            ]);
        }
        return $this->address;
    }
}