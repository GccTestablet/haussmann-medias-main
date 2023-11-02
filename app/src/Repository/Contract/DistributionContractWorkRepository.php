<?php

declare(strict_types=1);

namespace App\Repository\Contract;

use App\Enum\Pager\ColumnEnum;
use App\Pager\Shared\PagerInterface;
use App\Repository\Shared\PagerRepositoryInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class DistributionContractWorkRepository extends EntityRepository implements PagerRepositoryInterface
{
    public function getPagerQueryBuilder(array $criteria, array $orderBy, ?int $limit = PagerInterface::DEFAULT_LIMIT, int $offset = PagerInterface::DEFAULT_OFFSET): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('dcw')
            ->leftJoin('dcw.work', 'w')
        ;

        foreach ($criteria as $field => $value) {
            match ($field) {
                ColumnEnum::DISTRIBUTION_CONTRACT => $queryBuilder
                    ->andWhere('dcw.distributionContract = :distributionContract')
                    ->setParameter('distributionContract', $value),
                default => null,
            };
        }

        foreach ($orderBy as $field => $direction) {
            match ($field) {
                ColumnEnum::WORK => $queryBuilder->orderBy('w.name', $direction),
                ColumnEnum::PERIOD => $queryBuilder->orderBy('dcw.startsAt', $direction),
                ColumnEnum::AMOUNT => $queryBuilder->orderBy('dcw.amount', $direction),
                default => null,
            };
        }

        return $queryBuilder
            ->setFirstResult($offset)
            ->setMaxResults($limit)
        ;
    }
}
