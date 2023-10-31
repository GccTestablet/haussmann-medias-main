<?php

declare(strict_types=1);

namespace App\Repository\Work;

use App\Enum\Pager\ColumnEnum;
use App\Pager\Shared\PagerInterface;
use App\Repository\Shared\PagerRepositoryInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class WorkReversionRepository extends EntityRepository implements PagerRepositoryInterface
{
    public function getPagerQueryBuilder(array $criteria, array $orderBy, ?int $limit = PagerInterface::DEFAULT_LIMIT, int $offset = PagerInterface::DEFAULT_OFFSET): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('wr');

        foreach ($criteria as $field => $value) {
            $enum = ColumnEnum::tryFrom($field);

            match ($enum) {
                ColumnEnum::WORK => $queryBuilder
                    ->andWhere('wr.work = :work')
                    ->setParameter('work', $value),
                default => null,
            };
        }

        foreach ($orderBy as $field => $direction) {
            $enum = ColumnEnum::tryFrom($field);

            match ($enum) {
                ColumnEnum::CHANNEL => $queryBuilder->orderBy('wr.channel', $direction),
                ColumnEnum::PERCENT_REVERSION => $queryBuilder->orderBy('wr.percentageReversion', $direction),
                default => null,
            };
        }

        return $queryBuilder
            ->setMaxResults($limit)
            ->setFirstResult($offset)
        ;
    }
}
