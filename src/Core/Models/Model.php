<?php

namespace App\Core\Models;

use DateTime;
use JsonSerializable;

abstract class Model implements JsonSerializable {

    abstract function getId(): int;
    abstract function setId(int $id): self;

    public function jsonSerialize(): array 
    {
        $data = [];
        foreach (get_object_vars($this) as $prop => $value) {
            $data[$prop] = match(true) {
                $value instanceof DateTime => $value->format('Y-m-d H:i:s'),
                $value instanceof Model => $value->jsonSerialize(),
                is_object($value) && method_exists($value, 'getId') => $value->getId(),
                default => $value,
            };
        }
        return $data;
    }
}