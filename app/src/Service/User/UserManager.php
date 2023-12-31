<?php

declare(strict_types=1);

namespace App\Service\User;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class UserManager
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {}

    /**
     * @return User[]
     */
    public function findAll(): array
    {
        return $this->getRepository()->findBy(
            [],
            ['lastName' => 'ASC', 'firstName' => 'ASC']
        );
    }

    public function findByEmail(string $email): ?User
    {
        return $this->getRepository()->findOneBy(['email' => $email]);
    }

    public function findByPasswordResetToken(string $confirmationToken): ?User
    {
        return $this->getRepository()->findOneBy(['passwordResetToken' => $confirmationToken]);
    }

    private function getRepository(): UserRepository|EntityRepository
    {
        return $this->entityManager->getRepository(User::class);
    }
}
