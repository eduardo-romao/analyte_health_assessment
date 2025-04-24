<?php

namespace App\Application;

use App\Application\Bootstrap\EnvLoader;
use App\Application\Container\Container;
use App\Infraestructure\Database\MySQLConnectionBuilder;
use ReflectionClass;
use ReflectionParameter;

class Kernel {
    public function __construct(
        private string $env
    ) {}

    public function run(): void {
        EnvLoader::load(
            $this->env,
            __DIR__. '/../../.env'
        );
        $this->initializeContainer();
    }

    protected function initializeContainer(): void
    {
        $container = Container::getInstance();
        $container
            ->add(
                'env',
                $this->env
            )
            ->add(
                'db', 
                MySQLConnectionBuilder::build()
            );
        $services = require __DIR__ . '/../../configs/services.php';

        foreach(array_keys($services) as $id) {
            $this->loadDependencyInjection($id, $container, $services);
        }
    }

    protected function loadDependencyInjection(string $id, Container $container, array $services): void
    {
        if ($container->has($id)) {
            return;
        }
        $arguments = $services[$id]['arguments'];
        if (empty($arguments) && (!isset($services[$id]['autowire']) || $services[$id]['autowire'] == false)) {
            $container->add(
                $id,
                new $services[$id]['class']()
            );
            return;
        }
        if (isset($services[$id]['autowire']) && $services[$id]['autowire'] == true) {
            
            $reflectionClass = new ReflectionClass($services[$id]['class']);
            $reflectionMethod = $reflectionClass->getConstructor();
            if ($reflectionMethod) {
                $arguments = array_map(
                    fn(ReflectionParameter $param) => $param->getType()->getName(),
                    $reflectionMethod->getParameters()
                );
            }
        }
        $dependencies = [];
        foreach ($arguments as $arg) {
            $this->loadDependencyInjection($arg, $container, $services);
            $dependencies[] = $container->get($arg);
        }
        $container->add(
            $id,
            new $services[$id]['class'](...$dependencies)
        );
    }
}