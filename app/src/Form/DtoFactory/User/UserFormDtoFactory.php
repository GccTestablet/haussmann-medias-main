<?php

declare(strict_types=1);

namespace App\Form\DtoFactory\User;

use App\Entity\User;
use App\Form\Dto\User\UserFormDto;
use App\Service\Security\SecurityManager;
use App\Tools\Parser\ObjectParser;

class UserFormDtoFactory
{
    public function __construct(
        private readonly SecurityManager $securityManager,
        private readonly ObjectParser $objectParser
    ) {}

    public function create(?User $user): UserFormDto
    {
        if (!$user) {
            return new UserFormDto(new User(), false);
        }

        $role = $this->securityManager->getUserRole($user);
        $dto = (new UserFormDto($user, true))
            ->setRole($role)
        ;

        $this->objectParser->mergeFromObject($user, $dto);

        return $dto;
    }

    public function updateUser(UserFormDto $dto, User $user): void
    {
        $this->objectParser->mergeFromObject($dto, $user);
    }
}
