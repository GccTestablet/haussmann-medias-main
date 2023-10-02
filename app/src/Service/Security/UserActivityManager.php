<?php

declare(strict_types=1);

namespace App\Service\Security;

use App\Entity\User;
use App\Tools\Parser\DateParser;
use Doctrine\ORM\EntityManagerInterface;

class UserActivityManager
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly DateParser $dateParser
    ) {}

    public function updateLastLogin(User $user): void
    {
        $user->setLastLogin($this->dateParser->getDateTime());

        $this->entityManager->flush();
    }

    public function updateLastActivity(User $user): void
    {
        $user->setLastActivity($this->dateParser->getDateTime());

        $this->entityManager->flush();
    }
}
