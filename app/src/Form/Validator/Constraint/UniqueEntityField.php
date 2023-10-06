<?php

declare(strict_types=1);

namespace App\Form\Validator\Constraint;

use App\Form\Validator\UniqueEntityFieldValidator;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class UniqueEntityField extends Constraint
{
    public string $message = 'This value is already used.';

    public function __construct(
        private readonly string $entityClass,
        private readonly string $field,
        private readonly ?object $origin,
        array $groups = null,
        mixed $payload = null
    ) {
        parent::__construct($groups, $payload);
    }

    public function getEntityClass(): string
    {
        return $this->entityClass;
    }

    public function getField(): string
    {
        return $this->field;
    }

    public function getOrigin(): ?object
    {
        return $this->origin;
    }

    public function validatedBy(): string
    {
        return UniqueEntityFieldValidator::class;
    }
}
