<?php declare(strict_types=1);

namespace BenRowan\DoctrineAssert\Constraints;

use BenRowan\DoctrineAssert\Config\QueryConfigIterator;
use Doctrine\ORM\EntityManager;

class DatabaseHas extends AbstractDatabaseConstraint
{
    /**
     * @var QueryConfigIterator
     */
    private $queryConfig;

    /**
     * @var string
     */
    private $queryConfigJson;

    public function __construct(
        EntityManager $entityManager,
        QueryConfigIterator $queryConfig
    ) {
        parent::__construct($entityManager);

        $this->queryConfig     = $queryConfig;
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

        return 0 !== $this->resultCount();
    }

    /**
     * Returns a description of the failure.
     *
     * @param  string  $table
     * @return string
     */
    public function failureDescription($table): string
    {
        return "FAIL - $table";
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