<?php

declare(strict_types=1);

namespace App\Tools\Parser;

use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;

class ObjectParser
{
    public function getClassName(string|object $classOrObject): string
    {
        if (\is_string($classOrObject)) {
            return $classOrObject;
        }

        return $classOrObject::class;
    }

    /**
     * @return string[]
     */
    public function getProperties(object $object): array
    {
        $reflectionExtractor = new ReflectionExtractor();
        $propertyInfo = new PropertyInfoExtractor([$reflectionExtractor]);

        return $propertyInfo->getProperties($this->getClassName($object)) ?? [];
    }

    public function mergeFromObject(object $source, object $target): void
    {
        $properties = \array_intersect($this->getProperties($source), $this->getProperties($target));
        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        foreach ($properties as $property) {
            $propertyAccessor->setValue(
                $target,
                $property,
                $propertyAccessor->getValue($source, $property)
            );
        }
    }
}
