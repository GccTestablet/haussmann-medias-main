<?php

declare(strict_types=1);

namespace App\Repository\Work;

use App\Enum\Pager\ColumnEnum;
use App\Pager\Shared\PagerInterface;
use App\Repository\Shared\PagerRepositoryInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class WorkTerritoryRepository extends EntityRepository implements PagerRepositoryInterface
{
    public function getPagerQueryBuilder(array $criteria, array $orderBy, ?int $limit = PagerInterface::DEFAULT_LIMIT, int $offset = PagerInterface::DEFAULT_OFFSET): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('wt')
            ->innerJoin('wt.territory', 't')
            ->orderBy('t.archived', 'ASC')
        ;

        foreach ($criteria as $field => $value) {
            match ($field) {
                ColumnEnum::WORK => $queryBuilder
                    ->andWhere('wt.work = :work')
                    ->setParameter('work', $value),
                default => null,
            };
        }

        foreach ($orderBy as $field => $direction) {
            match ($field) {
                ColumnEnum::TERRITORY => $queryBuilder->addOrderBy('t.name', $direction),
                ColumnEnum::EXCLUSIVE => $queryBuilder->addOrderBy('wt.exclusive', $direction),
                default => null,
            };
        }

        return $queryBuilder
            ->setMaxResults($limit)
            ->setFirstResult($offset)
        ;
    }
}
