<?php declare(strict_types=1);

namespace BenRowan\DoctrineAssert;

use BenRowan\DoctrineAssert\Constraints\DatabaseCount;
use BenRowan\DoctrineAssert\Constraints\DatabaseHas;
use PHPUnit\Framework\Constraint\LogicalNot;
use PHPUnit\Framework\TestCase;

trait DoctrineAssertTrait
{
    /**
     * Assert that the database has one or more entities with this data.
     *
     * @param string $rootEntityFqn
     * @param array $queryConfig
     *
     * @return $this
     */
    public function assertDatabaseHas(string $rootEntityFqn, array $queryConfig): self
    {
        $constraint = new DatabaseHas($this->getEntityManager(), $queryConfig);

        /* @var TestCase $this */
        $this->assertThat(
            $rootEntityFqn,
            $constraint
        );

        return $this;
    }

    /**
     * Asset that the database has no entities with this data.
     *
     * @param string $rootEntityFqn
     * @param array $queryConfig
     *
     * @return $this
     */
    public function assertDatabaseMissing(string $rootEntityFqn, array $queryConfig): self
    {
        $constraint = new LogicalNot(
            new DatabaseHas($this->getEntityManager(), $queryConfig)
        );

        /* @var TestCase $this */
        $this->assertThat(
            $rootEntityFqn,
            $constraint
        );

        return $this;
    }

    /**
     * Assert that the database has exactly $count entities with this data.
     *
     * @param string $rootEntityFqn
     * @param array $queryConfig
     *
     * @return $this
     */
    public function assertDatabaseCount(int $count, string $rootEntityFqn, array $queryConfig): self
    {
        $constraint = new DatabaseCount($this->getEntityManager(), $queryConfig, $count);

        /* @var TestCase $this */
        $this->assertThat(
            $rootEntityFqn,
            $constraint
        );

        return $this;
    }
}