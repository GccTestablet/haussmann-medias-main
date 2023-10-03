<?php

declare(strict_types=1);

namespace App\Form\Dto\Security\Resetting;

use App\Form\Validator\Constraint as AppAssert;
use Symfony\Component\Validator\Constraints as Assert;

class RequestResetPasswordFormDto
{
    #[AppAssert\PasswordRequestNonExpired]
    #[Assert\Email]
    private ?string $email = null;

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }
}
