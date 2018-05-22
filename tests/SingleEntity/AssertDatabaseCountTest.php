<?php declare(strict_types=1);

namespace BenRowan\DoctrineAssert\Tests;

use BenRowan\DoctrineAssert\DoctrineAssertTrait;

class AssertDatabaseCountTest extends AbstractDoctrineTest
{
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

    }
}