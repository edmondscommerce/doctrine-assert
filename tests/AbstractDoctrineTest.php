<?php declare(strict_types=1);

namespace BenRowan\DoctrineAssert\Tests;

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;


abstract class AbstractDoctrineTest extends TestCase
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @return EntityManager
     */
    public function getEntityManager(): EntityManager
    {
        return $this->entityManager;
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     */
    protected function setupEntityManager(): void
    {
        $isDevMode = true;

        $config = Setup::createAnnotationMetadataConfiguration(
            [__DIR__ . '/Entity'],
            $isDevMode
        );

        $connection = [
            'driver'   => 'pdo_mysql',
            'user'     => $_ENV['DB_USER'],
            'password' => $_ENV['DB_PASS'],
            'host'     => $_ENV['DB_HOST'],
            'dbname'   => $_ENV['DB_NAME']
        ];

        $this->entityManager = EntityManager::create($connection, $config);
    }
}