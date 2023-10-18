<?php

declare(strict_types=1);

namespace App\Tools\Parser;

class ArrayParser
{
    /**
     * @param array<mixed> $array
     */
    public static function getFirstValue(array $array): mixed
    {
        return \array_shift($array);
    }

    /**
     * @param array<string|int, mixed> $source
     * @param array<string|int, mixed> $target
     *
     * @return array<string|int, mixed>
     */
    public function merge(array $source, array $target): array
    {
        $merged = \array_merge($source, $target);
        foreach ($merged as $key => $value) {
            if (\is_array($value) && isset($source[$key])) {
                $merged[$key] = \array_unique(\array_merge($source[$key], $value));
            }
        }

        return $merged;
    }
}
