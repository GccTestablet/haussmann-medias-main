<?php

declare(strict_types=1);

namespace App\Form\Dto\User;

use App\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;

class UserFormDto
{
    #[Assert\NotBlank]
    private ?string $firstName = null;

    #[Assert\NotBlank]
    private ?string $lastName = null;

    #[Assert\Email]
    #[Assert\NotBlank]
    private ?string $email = null;

    #[Assert\Choice(choices: [User::ROLE_SUPER_ADMIN, User::ROLE_ADMIN])]
    #[Assert\NotBlank]
    private ?string $role = null;

    public function __construct(
        private readonly User $user,
        private readonly bool $exists,
    ) {}

    public function getUser(): User
    {
        return $this->user;
    }

    public function isExists(): bool
    {
        return $this->exists;
    }

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
}
