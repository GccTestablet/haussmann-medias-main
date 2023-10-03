<?php

declare(strict_types=1);

namespace App\Service\Security;

use App\Entity\User;
use App\Event\Mailer\Security\ResetPasswordEvent;
use App\Tools\Generator\PasswordGenerator;
use App\Tools\Parser\DateParser;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class UserPasswordManager
{
    final public const RESET_PASSWORD_TTL = 60; // 1 minute

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly UserPasswordHasherInterface $userPasswordHasher,
        private readonly DateParser $dateParser
    ) {}

    public function updatePassword(User $user, string $plainPassword): void
    {
        $hashedPassword = $this->userPasswordHasher->hashPassword($user, $plainPassword);

        $user->setPassword($hashedPassword);
    }

    public function canRequestResetPassword(User $user): bool
    {
        if (!$user->getPasswordRequestedAt() instanceof \DateTime) {
            return true;
        }

        $now = $this->dateParser->getDateTime();

        return $user->getPasswordRequestedAt()->getTimestamp() + self::RESET_PASSWORD_TTL < $now?->getTimestamp();
    }

    public function requestResetPassword(User $user): void
    {
        $user
            ->setPasswordRequestedAt($this->dateParser->getDateTime())
            ->setPasswordResetToken(PasswordGenerator::generate(32))
        ;

        $event = new ResetPasswordEvent($user);
        $this->eventDispatcher->dispatch($event);

        $this->entityManager->flush();
    }
}
