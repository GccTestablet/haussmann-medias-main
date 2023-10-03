<?php

declare(strict_types=1);

namespace App\Form\Validator;

use App\Form\Validator\Constraint\PasswordRequestNonExpired;
use App\Service\Security\UserPasswordManager;
use App\Service\User\UserManager;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class PasswordRequestNonExpiredValidator extends ConstraintValidator
{
    public function __construct(
        private readonly UserManager $userManager,
        private readonly UserPasswordManager $userPasswordManager,
    ) {}

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof PasswordRequestNonExpired) {
            throw new UnexpectedTypeException($constraint, PasswordRequestNonExpired::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        $user = $this->userManager->findByEmail($value);
        if (!$user) {
            return;
        }

        if (!$this->userPasswordManager->canRequestResetPassword($user)) {
            $this->context->buildViolation($constraint->message)
                ->addViolation()
            ;
        }
    }
}
