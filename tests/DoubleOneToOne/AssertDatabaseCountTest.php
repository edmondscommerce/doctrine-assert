<?php declare(strict_types=1);

namespace BenRowan\DoctrineAssert\Tests\DoubleOneToOne;

use BenRowan\DoctrineAssert\DoctrineAssertTrait;
use BenRowan\DoctrineAssert\Tests\AbstractDoctrineTest;
use Faker\Factory;
use Faker\ORM\Doctrine\Populator;

class AssertDatabaseCountTest extends AbstractDoctrineTest
{
    public const VFS_NAMESPACE = 'Vfs\\DoubleOneToOne\\';

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

        $populator->addEntity(self::VFS_NAMESPACE . 'Three', 100);
        $populator->addEntity(self::VFS_NAMESPACE . 'Two', 100);
        $populator->addEntity(self::VFS_NAMESPACE . 'One', 100);

        $populator->execute();
    }

    public function testSettingSingleQueryConfigConstraintOnThirdEntityReturnsCorrectCount(): void
    {
        $this->createSettingSingleQueryConfigConstraintOnThirdEntityReturnsCorrectCountFixtures();

        $this->assertDatabaseCount(
            50,
            self::VFS_NAMESPACE . 'One',
            [
                self::VFS_NAMESPACE . 'Two' => [
                    self::VFS_NAMESPACE . 'Three' => [
                        'active' => true
                    ]
                ]
            ]
        );

        $this->assertDatabaseCount(
            50,
            self::VFS_NAMESPACE . 'One',
            [
                self::VFS_NAMESPACE . 'Two' => [
                    self::VFS_NAMESPACE . 'Three' => [
                        'active' => false
                    ]
                ]
            ]
        );
    }

    private function createSettingSingleQueryConfigConstraintOnThirdEntityReturnsCorrectCountFixtures(): void
    {
        $generator = Factory::create();
        $populator = new Populator($generator, $this->getEntityManager());

        $populator->addEntity(self::VFS_NAMESPACE . 'Three', 50, [
            'active' => function() { return true; }
        ]);
        $populator->addEntity(self::VFS_NAMESPACE . 'Two', 50);
        $populator->addEntity(self::VFS_NAMESPACE . 'One', 50);
        $populator->execute();

        $populator->addEntity(self::VFS_NAMESPACE . 'Three', 50, [
            'active' => function() { return false; }
        ]);
        $populator->addEntity(self::VFS_NAMESPACE . 'Two', 50);
        $populator->addEntity(self::VFS_NAMESPACE . 'One', 50);
        $populator->execute();
    }

    public function testSettingDoubleQueryConfigConstraintOnThirdEntityReturnsCorrectCount(): void
    {
        $this->createSettingDoubleQueryConfigConstraintOnThirdEntityReturnsCorrectCountFixtures();

        $this->assertDatabaseCount(
            50,
            self::VFS_NAMESPACE . 'One',
            [
                self::VFS_NAMESPACE . 'Two' => [
                    self::VFS_NAMESPACE . 'Three' => [
                        'active' => true,
                        'name'   => 'Aomame'
                    ]
                ]
            ]
        );

        $this->assertDatabaseCount(
            50,
            self::VFS_NAMESPACE . 'One',
            [
                self::VFS_NAMESPACE . 'Two' => [
                    self::VFS_NAMESPACE . 'Three' => [
                        'active' => true,
                        'name'   => 'Tamaru'
                    ]
                ]
            ]
        );

        $this->assertDatabaseCount(
            50,
            self::VFS_NAMESPACE . 'One',
            [
                self::VFS_NAMESPACE . 'Two' => [
                    self::VFS_NAMESPACE . 'Three' => [
                        'active' => false,
                        'name'   => 'Tengo'
                    ]
                ]
            ]
        );

        $this->assertDatabaseCount(
            50,
            self::VFS_NAMESPACE . 'One',
            [
                self::VFS_NAMESPACE . 'Two' => [
                    self::VFS_NAMESPACE . 'Three' => [
                        'active' => false,
                        'name'   => 'Eriko'
                    ]
                ]
            ]
        );
    }

    private function createSettingDoubleQueryConfigConstraintOnThirdEntityReturnsCorrectCountFixtures(): void
    {
        $generator = Factory::create();
        $populator = new Populator($generator, $this->getEntityManager());

        $populator->addEntity(self::VFS_NAMESPACE . 'Three', 50, [
            'active' => function() { return true; },
            'name'   => function() { return 'Aomame'; }
        ]);
        $populator->addEntity(self::VFS_NAMESPACE . 'Two', 50);
        $populator->addEntity(self::VFS_NAMESPACE . 'One', 50);
        $populator->execute();

        $populator->addEntity(self::VFS_NAMESPACE . 'Three', 50, [
            'active' => function() { return true; },
            'name'   => function() { return 'Tamaru'; }
        ]);
        $populator->addEntity(self::VFS_NAMESPACE . 'Two', 50);
        $populator->addEntity(self::VFS_NAMESPACE . 'One', 50);
        $populator->execute();

        $populator->addEntity(self::VFS_NAMESPACE . 'Three', 50, [
            'active' => function() { return false; },
            'name'   => function() { return 'Tengo'; }
        ]);
        $populator->addEntity(self::VFS_NAMESPACE . 'Two', 50);
        $populator->addEntity(self::VFS_NAMESPACE . 'One', 50);
        $populator->execute();

        $populator->addEntity(self::VFS_NAMESPACE . 'Three', 50, [
            'active' => function() { return false; },
            'name'   => function() { return 'Eriko'; }
        ]);
        $populator->addEntity(self::VFS_NAMESPACE . 'Two', 50);
        $populator->addEntity(self::VFS_NAMESPACE . 'One', 50);
        $populator->execute();
    }
}