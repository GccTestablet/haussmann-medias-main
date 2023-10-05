<?php

declare(strict_types=1);

namespace App\Service\User;

use App\Entity\Company;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class UserUpdater
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {}

    public function updateConnectedOn(User $user, Company $company): void
    {
        $user->setConnectedOn($company);

        $this->entityManager->flush();
    }
}
