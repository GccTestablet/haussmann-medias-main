<?php

declare(strict_types=1);

namespace App\Form\Validator;

use App\Form\Validator\Constraint\EmailExists;
use App\Service\User\UserManager;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class EmailExistsValidator extends ConstraintValidator
{
    public function __construct(
        private readonly UserManager $userManager
    ) {}

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof EmailExists) {
            throw new UnexpectedTypeException($constraint, EmailExists::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        $userFromEmail = $this->userManager->findByEmail($value);
        if ($userFromEmail && $userFromEmail !== $constraint->user) {
            $this->context->buildViolation($constraint->message)
                ->addViolation()
            ;
        }
    }
}
