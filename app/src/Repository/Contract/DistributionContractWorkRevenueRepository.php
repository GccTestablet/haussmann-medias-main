<?php

declare(strict_types=1);

namespace App\Repository\Contract;

use App\Enum\Pager\ColumnEnum;
use App\Pager\Shared\PagerInterface;
use App\Repository\Shared\PagerRepositoryInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class DistributionContractWorkRevenueRepository extends EntityRepository implements PagerRepositoryInterface
{
    public function getPagerQueryBuilder(array $criteria, array $orderBy, ?int $limit = PagerInterface::DEFAULT_LIMIT, int $offset = PagerInterface::DEFAULT_OFFSET): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('dcwr')
            ->innerJoin('dcwr.contractWork', 'cw')
            ->innerJoin('cw.work', 'w')
            ->innerJoin('dcwr.broadcastChannel', 'bc')
        ;

        foreach ($criteria as $field => $value) {
            match ($field) {
                ColumnEnum::DISTRIBUTION_CONTRACT => $queryBuilder
                    ->andWhere('cw.distributionContract = :distributionContract')
                    ->setParameter('distributionContract', $value),
                ColumnEnum::WORKS => $queryBuilder
                    ->andWhere('w IN (:works)')
                    ->setParameter('works', $value),
                ColumnEnum::CHANNELS => $queryBuilder
                    ->andWhere('bc IN (:channels)')
                    ->setParameter('channels', $value),
                ColumnEnum::ENDS_AT => $queryBuilder
                    ->andWhere('dcwr.endsAt BETWEEN :fromEndsAt AND :toEndsAt')
                    ->setParameter('fromEndsAt', $value->getFrom()->setTime(0, 0, 0))
                    ->setParameter('toEndsAt', $value->getTo()->setTime(23, 59, 59)),
                default => null,
            };
        }

        foreach ($orderBy as $field => $direction) {
            match ($field) {
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

    /**
     * @param array<string, mixed> $criteria
     * @param array<string, string> $orderBy
     *
     * @return array<string, string>
     */
    public function getFilteredSumByCurrency(array $criteria, array $orderBy): array
    {
        $result = $this->getPagerQueryBuilder($criteria, $orderBy, null, 0)
            ->select('SUM(dcwr.amount) as sum')
            ->addSelect('dcwr.currency')
            ->groupBy('dcwr.currency')
            ->orderBy('sum', 'DESC')
            ->getQuery()
            ->getArrayResult()
        ;

        return \array_column(
            $result,
            'sum',
            'currency'
        );
    }
}
