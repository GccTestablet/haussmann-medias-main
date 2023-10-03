<?php

declare(strict_types=1);

namespace App\Service\Company;

use App\Entity\Company;
use App\Entity\User;
use App\Entity\UserCompany;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class CompanyUserManager
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {}

    public function findByCompanyAndUser(Company $company, User $user): ?UserCompany
    {
        return $this->getRepository()->findOneBy([
            'user' => $user,
            'company' => $company,
        ]);
    }

    private function getRepository(): EntityRepository
    {
        return $this->entityManager->getRepository(UserCompany::class);
    }
}
