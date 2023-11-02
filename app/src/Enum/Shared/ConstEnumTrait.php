<?php

declare(strict_types=1);

namespace App\Enum\Shared;

trait ConstEnumTrait
{
    /**
     * @return array<string, mixed>
     */
    public static function getAsArray(): array
    {
        return (new \ReflectionClass(static::class))
            ->getConstants()
        ;
    }

    /**
     * @return array<mixed>
     */
    public static function getValues(): array
    {
        return \array_values(self::getAsArray());
    }

    public static function isValid(string $key): bool
    {
        return \array_key_exists($key, self::getAsArray());
    }

    public static function isValueValid(mixed $value): bool
    {
        return \in_array($value, self::getAsArray(), true);
    }
}
