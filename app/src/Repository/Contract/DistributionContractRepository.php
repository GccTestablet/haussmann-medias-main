<?php

declare(strict_types=1);

namespace App\Repository\Contract;

use App\Enum\Pager\ColumnEnum;
use App\Pager\Shared\PagerInterface;
use App\Repository\Shared\PagerRepositoryInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class DistributionContractRepository extends EntityRepository implements PagerRepositoryInterface
{
    public function getPagerQueryBuilder(array $criteria, array $orderBy, ?int $limit = PagerInterface::DEFAULT_LIMIT, int $offset = PagerInterface::DEFAULT_OFFSET): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('dc')
            ->innerJoin('dc.distributor', 'd')
            ->leftJoin('dc.contractWorks', 'cw')
            ->leftJoin('cw.work', 'w')
            ->leftJoin('dc.broadcastChannels', 'bc')
            ->groupBy('dc')
            ->addGroupBy('d')
        ;

        foreach ($criteria as $field => $value) {
            $enum = ColumnEnum::tryFrom($field);

            match ($enum) {
                ColumnEnum::NAME => $queryBuilder
                    ->andWhere('LOWER(dc.name) LIKE LOWER(:name)')
                    ->setParameter('name', \sprintf('%%%s%%', $value)),
                ColumnEnum::COMPANY => $queryBuilder
                    ->andWhere('dc.company = :company')
                    ->setParameter('company', $value),
                ColumnEnum::DISTRIBUTORS => $queryBuilder
                    ->andWhere('dc.distributor IN (:distributors)')
                    ->setParameter('distributors', $value),
                ColumnEnum::WORKS => $queryBuilder
                    ->andWhere('w IN (:works)')
                    ->setParameter('works', $value),
                ColumnEnum::CHANNELS => $queryBuilder
                    ->andWhere('bc IN (:broadcastChannels)')
                    ->setParameter('broadcastChannels', $value),
                ColumnEnum::SIGNED_AT => $queryBuilder
                    ->andWhere('dc.signedAt BETWEEN :signedAtFrom AND :signedAtTo')
                    ->setParameter('signedAtFrom', $value->getFrom())
                    ->setParameter('signedAtTo', $value->getTo()),
                default => null,
            };
        }

        foreach ($orderBy as $field => $direction) {
            $enum = ColumnEnum::tryFrom($field);
            match ($enum) {
                ColumnEnum::NAME => $queryBuilder->orderBy('dc.name', $direction),
                ColumnEnum::DISTRIBUTOR => $queryBuilder->orderBy('d.name', $direction),
                ColumnEnum::SIGNED_AT => $queryBuilder->orderBy('dc.signedAt', $direction),
                default => null,
            };
        }

        return $queryBuilder
            ->setFirstResult($offset)
            ->setMaxResults($limit)
        ;
    }
}
