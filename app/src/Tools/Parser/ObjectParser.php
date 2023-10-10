<?php

declare(strict_types=1);

namespace App\Tools\Parser;

use App\Tools\CastTool;

class ObjectParser
{
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
