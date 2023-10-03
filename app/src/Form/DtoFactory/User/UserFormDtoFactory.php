<?php

declare(strict_types=1);

namespace App\Form\DtoFactory\User;

use App\Entity\User;
use App\Form\Dto\User\UserFormDto;
use App\Service\Security\SecurityManager;

class UserFormDtoFactory
{
    public function __construct(
        private readonly SecurityManager $securityManager
    ) {}

    public function create(?User $user): UserFormDto
    {
        if (!$user) {
            return new UserFormDto(new User(), false);
        }

        $role = $this->securityManager->getUserRole($user);

        return (new UserFormDto($user, true))
            ->setFirstName($user->getFirstName())
            ->setLastName($user->getLastName())
            ->setEmail($user->getEmail())
            ->setRole($role)
        ;
    }

    public function updateUser(UserFormDto $dto, User $user): void
    {
        $user
            ->setFirstName($dto->getFirstName())
            ->setLastName($dto->getLastName())
            ->setEmail($dto->getEmail())
            ->setRoles([$dto->getRole()])
        ;
    }
}
