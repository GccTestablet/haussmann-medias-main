<?php

declare(strict_types=1);

namespace App\Tools\Parser;

use Doctrine\ORM\EntityManagerInterface;

class EntityParser
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly ObjectParser $objectParser,
    ) {}

    public function getClassName(string|object $classOrObject): string
    {
        $class = $this->objectParser->getClassName($classOrObject);

        return $this->entityManager->getClassMetadata($class)->getName();
    }
}
