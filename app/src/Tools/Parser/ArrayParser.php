<?php

declare(strict_types=1);

namespace App\Tools\Parser;

use function array_shift;

class ArrayParser
{
    /**
     * @param array<mixed> $array
     */
    public static function getFirstValue(array $array): mixed
    {
        return array_shift($array);
    }
}
