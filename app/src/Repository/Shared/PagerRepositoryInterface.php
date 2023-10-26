<?php

declare(strict_types=1);

namespace App\Repository\Shared;

use Doctrine\ORM\QueryBuilder;

interface PagerRepositoryInterface
{
    /**
     * @param array<string, mixed> $criteria
     * @param array<string, string> $orderBy
     */
    public function getPagerQueryBuilder(array $criteria, array $orderBy, int $limit, int $offset): QueryBuilder;
}
