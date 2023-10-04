<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Company;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

class UserRepository extends EntityRepository implements PasswordUpgraderInterface
{
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(\sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    public function findUsersNotInCompanyQueryBuilder(Company $company): QueryBuilder
    {
        $subQuery = $this->createQueryBuilder('u2')
            ->select('u2.id')
            ->innerJoin('u2.companies', 'c2')
            ->where('c2.company = :company')
        ;

        $queryBuilder = $this->createQueryBuilder('u');

        return $queryBuilder
            ->where($queryBuilder->expr()->notIn('u.id', $subQuery->getDQL()))
            ->setParameter('company', $company)
            ->orderBy('u.lastName', 'ASC')
            ->orderBy('u.firstName', 'ASC')
        ;
    }
}
