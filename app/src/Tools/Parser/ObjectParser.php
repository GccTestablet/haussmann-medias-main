<?php

declare(strict_types=1);

namespace App\Tools\Parser;

class ObjectParser
{
    public function getClassName(string|object $classOrObject): string
    {
        if (\is_string($classOrObject)) {
            return $classOrObject;
        }

        return $classOrObject::class;
    }
}
