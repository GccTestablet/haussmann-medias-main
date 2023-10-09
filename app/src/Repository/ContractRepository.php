<?php

declare(strict_types=1);

namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class ContractRepository extends EntityRepository
{
    public function getQueryBuilderBySearchQuery(string $query, int $limit): QueryBuilder
    {
        $orX = $this->getEntityManager()->getExpressionBuilder()->orX();

        $fields = ['ct.originalFileName', 'b.name', 'cm.name'];
        foreach ($fields as $field) {
            $orX->add(\sprintf('LOWER(%s) LIKE LOWER(:query)', $field));
        }

        return $this->createQueryBuilder('ct')
            ->innerJoin('ct.beneficiary', 'b')
            ->innerJoin('ct.company', 'cm')
            ->where($orX)
            ->setParameter('query', \sprintf('%%%s%%', $query))
            ->orderBy('ct.originalFileName', 'ASC')
            ->setFirstResult(0)
            ->setMaxResults($limit)
        ;
    }
}
