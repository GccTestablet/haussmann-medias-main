<?php

declare(strict_types=1);

namespace App\Event\Mailer\Security;

use App\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

class RegistrationEvent extends Event
{
    public function __construct(
        private readonly User $user,
        private readonly string $plainPassword,
    ) {}

    public function getUser(): User
    {
        return $this->user;
    }

    public function getPlainPassword(): string
    {
        return $this->plainPassword;
    }
}
