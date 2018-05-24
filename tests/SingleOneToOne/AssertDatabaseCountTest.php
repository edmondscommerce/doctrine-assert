<?php declare(strict_types=1);

namespace BenRowan\DoctrineAssert\Tests\SingleOneToOne;

use BenRowan\DoctrineAssert\DoctrineAssertTrait;
use BenRowan\DoctrineAssert\Tests\AbstractDoctrineTest;
use Faker\Factory;
use Faker\ORM\Doctrine\Populator;

class AssertDatabaseCountTest extends AbstractDoctrineTest
{
    public const VFS_NAMESPACE = 'Vfs\\SingleOneToOne\\';

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
            self::VFS_NAMESPACE . 'One',
            []
        );
    }

    private function createSettingNoQueryConfigReturnsAllResultsFixtures(): void
    {
        $generator = Factory::create();
        $populator = new Populator($generator, $this->getEntityManager());

        $populator->addEntity(self::VFS_NAMESPACE . 'One', 100);
        $populator->addEntity(self::VFS_NAMESPACE . 'Two', 100);
        $populator->execute();
    }

    public function testSettingSingleQueryConfigConstraintOnSecondEntityReturnsCorrectCount(): void
    {
        $this->createSettingSingleQueryConfigConstraintOnSecondEntityReturnsCorrectCountFixtures();

        $this->assertDatabaseCount(
            50,
            self::VFS_NAMESPACE . 'One',
            [
                self::VFS_NAMESPACE . 'Two' => [
                    'active' => true
                ]
            ]
        );

        $this->assertDatabaseCount(
            50,
            self::VFS_NAMESPACE . 'One',
            [
                self::VFS_NAMESPACE . 'Two' => [
                    'active' => false
                ]
            ]
        );
    }

    private function createSettingSingleQueryConfigConstraintOnSecondEntityReturnsCorrectCountFixtures(): void
    {
        $generator = Factory::create();
        $populator = new Populator($generator, $this->getEntityManager());

        $populator->addEntity(self::VFS_NAMESPACE . 'Two', 50, [
            'active' => function() { return true; }
        ]);
        $populator->addEntity(self::VFS_NAMESPACE . 'One', 50);
        $populator->execute();

        $populator->addEntity(self::VFS_NAMESPACE . 'Two', 50, [
            'active' => function() { return false; }
        ]);
        $populator->addEntity(self::VFS_NAMESPACE . 'One', 50);
        $populator->execute();
    }

    public function testSettingDoubleQueryConfigConstraintOnSecondEntityReturnsCorrectCount(): void
    {
        $this->createSettingDoubleQueryConfigConstraintOnSecondEntityReturnsCorrectCountFixtures();

        $this->assertDatabaseCount(
            50,
            self::VFS_NAMESPACE . 'One',
            [
                self::VFS_NAMESPACE . 'Two' => [
                    'active' => true,
                    'name'   => 'Aomame'
                ]
            ]
        );

        $this->assertDatabaseCount(
            50,
            self::VFS_NAMESPACE . 'One',
            [
                self::VFS_NAMESPACE . 'Two' => [
                    'active' => true,
                    'name'   => 'Tamaru'
                ]
            ]
        );

        $this->assertDatabaseCount(
            50,
            self::VFS_NAMESPACE . 'One',
            [
                self::VFS_NAMESPACE . 'Two' => [
                    'active' => false,
                    'name'   => 'Tengo'
                ]
            ]
        );

        $this->assertDatabaseCount(
            50,
            self::VFS_NAMESPACE . 'One',
            [
                self::VFS_NAMESPACE . 'Two' => [
                    'active' => false,
                    'name'   => 'Eriko'
                ]
            ]
        );
    }

    private function createSettingDoubleQueryConfigConstraintOnSecondEntityReturnsCorrectCountFixtures(): void
    {
        $generator = Factory::create();
        $populator = new Populator($generator, $this->getEntityManager());

        $populator->addEntity(self::VFS_NAMESPACE . 'Two', 50, [
            'active' => function() { return true; },
            'name'   => function() { return 'Aomame'; }
        ]);
        $populator->addEntity(self::VFS_NAMESPACE . 'One', 50);
        $populator->execute();

        $populator->addEntity(self::VFS_NAMESPACE . 'Two', 50, [
            'active' => function() { return true; },
            'name'   => function() { return 'Tamaru'; }
        ]);
        $populator->addEntity(self::VFS_NAMESPACE . 'One', 50);
        $populator->execute();

        $populator->addEntity(self::VFS_NAMESPACE . 'Two', 50, [
            'active' => function() { return false; },
            'name'   => function() { return 'Tengo'; }
        ]);
        $populator->addEntity(self::VFS_NAMESPACE . 'One', 50);
        $populator->execute();

        $populator->addEntity(self::VFS_NAMESPACE . 'Two', 50, [
            'active' => function() { return false; },
            'name'   => function() { return 'Eriko'; }
        ]);
        $populator->addEntity(self::VFS_NAMESPACE . 'One', 50);
        $populator->execute();
    }
}