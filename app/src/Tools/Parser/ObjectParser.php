<?php

declare(strict_types=1);

namespace App\Tools\Parser;

use App\Tools\CastTool;
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

    /**
     * @param string[] $excludeProperties
     */
    public function mergeFromObject(object $source, object $target, array $excludeProperties = []): void
    {
        $properties = \array_intersect($this->getProperties($source), $this->getProperties($target));
        $properties = \array_diff($properties, $excludeProperties);
        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        foreach ($properties as $property) {
            $propertyAccessor->setValue(
                $target,
                $property,
                $propertyAccessor->getValue($source, $property)
            );
        }
    }

    /**
     * @param array<string|int, mixed> $source
     * @param array<string> $fields
     * @param array<string, string> $castTo
     */
    public function mergeFromArray(array $source, object $target, array $fields = [], array $castTo = []): object
    {
        if (empty($fields)) {
            $fields = \array_keys($source);
        }

        foreach ($fields as $field) {
            $setMethod = \sprintf('set%s', \ucfirst((string) $field));

            if (!\method_exists($target, $setMethod)) {
                throw new \InvalidArgumentException(\sprintf(
                    'Object "%s" does not seem to have a property named "%s". Could not find methods "%s".',
                    $target::class,
                    $field,
                    $setMethod
                ));
            }

            if (\array_key_exists($field, $castTo) && null !== $source[$field]) {
                $dto[$field] = CastTool::cast($source[$field], $castTo[$field]);
            }

            $target->$setMethod($source[$field]);
        }

        return $target;
    }
}
