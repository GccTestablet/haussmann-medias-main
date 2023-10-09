<?php

declare(strict_types=1);

namespace App\Form\Validator;

use App\Form\Validator\Constraint\UniqueEntityField;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueEntityFieldValidator extends ConstraintValidator
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {}

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof UniqueEntityField) {
            throw new UnexpectedTypeException($constraint, UniqueEntityField::class);
        }

        $origin = $constraint->getOrigin();
        $entityClass = $constraint->getEntityClass();
        if ($origin && !\is_a($origin, $entityClass)) {
            throw new UnexpectedTypeException($origin, $entityClass);
        }

        if (null === $value || '' === $value) {
            return;
        }

        $repository = $this->getRepository($entityClass);
        $entity = $repository->findOneBy([$constraint->getField() => $value]);
        if (!$entity) {
            return;
        }

        if ($entity === $origin) {
            return;
        }

        $this->context->buildViolation($constraint->message)
            ->addViolation()
        ;
    }

    /**
     * @param class-string $entityClass
     */
    private function getRepository(string $entityClass): EntityRepository
    {
        return $this->entityManager->getRepository($entityClass);
    }
}
