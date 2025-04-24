<?php

namespace App\Core\Services;

use Exception;

class ValidationService {

    public function validate(array $data, array $validations): void
    {
        foreach ($validations as $validation) {
            $rules = explode(':', $validation);
            $method = $rules[1];
            unset($rules[1]);
            $this->$method($data, ...$rules);
        }

    }

    public function required(array $data, string $field, ?string $label = null): self 
    {
        if (empty($data[$field])) {
            throw new Exception(
                sprintf(
                    'The %s is required',
                    $label ?? $field
                ),
                400
            );
        }

        return $this;
    }

    public function minLength(array $data, string $field, int $min, ?string $label = null): self 
    {
        if (mb_strlen($data[$field]) < $min) {
            throw new Exception(
                sprintf(
                    'The %s must be at least %d characters long',
                    $label ?? $field,
                    $min
                ),
                400
            );
        }

        return $this;
    }

    public function maxLenght(array $data, string $field, int $max, ?string $label = null): self 
    {
        if (mb_strlen($data[$field]) > $max) {
            throw new Exception(
                sprintf(
                    'The %s must be at most %d characters long',
                    $label ?? $field,
                    $max
                ),
                400
            );
        }

        return $this;
    }

    public function email(array $data, string $field, ?string $label = null): self
    {
        if (!filter_var($data[$field], FILTER_VALIDATE_EMAIL)) {
            throw new Exception(
                sprintf(
                    'The %s must be a valid email',
                    $label ?? $field
                ),
                400
            );
        }

        return $this;
    }

}