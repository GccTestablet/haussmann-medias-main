<?php

declare(strict_types=1);

namespace App\Form\Validator\Constraint;

use App\Form\Validator\PasswordRequestNonExpiredValidator;
use Symfony\Component\Validator\Constraint;

#[\Attribute]
class PasswordRequestNonExpired extends Constraint
{
    public string $message = 'You already request a password recently.';

    public function validatedBy(): string
    {
        return PasswordRequestNonExpiredValidator::class;
    }
}
