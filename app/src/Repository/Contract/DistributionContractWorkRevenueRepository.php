<?php

declare(strict_types=1);

namespace App\Repository\Contract;

use App\Enum\Pager\ColumnEnum;
use App\Repository\Shared\PagerRepositoryInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class DistributionContractWorkRevenueRepository extends EntityRepository implements PagerRepositoryInterface
{
    public function getPagerQueryBuilder(array $criteria, array $orderBy, int $limit, int $offset): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('dcwr')
            ->innerJoin('dcwr.contractWork', 'cw')
            ->innerJoin('cw.work', 'w')
            ->innerJoin('dcwr.broadcastChannel', 'bc')
        ;

        foreach ($criteria as $field => $value) {
            $enum = ColumnEnum::tryFrom($field);

            match ($enum) {
                ColumnEnum::WORKS => $queryBuilder
                    ->andWhere('w IN (:works)')
                    ->setParameter('works', $value),
                ColumnEnum::CHANNELS => $queryBuilder
                    ->andWhere('bc IN (:channels)')
                    ->setParameter('channels', $value),
                ColumnEnum::STARTS_AT => $queryBuilder
                    ->andWhere('dcwr.startsAt BETWEEN :startsAtFrom AND :startsAtTo')
                    ->setParameter('startsAtFrom', $value->getFrom())
                    ->setParameter('startsAtTo', $value->getTo()),
                ColumnEnum::ENDS_AT => $queryBuilder
                    ->andWhere('dcwr.endsAt BETWEEN :endsAtFrom AND :endsAtTo')
                    ->setParameter('endsAtFrom', $value->getFrom())
                    ->setParameter('endsAtTo', $value->getTo()),
                default => null,
            };
        }

        foreach ($orderBy as $field => $direction) {
            $enum = ColumnEnum::tryFrom($field);

            match ($enum) {
                ColumnEnum::WORK => $queryBuilder->orderBy('w.name', $direction),
                ColumnEnum::CHANNEL => $queryBuilder->orderBy('bc.name', $direction),
                ColumnEnum::STARTS_AT => $queryBuilder->orderBy('dcwr.startsAt', $direction),
                ColumnEnum::ENDS_AT => $queryBuilder->orderBy('dcwr.endsAt', $direction),
                ColumnEnum::AMOUNT => $queryBuilder->orderBy('dcwr.amount', $direction),
                default => null,
            };
        }

        return $queryBuilder
            ->setFirstResult($offset)
            ->setMaxResults($limit)
        ;
    }

    public function getFilteredTotalAmount(QueryBuilder $queryBuilder): int
    {
        return (int) $queryBuilder
            ->resetDQLPart('orderBy')
            ->select('SUM(dcwr.amount) as total')
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }
}
