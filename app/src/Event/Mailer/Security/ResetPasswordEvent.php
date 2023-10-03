<?php

declare(strict_types=1);

namespace App\Event\Mailer\Security;

use App\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

class ResetPasswordEvent extends Event
{
    public function __construct(
        private readonly User $user
    ) {}

    public function getUser(): User
    {
        return $this->user;
    }
}
