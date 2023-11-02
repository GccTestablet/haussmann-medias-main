<?php

declare(strict_types=1);

namespace App\Repository\Setting;

use App\Entity\Setting\AdaptationCostType;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class AdaptationCostTypeRepository extends EntityRepository
{
    public function getUnArchivedQueryBuilder(?AdaptationCostType $included): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('act')
            ->andWhere('act.archived = :archived')
            ->setParameter('archived', false)
            ->orderBy('act.name', 'ASC')
        ;

        //        if ($included) {
        //            $queryBuilder
        //                ->orWhere('act = :included')
        //                ->setParameter('included', $included->getId())
        //            ;
        //        }

        return $queryBuilder;
    }
}
