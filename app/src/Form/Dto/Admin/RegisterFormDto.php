<?php

declare(strict_types=1);

namespace App\Form\Dto\Admin;

use App\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;

class RegisterFormDto
{
    private ?string $firstName = null;

    private ?string $lastName = null;

    #[Assert\Email]
    private ?string $email = null;

    #[Assert\Choice(choices: [User::ROLE_ADMIN, User::ROLE_SUPPLIER, User::ROLE_DISTRIBUTOR])]
    private ?string $role = null;

    private ?User $user = null;

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(?string $role): static
    {
        $this->role = $role;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
