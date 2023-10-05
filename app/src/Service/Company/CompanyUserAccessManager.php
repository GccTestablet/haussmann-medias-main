<?php

declare(strict_types=1);

namespace App\Service\Company;

use App\Entity\Company;
use App\Entity\User;
use App\Entity\UserCompany;
use App\Enum\User\UserCompanyPermissionEnum;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class CompanyUserAccessManager
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {}

    public function getPermission(Company $company, User $user): ?UserCompanyPermissionEnum
    {
        $userCompany = $this->getRepository()->findOneBy([
            'user' => $user,
            'company' => $company,
        ]);

        if (!$userCompany) {
            return null;
        }

        return $userCompany->getPermission();
    }

    public function hasPermission(Company $company, User $user, UserCompanyPermissionEnum $permission): bool
    {
        return $permission === $this->getPermission($company, $user);
    }

    private function getRepository(): EntityRepository
    {
        return $this->entityManager->getRepository(UserCompany::class);
    }
}
