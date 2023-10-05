<?php

declare(strict_types=1);

namespace App\Service\Company;

use App\Entity\Company;
use App\Entity\User;
use App\Repository\CompanyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class CompanyManager
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {}

    /**
     * @return Company[]
     */
    public function findByUser(User $user, bool $isSuperAdmin = false): array
    {
        if ($isSuperAdmin) {
            return $this->getRepository()->findAll();
        }

        return $this->getRepository()->findByUser($user);
    }

    /**
     * @return CompanyRepository|EntityRepository<Company>
     */
    private function getRepository(): CompanyRepository|EntityRepository
    {
        return $this->entityManager->getRepository(Company::class);
    }
}
