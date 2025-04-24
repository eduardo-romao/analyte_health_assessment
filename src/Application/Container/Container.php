<?php

namespace App\Application\Container;

class Container
{
    private static ?Container $instance = null;

    private array $services;

    private function __construct(){}

    public static function getInstance(): Container
    {
        if (!self::$instance) {
            self::$instance = new Container();
        }

        return self::$instance;
    }

    public function add(string $id, mixed $service): self
    {
        $this->services[$id] = $service;

        return $this;
    }

    public function get(string $id): mixed
    {
        return $this->services[$id];
    }

    public function has(string $id): bool
    {
        return isset($this->services[$id]);
    }
}