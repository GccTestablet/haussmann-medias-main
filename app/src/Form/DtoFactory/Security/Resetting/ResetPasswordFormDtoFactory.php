<?php

declare(strict_types=1);

namespace App\Form\DtoFactory\Security\Resetting;

use App\Entity\User;
use App\Form\Dto\Security\Resetting\ResetPasswordFormDto;

class ResetPasswordFormDtoFactory
{
    public function create(User $user): ResetPasswordFormDto
    {
        return new ResetPasswordFormDto($user);
    }
}
