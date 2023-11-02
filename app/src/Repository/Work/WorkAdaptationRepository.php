<?php

declare(strict_types=1);

namespace App\Repository\Work;

use App\Enum\Pager\ColumnEnum;
use App\Pager\Shared\PagerInterface;
use App\Repository\Shared\PagerRepositoryInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class WorkAdaptationRepository extends EntityRepository implements PagerRepositoryInterface
{
    public function getPagerQueryBuilder(array $criteria, array $orderBy, ?int $limit = PagerInterface::DEFAULT_LIMIT, int $offset = PagerInterface::DEFAULT_OFFSET): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('wa');

        foreach ($criteria as $field => $value) {
            match ($field) {
                ColumnEnum::WORK => $queryBuilder
                    ->andWhere('wa.work = :work')
                    ->setParameter('work', $value),
                default => null,
            };
        }

        foreach ($orderBy as $field => $direction) {
            match ($field) {
                ColumnEnum::TYPE => $queryBuilder->orderBy('wa.type', $direction),
                ColumnEnum::AMOUNT => $queryBuilder->orderBy('wa.amount', $direction),
                default => null,
            };
        }

        return $queryBuilder
            ->setMaxResults($limit)
            ->setFirstResult($offset)
        ;
    }
}
