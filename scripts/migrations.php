
<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Application\Container\Container;
use App\Application\Kernel;
use App\Infraestructure\Database\MigrationHelper;

$kernel = new Kernel(getenv('APP_ENV') ?? 'dev');
$kernel->run();

$migrationHelper = new MigrationHelper(
    Container::getInstance()->get('db') 
);

$migrationHelper->run();



