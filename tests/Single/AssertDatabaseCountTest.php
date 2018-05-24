<?php declare(strict_types=1);

namespace BenRowan\DoctrineAssert\Tests\Single;

use BenRowan\DoctrineAssert\DoctrineAssertTrait;
use BenRowan\DoctrineAssert\Tests\AbstractDoctrineTest;
use Faker\Factory;
use Faker\ORM\Doctrine\Populator;

class AssertDatabaseCountTest extends AbstractDoctrineTest
{
    public const VFS_NAMESPACE = 'Vfs\\Single\\';

    use DoctrineAssertTrait;

    public function setUp(): void
    {
        parent::setUp();
    }

    protected function getVfsPath(): string
    {
        return __DIR__ . '/Vfs';
    }

    public function testSettingNoQueryConfigReturnsAllResults(): void
    {
        $this->createSettingNoQueryConfigReturnsAllResultsFixtures();

        $this->assertDatabaseCount(
            100,
            self::VFS_NAMESPACE . 'Thing',
            []
        );
    }

    private function createSettingNoQueryConfigReturnsAllResultsFixtures(): void
    {
        $generator = Factory::create();
        $populator = new Populator($generator, $this->getEntityManager());

        $populator->addEntity(self::VFS_NAMESPACE . 'Thing', 100);
        $populator->execute();
    }

    public function testSettingSingleQueryConfigConstraintReturnsCorrectCount(): void
    {
        $this->createSettingSingleQueryConfigConstraintReturnsCorrectCountFixtures();

        $this->assertDatabaseCount(
            50,
            self::VFS_NAMESPACE . 'Thing',
            [
                'active' => true
            ]
        );

        $this->assertDatabaseCount(
            50,
            self::VFS_NAMESPACE . 'Thing',
            [
                'active' => false
            ]
        );
    }

    private function createSettingSingleQueryConfigConstraintReturnsCorrectCountFixtures(): void
    {
        $generator = Factory::create();
        $populator = new Populator($generator, $this->getEntityManager());

        $populator->addEntity(self::VFS_NAMESPACE . 'Thing', 50, [
            'active' => function() { return false; }
        ]);
        $populator->execute();

        $populator->addEntity(self::VFS_NAMESPACE . 'Thing', 50, [
            'active' => function() { return true; }
        ]);
        $populator->execute();
    }

    public function testSettingDoubleQueryConfigConstraintReturnsCorrectCount(): void
    {
        $this->createSettingDoubleQueryConfigConstraintReturnsCorrectCountFixtures();

        $this->assertDatabaseCount(
            50,
            self::VFS_NAMESPACE . 'Thing',
            [
                'active' => true,
                'name'   => 'Aomame'
            ]
        );

        $this->assertDatabaseCount(
            50,
            self::VFS_NAMESPACE . 'Thing',
            [
                'active' => true,
                'name'   => 'Tamaru'
            ]
        );

        $this->assertDatabaseCount(
            50,
            self::VFS_NAMESPACE . 'Thing',
            [
                'active' => false,
                'name'   => 'Tengo'
            ]
        );

        $this->assertDatabaseCount(
            50,
            self::VFS_NAMESPACE . 'Thing',
            [
                'active' => false,
                'name'   => 'Eriko'
            ]
        );
    }

    private function createSettingDoubleQueryConfigConstraintReturnsCorrectCountFixtures(): void
    {
        $generator = Factory::create();
        $populator = new Populator($generator, $this->getEntityManager());

        $populator->addEntity(self::VFS_NAMESPACE . 'Thing', 50, [
            'active' => function() { return true; },
            'name'   => function() { return 'Aomame'; }
        ]);
        $populator->execute();

        $populator->addEntity(self::VFS_NAMESPACE . 'Thing', 50, [
            'active' => function() { return true; },
            'name'   => function() { return 'Tamaru'; }
        ]);
        $populator->execute();

        $populator->addEntity(self::VFS_NAMESPACE . 'Thing', 50, [
            'active' => function() { return false; },
            'name'   => function() { return 'Tengo'; }
        ]);
        $populator->execute();

        $populator->addEntity(self::VFS_NAMESPACE . 'Thing', 50, [
            'active' => function() { return false; },
            'name'   => function() { return 'Eriko'; }
        ]);
        $populator->execute();
    }
}