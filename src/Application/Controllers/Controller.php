<?php

namespace App\Application\Controllers;

use App\Application\Container\Container;

abstract class Controller {

    private Container $container;

    public function setContainer(Container $container): void
    {
        $this->container = $container;
    }

    public function renderView(string $path, array $data = []): void
    {
        $data = array_merge(
            [
                'container' => $this->container
            ],
            $data
        );
        extract($data);
        require __DIR__ . '/../../views' . $path;
    }

    public static function toJson(?array $data = null, int $httpStatus = 200): void
    {
        http_response_code($httpStatus);
        header('Content-Type: application/json');
        if ($data) {
            echo json_encode($data);
        }
    }

    public static function getJsonRequestData(): array
    {
        $jsonPayload = file_get_contents('php://input');
        return json_decode($jsonPayload, true);
    }

}