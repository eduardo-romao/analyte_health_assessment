<?php

namespace App\Infraestructure\Database;

use PDO;
use RuntimeException;

class MigrationHelper {

    private array $versions = [
        '/versions/00001.sql'
    ];

    public function __construct(
        private PDO $pdo
    ) {}

    public function run(): void
    {
        foreach ($this->versions as $version) {
            $sql = file_get_contents(__DIR__ . '/../../../migrations' . $version);
            if ($sql === false) {
                throw new RuntimeException("Version file not found: $version");
            }
            $commands = preg_split('/;\s*$/m', $sql);
            foreach ($commands as $command) {
                $comando = trim($command);
                if (!empty($comando)) {
                    echo "Running scrpit: " . PHP_EOL;
                    echo $comando . PHP_EOL;
                    $this->pdo->exec($comando);
                }
            }
        }
        echo "Migration finished!" . PHP_EOL;
    }
}