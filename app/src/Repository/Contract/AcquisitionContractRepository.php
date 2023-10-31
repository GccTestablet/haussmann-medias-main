<?php

declare(strict_types=1);

namespace App\Repository\Contract;

use App\Enum\Pager\ColumnEnum;
use App\Pager\Shared\PagerInterface;
use App\Repository\Shared\PagerRepositoryInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class AcquisitionContractRepository extends EntityRepository implements PagerRepositoryInterface
{
    public function getPagerQueryBuilder(array $criteria, array $orderBy, ?int $limit = PagerInterface::DEFAULT_LIMIT, int $offset = PagerInterface::DEFAULT_OFFSET): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('ac')
            ->innerJoin('ac.beneficiary', 'b')
            ->leftJoin('ac.works', 'w')
            ->leftJoin('w.workTerritories', 'wt')
            ->groupBy('ac')
            ->addGroupBy('b')
        ;

        foreach ($criteria as $field => $value) {
            $enum = ColumnEnum::tryFrom($field);

            match ($enum) {
                ColumnEnum::NAME => $queryBuilder
                    ->andWhere('LOWER(ac.name) LIKE LOWER(:name)')
                    ->setParameter('name', \sprintf('%%%s%%', $value)),
                ColumnEnum::COMPANY => $queryBuilder
                    ->andWhere('ac.company = :company OR ac.beneficiary = :company')
                    ->setParameter('company', $value),
                ColumnEnum::BENEFICIARIES => $queryBuilder
                    ->andWhere('ac.beneficiary IN (:beneficiaries)')
                    ->setParameter('beneficiaries', $value),
                ColumnEnum::WORKS => $queryBuilder
                    ->andWhere('w IN (:works)')
                    ->setParameter('works', $value),
                ColumnEnum::TERRITORIES => $queryBuilder
                    ->andWhere('wt.territory IN (:territories)')
                    ->setParameter('territories', $value),
                ColumnEnum::SIGNED_AT => $queryBuilder
                    ->andWhere('ac.signedAt BETWEEN :signedAtFrom AND :signedAtTo')
                    ->setParameter('signedAtFrom', $value->getFrom())
                    ->setParameter('signedAtTo', $value->getTo()),
                ColumnEnum::STARTS_AT => $queryBuilder
                    ->andWhere('ac.startsAt BETWEEN :startsAtFrom AND :startsAtTo')
                    ->setParameter('startsAtFrom', $value->getFrom())
                    ->setParameter('startsAtTo', $value->getTo()),
                ColumnEnum::ENDS_AT => $queryBuilder
                    ->andWhere('ac.endsAt BETWEEN :endsAtFrom AND :endsAtTo')
                    ->setParameter('endsAtFrom', $value->getFrom())
                    ->setParameter('endsAtTo', $value->getTo()),
                default => null,
            };
        }

        foreach ($orderBy as $field => $direction) {
            $enum = ColumnEnum::tryFrom($field);
            match ($enum) {
                ColumnEnum::NAME => $queryBuilder->orderBy('ac.name', $direction),
                ColumnEnum::BENEFICIARY => $queryBuilder->orderBy('b.name', $direction),
                ColumnEnum::SIGNED_AT => $queryBuilder->orderBy('ac.signedAt', $direction),
                ColumnEnum::PERIOD => $queryBuilder->orderBy('ac.startsAt', $direction),
                default => null,
            };
        }

        return $queryBuilder
            ->setFirstResult($offset)
            ->setMaxResults($limit)
        ;
    }

    public function getQueryBuilderBySearchQuery(string $query, int $limit): QueryBuilder
    {
        $orX = $this->getEntityManager()->getExpressionBuilder()->orX();

        $fields = ['ac.name', 'b.name', 'cm.name'];
        foreach ($fields as $field) {
            $orX->add(\sprintf('LOWER(%s) LIKE LOWER(:query)', $field));
        }

        return $this->createQueryBuilder('ac')
            ->innerJoin('ac.beneficiary', 'b')
            ->innerJoin('ac.company', 'cm')
            ->where($orX)
            ->setParameter('query', \sprintf('%%%s%%', $query))
            ->orderBy('ac.name', 'ASC')
            ->setFirstResult(0)
            ->setMaxResults($limit)
        ;
    }
}
