<?php declare(strict_types=1);

namespace BenRowan\DoctrineAssert\Tests;

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;


abstract class AbstractDoctrineTest extends TestCase
{
    public const DB_USER = 'test';
    public const DB_PASS = 'password';
    public const DB_HOST = 'localhost';
    public const DB_NAME = 'doctrine_assert_test';

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
            'user'     => self::DB_USER,
            'password' => self::DB_PASS,
            'host'     => self::DB_HOST,
            'dbname'   => self::DB_NAME
        ];

        $this->entityManager = EntityManager::create($connection, $config);
    }
}