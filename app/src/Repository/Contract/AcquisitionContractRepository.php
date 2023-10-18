<?php

declare(strict_types=1);

namespace App\Repository\Contract;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class AcquisitionContractRepository extends EntityRepository
{
    public function getQueryBuilderBySearchQuery(string $query, int $limit): QueryBuilder
    {
        $orX = $this->getEntityManager()->getExpressionBuilder()->orX();

        $fields = ['ac.name', 'ac.originalFileName', 'b.name', 'cm.name'];
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
