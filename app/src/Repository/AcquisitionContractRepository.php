<?php

declare(strict_types=1);

namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class AcquisitionContractRepository extends EntityRepository
{
    public function getQueryBuilderBySearchQuery(string $query, int $limit): QueryBuilder
    {
        $orX = $this->getEntityManager()->getExpressionBuilder()->orX();

        $fields = ['ac.originalFileName', 'b.name', 'cm.name'];
        foreach ($fields as $field) {
            $orX->add(\sprintf('LOWER(%s) LIKE LOWER(:query)', $field));
        }

        return $this->createQueryBuilder('ac')
            ->innerJoin('ac.beneficiary', 'b')
            ->innerJoin('ac.company', 'cm')
            ->where($orX)
            ->setParameter('query', \sprintf('%%%s%%', $query))
            ->orderBy('ac.originalFileName', 'ASC')
            ->setFirstResult(0)
            ->setMaxResults($limit)
        ;
    }
}
