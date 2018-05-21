<?php declare(strict_types=1);

use BenRowan\DoctrineAssert\Tests\AbstractDoctrineTest;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

require_once 'vendor/autoload.php';

$isDevMode = true;

$config = Setup::createAnnotationMetadataConfiguration(
    [__DIR__ . '/tests'],
    $isDevMode
);

$connection = [
    'driver'   => 'pdo_mysql',
    'user'     => AbstractDoctrineTest::DB_USER,
    'password' => AbstractDoctrineTest::DB_PASS,
    'host'     => AbstractDoctrineTest::DB_HOST,
    'dbname'   => AbstractDoctrineTest::DB_NAME
];

$entityManager = EntityManager::create($connection, $config);

return ConsoleRunner::createHelperSet($entityManager);