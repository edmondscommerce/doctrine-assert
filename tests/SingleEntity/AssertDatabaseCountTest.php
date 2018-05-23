<?php declare(strict_types=1);

namespace BenRowan\DoctrineAssert\Tests;

use BenRowan\DoctrineAssert\DoctrineAssertTrait;

class AssertDatabaseCountTest extends AbstractDoctrineTest
{
    public const VFS_NAMESPACE        = 'BenRowan\\DoctrineAssert\\Vfs\\';
    public const VFS_ENTITY_NAMESPACE = self::VFS_NAMESPACE . 'Entity\\';

    use DoctrineAssertTrait;

    public function setUp(): void
    {
        parent::setUp();
    }

    protected function getFixturePath(): string
    {
        return realpath(__DIR__ . '/fixture');
    }

    public function testAssertsCorrectCount(): void
    {
        $this->assertDatabaseCount(
            0,
            self::VFS_ENTITY_NAMESPACE . 'Thing',
            []
        );
    }
}