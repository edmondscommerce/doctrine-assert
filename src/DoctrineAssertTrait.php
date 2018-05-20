<?php declare(strict_types=1);

namespace BenRowan\DoctrineAssert;

use BenRowan\DoctrineAssert\Constraints\DatabaseCount;
use BenRowan\DoctrineAssert\Constraints\DatabaseHas;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\Constraint\LogicalNot;
use PHPUnit\Framework\TestCase;

trait DoctrineAssertTrait
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * Set the entity manager to be used by Doctrine Assert.
     *
     * @param EntityManager $entityManager
     */
    protected function setEntityManager(EntityManager $entityManager): void
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Get the current entity manager.
     *
     * @return EntityManager
     */
    protected function getEntityManager(): EntityManager
    {
        return $this->entityManager;
    }

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
        /* @var TestCase $this */

        $constraint = new DatabaseHas($this->getEntityManager(), $queryConfig);

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
        /* @var TestCase $this */

        $constraint = new LogicalNot(
            new DatabaseHas($this->getEntityManager(), $queryConfig)
        );

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
        /* @var TestCase $this */

        $constraint = new DatabaseCount($this->getEntityManager(), $queryConfig, $count);

        $this->assertThat(
            $rootEntityFqn,
            $constraint
        );

        return $this;
    }
}