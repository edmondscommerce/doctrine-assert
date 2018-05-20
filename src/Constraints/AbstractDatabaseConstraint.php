<?php declare(strict_types=1);

namespace BenRowan\DoctrineAssert\Constraints;

use BenRowan\DoctrineAssert\Config\QueryConfigIterator;
use BenRowan\DoctrineAssert\Exceptions\DoctrineAssertException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use PHPUnit\Framework\Constraint\Constraint;

abstract class AbstractDatabaseConstraint extends Constraint
{
    /**
     * @var QueryBuilder
     */
    private $queryBuilder;

    /**
     * @var EntityManager
     */
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        parent::__construct();

        $this->entityManager = $entityManager;
        $this->queryBuilder  = $entityManager->createQueryBuilder();
    }

    /**
     * @return QueryBuilder
     */
    protected function getQueryBuilder(): QueryBuilder
    {
        return $this->queryBuilder;
    }

    /**
     * @return EntityManager
     */
    protected function getEntityManager(): EntityManager
    {
        return $this->entityManager;
    }

    protected function addCountSelect(string $rootEntityFqn, string $rootAlias): void
    {
        $this->getQueryBuilder()
            ->select(
                $this->getQueryBuilder()->expr()->count($rootAlias)
            )
            ->from($rootEntityFqn, $rootAlias);
    }

    private function addJoin(
        string $childEntityFqn,
        string $childAlias,
        string $parentEntityFqn,
        string $parentAlias
    ): void {

        $parentMetaData = $this->getEntityManager()->getClassMetadata($parentEntityFqn);

        foreach ($parentMetaData->getAssociationNames() as $associationName) {
            $targetClass = $parentMetaData->getAssociationTargetClass($associationName);

            if ($childEntityFqn === $targetClass) {
                $this->getQueryBuilder()
                    ->join(
                        $childEntityFqn,
                        $childAlias,
                        Join::WITH,
                        "$childAlias = $parentAlias.$associationName"
                    );

                return;
            }
        }

        throw new DoctrineAssertException(
            "No association found between '$childEntityFqn' and '$parentEntityFqn'"
        );
    }

    private function addWhere($value, string $field, string $alias): void
    {
        $this->getQueryBuilder()
            ->andWhere(
                $this->getQueryBuilder()->expr()->eq("$alias.$field", ":$field")
            )
            ->setParameter($field, $value);
    }

    /**
     * Converts an entity fully qualified name (FQN) into a DQL alias.
     *
     * @param string $fqn
     * @return mixed
     */
    private function fqnToAlias(string $fqn)
    {
        return str_replace(
            '\\',
            '_',
            $fqn
        );
    }

    private function buildChildQuery(
        QueryConfigIterator $queryConfig,
        string $childEntityFqn,
        string $parentEntityFqn,
        string $parentAlias
    ): void {

        $childAlias = $this->fqnToAlias($childEntityFqn);

        $this->addJoin(
            $childEntityFqn,
            $childAlias,
            $parentEntityFqn,
            $parentAlias
        );

        $this->buildQuery(
            $queryConfig,
            $childEntityFqn,
            $childAlias
        );
    }

    protected function buildQuery(
        QueryConfigIterator $queryConfig,
        string $currentEntityFqn,
        string $currentAlias
    ): void {

        if (0 === $queryConfig->count()) {
            return;
        }

        $queryConfig->next();

        if ($queryConfig->currentIsChildConfig()) {

            $this->buildChildQuery(
                $queryConfig->current(),
                $queryConfig->key(),
                $currentEntityFqn,
                $currentAlias
            );

            return;
        }

        $this->addWhere(
            $queryConfig->current(),
            $queryConfig->key(),
            $currentAlias
        );

        $this->buildQuery(
            $queryConfig,
            $currentEntityFqn,
            $currentAlias
        );
    }

    /**
     * The number of matches for the current query.
     *
     * @return int
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    protected function matchCount(): int
    {
        return (int) $this->getQueryBuilder()
            ->getQuery()
            ->getSingleScalarResult();
    }
}