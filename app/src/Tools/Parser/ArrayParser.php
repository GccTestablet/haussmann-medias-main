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
}
