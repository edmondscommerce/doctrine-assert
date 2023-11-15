<?php declare(strict_types=1);

namespace BenRowan\DoctrineAssert\Constraints;

use BenRowan\DoctrineAssert\Config\QueryConfigIterator;
use Doctrine\ORM\EntityManagerInterface;

class DatabaseCount extends AbstractDatabaseConstraint
{
    private readonly string $queryConfigJson;

    private ?int $resultCount = null;

    public function __construct(
        EntityManagerInterface $entityManager,
        private readonly QueryConfigIterator $queryConfig,
        private readonly int $count
    ) {
        parent::__construct($entityManager);
        $this->queryConfigJson = $queryConfig->toJson();
    }

    /**
     * Check if the data is found in the given table.
     *
     * @param  string $rootEntityFqn
     * @return bool
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function matches($rootEntityFqn): bool
    {
        $rootAlias = $this->fqnToAlias($rootEntityFqn);

        $this->addCountSelect($rootEntityFqn, $rootAlias);

        $this->buildQuery(
            $this->queryConfig,
            $rootEntityFqn,
            $rootAlias
        );

        $this->resultCount = $this->resultCount();

        return $this->count === $this->resultCount;
    }

    /**
     * Returns a description of the failure.
     *
     * @param  string  $rootEntityFqn
     * @return string
     */
    public function failureDescription($rootEntityFqn): string
    {
        return sprintf(
            "expected count %d matches actual count %d for '%s'.\n\nQuery config:\n\n%s\n\n",
            $this->count,
            $this->resultCount,
            $rootEntityFqn,
            $this->toString()
        );
    }

    /**
     * Returns a string representation of the initial query config.
     *
     * @return string
     */
    public function toString(): string
    {
        return $this->queryConfigJson;
    }
}
