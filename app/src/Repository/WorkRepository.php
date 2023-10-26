<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Company;
use App\Entity\Contract\DistributionContract;
use App\Entity\Work\Work;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class WorkRepository extends EntityRepository
{
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
