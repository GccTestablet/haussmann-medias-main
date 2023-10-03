<?php

declare(strict_types=1);

namespace App\Form\Validator\Constraint;

use App\Entity\User;
use App\Form\Validator\EmailExistsValidator;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class EmailExists extends Constraint
{
    public string $message = 'A user with this email already exist';

    public function __construct(
        public ?User $user,
        array $groups = null,
        mixed $payload = null
    ) {
        parent::__construct($groups, $payload);
    }

    public function validatedBy(): string
    {
        return EmailExistsValidator::class;
    }
}
