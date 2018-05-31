<?php declare(strict_types=1);

namespace BenRowan\DoctrineAssert\Tests\ValueBeforeJoin;

use BenRowan\DoctrineAssert\DoctrineAssertTrait;
use BenRowan\DoctrineAssert\Tests\AbstractDoctrineTest;
use Faker\Factory;
use Faker\ORM\Doctrine\Populator;

class AssertDatabaseCountTest extends AbstractDoctrineTest
{
    public const VFS_NAMESPACE = 'Vfs\\ValueBeforeJoin\\';

    use DoctrineAssertTrait;

    public function setUp(): void
    {
        parent::setUp();
    }

    protected function getVfsPath(): string
    {
        return __DIR__ . '/Vfs';
    }

    public function testSettingValueBeforeJoinGivesCorrectResult(): void
    {
        $generator = Factory::create();
        $populator = new Populator($generator, $this->getEntityManager());

        $populator->addEntity(self::VFS_NAMESPACE . 'One', 50, [
            'name' => 'One'
        ]);
        $populator->addEntity(self::VFS_NAMESPACE . 'Two', 50, [
            'active' => true
        ]);
        $populator->execute();

//        $populator->addEntity(self::VFS_NAMESPACE . 'Two', 50, [
//            'active' => false
//        ]);
//        $populator->addEntity(self::VFS_NAMESPACE . 'One', 50);
//        $populator->execute();

//        $populator->addEntity(self::VFS_NAMESPACE . 'Two', 50);
//        $populator->addEntity(self::VFS_NAMESPACE . 'One', 50);
//        $populator->execute();

        $this->assertDatabaseCount(
            50,
            self::VFS_NAMESPACE . 'One',
            [
                'name' => 'One',
                self::VFS_NAMESPACE . 'Two' => [
                    'active' => true
                ]
            ]
        );

//        $this->assertDatabaseCount(
//            50,
//            self::VFS_NAMESPACE . 'One',
//            [
//                self::VFS_NAMESPACE . 'Two' => [
//                    'active' => false
//                ]
//            ]
//        );
    }

//    public function testSettingJoinBeforeValueGivesCorrectResult(): void
//    {
//        $generator = Factory::create();
//        $populator = new Populator($generator, $this->getEntityManager());
//
//        $populator->addEntity(self::VFS_NAMESPACE . 'Two', 50, [
//            'active' => true
//        ]);
//        $populator->addEntity(self::VFS_NAMESPACE . 'One', 50);
//        $populator->execute();
//
//        $populator->addEntity(self::VFS_NAMESPACE . 'Two', 50, [
//            'active' => false
//        ]);
//        $populator->addEntity(self::VFS_NAMESPACE . 'One', 50);
//        $populator->execute();
//
//        $this->assertDatabaseCount(
//            50,
//            self::VFS_NAMESPACE . 'One',
//            [
//                self::VFS_NAMESPACE . 'Two' => [
//                    'active' => true
//                ]
//            ]
//        );
//
//        $this->assertDatabaseCount(
//            50,
//            self::VFS_NAMESPACE . 'One',
//            [
//                self::VFS_NAMESPACE . 'Two' => [
//                    'active' => false
//                ]
//            ]
//        );
//    }
}