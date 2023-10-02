<?php

declare(strict_types=1);

namespace App\Form\DtoFactory\Admin;

use App\Entity\User;
use App\Form\Dto\Admin\RegisterFormDto;

class RegisterFormDtoFactory
{
    public function createUser(RegisterFormDto $dto): User
    {
        return (new User())
            ->setFirstName($dto->getFirstName())
            ->setLastName($dto->getLastName())
            ->setEmail($dto->getEmail())
        ;
    }
}
