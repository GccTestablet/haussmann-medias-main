<?php

declare(strict_types=1);

namespace App\Service\Security;

use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class SecurityManager
{
    public function __construct(
        private readonly Security $security
    ) {}

    public function getConnectedUser(): User
    {
        $user = $this->security->getUser();

        if (!$user instanceof User) {
            throw new AccessDeniedHttpException('The connected user is invalid');
        }

        return $user;
    }

    public function getUserRole(User $user): string
    {
        $array = \array_filter($user->getRoles(), static fn (string $role) => $role !== User::ROLE_USER);

        return \current($array);
    }
}
