<?php

namespace App\Infraestructure\Database;

use Exception;
use PDO;
use Throwable;

class MySQLConnectionBuilder {
    public static function build(): PDO
    {
        try {
            $host = getenv('DB_HOST');
            $port = getenv('DB_PORT');
            $db   = getenv('DB_NAME');
            $user = getenv('DB_USER');
            $pass = getenv('DB_PASS');
            $charset = getenv('DB_CHARSET') ?? 'utf8mb4';
    
            $dsn = "mysql:host=$host;dbname=$db;port=$port;charset=$charset;";
    
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ];
    
            return new PDO($dsn, $user, $pass, $options);
        } catch (Throwable $e) {
            throw new Exception('Failded connect to database.', 500, $e);
        }
    }
}