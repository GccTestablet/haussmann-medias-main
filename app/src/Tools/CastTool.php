<?php

declare(strict_types=1);

namespace App\Tools;

class CastTool
{
    final public const TO_INT = 'to_int';
    final public const TO_FLOAT = 'to_float';
    final public const TO_STRING = 'to_string';
    final public const TO_BOOL = 'to_bool';

    public static function cast(mixed $field, string $type): string|int|bool|float
    {
        return match ($type) {
            self::TO_INT => (int) $field,
            self::TO_FLOAT => (float) $field,
            self::TO_STRING => (string) $field,
            self::TO_BOOL => (bool) $field,
            default => throw new \LogicException(\sprintf('Cannot cast to type "%s"', $type))
        };
    }
}
