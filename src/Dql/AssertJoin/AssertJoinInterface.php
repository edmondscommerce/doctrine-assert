<?php declare(strict_types=1);

namespace BenRowan\DoctrineAssert\Dql\AssertJoin;

interface AssertJoinInterface
{
    public function add(
        string $childEntityFqn,
        string $childAlias,
        string $parentEntityFqn,
        string $parentAlias
    ): void;
}