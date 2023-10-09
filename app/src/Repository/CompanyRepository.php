<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Company;
use App\Entity\User;
use App\Enum\Company\CompanyTypeEnum;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class CompanyRepository extends EntityRepository
{
    public function getByTypeQueryBuilder(CompanyTypeEnum $type): QueryBuilder
    {
        return $this->createQueryBuilder('c')
            ->where('c.type = :type')
            ->setParameter('type', $type)
            ->orderBy('c.name', 'ASC')
        ;
    }

    /**
     * @return Company[]
     */
    public function findByUser(User $user): array
    {
        return $this->createQueryBuilder('c')
            ->innerJoin('c.users', 'u')
            ->where('u.user = :user')
            ->setParameter('user', $user)
            ->orderBy('c.name', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
}
