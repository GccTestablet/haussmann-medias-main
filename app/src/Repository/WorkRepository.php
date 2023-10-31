<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Company;
use App\Entity\Contract\DistributionContract;
use App\Entity\Work\Work;
use App\Enum\Pager\ColumnEnum;
use App\Pager\Shared\PagerInterface;
use App\Repository\Shared\PagerRepositoryInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class WorkRepository extends EntityRepository implements PagerRepositoryInterface
{
    public function getPagerQueryBuilder(array $criteria, array $orderBy, ?int $limit = PagerInterface::DEFAULT_LIMIT, int $offset = PagerInterface::DEFAULT_OFFSET): QueryBuilder
    {
        $expr = $this->getEntityManager()->getExpressionBuilder();
        $queryBuilder = $this->createQueryBuilder('w')
            ->innerJoin('w.acquisitionContract', 'ac')
            ->leftJoin('w.contractWorks', 'cw')
        ;

        foreach ($criteria as $field => $value) {
            $enum = ColumnEnum::tryFrom($field);

            match ($enum) {
                ColumnEnum::INTERNAL_ID => $queryBuilder
                    ->andWhere('LOWER(w.internalId) LIKE LOWER(:internalId)')
                    ->setParameter('internalId', \sprintf('%%%s%%', $value)),
                ColumnEnum::IMDB_ID => $queryBuilder
                    ->andWhere('LOWER(w.imdbId) LIKE LOWER(:imdbId)')
                    ->setParameter('imdbId', \sprintf('%%%s%%', $value)),
                ColumnEnum::NAME => $queryBuilder
                    ->andWhere('LOWER(w.name) LIKE LOWER(:name) OR LOWER(w.originalName) LIKE LOWER(:name)')
                    ->setParameter('name', \sprintf('%%%s%%', $value)),
                ColumnEnum::COMPANY => $queryBuilder
                    ->andWhere('ac.company = :company')
                    ->setParameter('company', $value),
                ColumnEnum::ACQUISITION_CONTRACT => $queryBuilder
                    ->andWhere('w.acquisitionContract = :acquisitionContract')
                    ->setParameter('acquisitionContract', $value),
                ColumnEnum::ACQUISITION_CONTRACT_NAME => $queryBuilder
                    ->andWhere('LOWER(ac.name) LIKE LOWER(:acquisitionContractName)')
                    ->setParameter('acquisitionContractName', \sprintf('%%%s%%', $value)),
                ColumnEnum::BENEFICIARIES => $queryBuilder
                    ->andWhere('ac.beneficiary IN (:beneficiaries)')
                    ->setParameter('beneficiaries', $value),
                ColumnEnum::DISTRIBUTION_CONTRACT => $queryBuilder
                    ->andWhere('cw.distributionContract = :distributionContract')
                    ->setParameter('distributionContract', $value),
                ColumnEnum::COUNTRIES => $this->addMultiple($queryBuilder, 'w.countries', $value),
                ColumnEnum::QUOTAS => $queryBuilder
                    ->andWhere('w.quota IN (:quotas)')
                    ->setParameter('quotas', $value),
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

    /**
     * @param array<mixed> $values
     */
    private function addMultiple(QueryBuilder $queryBuilder, string $field, array $values): void
    {
        $expr = $queryBuilder->expr();
        $orX = $expr->orX();
        foreach ($values as $key => $value) {
            $orX->add($expr->like($field, \sprintf(':value_%s', $key)));
            $queryBuilder->setParameter(\sprintf('value_%s', $key), \sprintf('%%%s%%', $value));
        }

        $queryBuilder->andWhere($orX);
    }
}
