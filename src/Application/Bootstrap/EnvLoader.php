<?php

namespace App\Application\Bootstrap;

use Exception;

class EnvLoader {
    public static function load(
        string $env,
        string $path
    ): void {
        putenv("env=$env");
        $_ENV['env'] = $env;
        $_SERVER['env'] = $env;

        if (!file_exists($path)) {
            throw new Exception('Env file not found.', 500);
        }
    
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (str_starts_with(trim($line), '#')) continue;
    
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
    
            putenv("$key=$value");
            $_ENV[$key] = $value;
            $_SERVER[$key] = $value;
        }
    }
}

