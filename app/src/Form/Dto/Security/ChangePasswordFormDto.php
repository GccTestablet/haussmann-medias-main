<?php

declare(strict_types=1);

namespace App\Form\Dto\Security;

class ChangePasswordFormDto
{
    private ?string $newPassword = null;

    private ?string $oldPassword = null;

    public function getNewPassword(): ?string
    {
        return $this->newPassword;
    }

    public function setNewPassword(?string $newPassword): static
    {
        $this->newPassword = $newPassword;

        return $this;
    }

    public function getOldPassword(): ?string
    {
        return $this->oldPassword;
    }

    public function setOldPassword(?string $oldPassword): static
    {
        $this->oldPassword = $oldPassword;

        return $this;
    }
}
