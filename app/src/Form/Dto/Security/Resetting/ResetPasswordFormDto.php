<?php

declare(strict_types=1);

namespace App\Form\Dto\Security\Resetting;

use App\Entity\User;

class ResetPasswordFormDto
{
    private ?string $newPassword = null;

    public function __construct(
        private readonly User $user
    ) {}

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function getNewPassword(): ?string
    {
        return $this->newPassword;
    }

    public function setNewPassword(?string $newPassword): static
    {
        $this->newPassword = $newPassword;

        return $this;
    }
}
