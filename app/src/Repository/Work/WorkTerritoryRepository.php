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
        ;

        foreach ($criteria as $field => $value) {
            $enum = ColumnEnum::tryFrom($field);

            match ($enum) {
                ColumnEnum::WORK => $queryBuilder
                    ->andWhere('wt.work = :work')
                    ->setParameter('work', $value),
                default => null,
            };
        }

        foreach ($orderBy as $field => $direction) {
            $enum = ColumnEnum::tryFrom($field);

            match ($enum) {
                ColumnEnum::TERRITORY => $queryBuilder->orderBy('t.name', $direction),
                ColumnEnum::EXCLUSIVE => $queryBuilder->orderBy('wt.exclusive', $direction),
                default => null,
            };
        }

        return $queryBuilder
            ->setMaxResults($limit)
            ->setFirstResult($offset)
        ;
    }
}
