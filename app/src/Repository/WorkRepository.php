<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Company;
use App\Entity\Contract\DistributionContract;
use App\Entity\Work\Work;
use App\Enum\Pager\ColumnEnum;
use App\Repository\Shared\PagerRepositoryInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class WorkRepository extends EntityRepository implements PagerRepositoryInterface
{
    public function getPagerQueryBuilder(array $criteria, array $orderBy, int $limit, int $offset): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('w')
            ->innerJoin('w.acquisitionContract', 'ac')
        ;

        foreach ($criteria as $field => $value) {
            $enum = ColumnEnum::tryFrom($field);
            if ($value instanceof Collection && 0 === $value->count()) {
                continue;
            }

            match ($enum) {
                ColumnEnum::INTERNAL_ID => $queryBuilder
                    ->andWhere('w.internalId LIKE :internalId')
                    ->setParameter('internalId', \sprintf('%%%s%%', $value)),
                ColumnEnum::NAME => $queryBuilder
                    ->andWhere('w.name LIKE :name OR w.originalName LIKE :name')
                    ->setParameter('name', \sprintf('%%%s%%', $value)),
                ColumnEnum::COMPANY => $queryBuilder
                    ->andWhere('ac.company = :company')
                    ->setParameter('company', $value),
                ColumnEnum::ACQUISITION_CONTRACT => $queryBuilder
                    ->andWhere('w.acquisitionContract = :acquisitionContract')
                    ->setParameter('acquisitionContract', $value),
                ColumnEnum::ACQUISITION_CONTRACT_NAME => $queryBuilder
                    ->andWhere('ac.name LIKE :acquisitionContractName')
                    ->setParameter('acquisitionContractName', \sprintf('%%%s%%', $value)),
                ColumnEnum::BENEFICIARY => $queryBuilder
                    ->andWhere('ac.beneficiary IN (:beneficiaries)')
                    ->setParameter('beneficiaries', $value),
                default => null,
            };
        }

        foreach ($orderBy as $field => $direction) {
            $enum = ColumnEnum::tryFrom($field);
            match ($enum) {
                ColumnEnum::INTERNAL_ID => $queryBuilder->addOrderBy('w.internalId', $direction),
                ColumnEnum::NAME => $queryBuilder->addOrderBy('w.name', $direction),
                ColumnEnum::ACQUISITION_CONTRACT => $queryBuilder->addOrderBy('ac.name', $direction),
                default => null,
            };
        }

        return $queryBuilder
            ->setFirstResult($offset)
            ->setMaxResults($limit)
        ;
    }

    public function findLastInternalId(string $prefix): ?string
    {
        return $this->createQueryBuilder('w')
            ->select('MAX(w.internalId)')
            ->where('w.internalId LIKE :prefix')
            ->setParameter('prefix', \sprintf('%s%%', $prefix))
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    /**
     * @return Work[]
     */
    public function findByCompany(Company $company): array
    {
        return $this->createQueryBuilder('w')
            ->innerJoin('w.acquisitionContract', 'ac')
            ->where('ac.company = :company')
            ->setParameter('company', $company)
            ->orderBy('w.name', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function getAvailableWorksByDistributionContractQueryBuilder(DistributionContract $distributionContract, Work $excludeWork = null): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('w')
            ->orderBy('w.internalId', 'ASC')
        ;

        $works = $distributionContract->getWorks();
        if (0 === $works->count()) {
            return $queryBuilder;
        }

        if ($excludeWork) {
            $works->removeElement($excludeWork);
        }

        return $queryBuilder
            ->where('w NOT IN (:works)')
            ->setParameter('works', $works)
        ;
    }

    /**
     * @return Work[]
     */
    public function findByDistributionContract(DistributionContract $distributionContract): array
    {
        return $this->createQueryBuilder('w')
            ->innerJoin('w.contractWorks', 'cw')
            ->where('cw.distributionContract = :contract')
            ->setParameter('contract', $distributionContract)
            ->orderBy('w.internalId', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function getQueryBuilderBySearchQuery(string $query, int $limit): QueryBuilder
    {
        $orX = $this->getEntityManager()->getExpressionBuilder()->orX();

        $fields = ['w.name', 'w.originalName', 'w.internalId', 'w.imdbId'];
        foreach ($fields as $field) {
            $orX->add(\sprintf('LOWER(%s) LIKE LOWER(:query)', $field));
        }

        return $this->createQueryBuilder('w')
            ->where($orX)
            ->setParameter('query', \sprintf('%%%s%%', $query))
            ->orderBy('w.name', 'ASC')
            ->setFirstResult(0)
            ->setMaxResults($limit)
        ;
    }
}
