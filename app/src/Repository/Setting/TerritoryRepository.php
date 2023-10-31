<?php

declare(strict_types=1);

namespace App\Repository\Setting;

use App\Enum\Pager\ColumnEnum;
use App\Pager\Shared\PagerInterface;
use App\Repository\Shared\PagerRepositoryInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class TerritoryRepository extends EntityRepository implements PagerRepositoryInterface
{
    public function getPagerQueryBuilder(array $criteria, array $orderBy, ?int $limit = PagerInterface::DEFAULT_LIMIT, int $offset = PagerInterface::DEFAULT_OFFSET): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('t')
            ->leftJoin('t.distributionContractWorkTerritories', 'dcwt')
            ->leftJoin('dcwt.contractWork', 'dcw')
            ->leftJoin('dcw.distributionContract', 'dc')
        ;

        foreach ($criteria as $field => $value) {
            $enum = ColumnEnum::tryFrom($field);
            match ($enum) {
                ColumnEnum::DISTRIBUTION_CONTRACT => $queryBuilder
                    ->andWhere('dc = :distributionContract')
                    ->setParameter('distributionContract', $value),
                default => null,
            };
        }

        foreach ($orderBy as $field => $direction) {
            $enum = ColumnEnum::tryFrom($field);
            match ($enum) {
                ColumnEnum::NAME => $queryBuilder->addOrderBy('t.name', $direction),
                default => null,
            };
        }

        return $queryBuilder
            ->setFirstResult($offset)
            ->setMaxResults($limit)
        ;
    }
}
