<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Company;
use App\Entity\Work;
use Doctrine\ORM\EntityRepository;

class WorkRepository extends EntityRepository
{
    /**
     * @return Work[]
     */
    public function findByCompany(Company $company): array
    {
        return $this->createQueryBuilder('w')
            ->select('w')
            ->innerJoin('w.contract', 'ct')
            ->where('ct.company = :company')
            ->setParameter('company', $company)
            ->orderBy('w.name', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
}
