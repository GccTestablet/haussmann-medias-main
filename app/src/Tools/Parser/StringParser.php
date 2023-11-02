<?php

declare(strict_types=1);

namespace App\Tools\Parser;

use Symfony\Component\String\Slugger\AsciiSlugger;

class StringParser
{
    public static function slugify(string $string, string $delimiter = '-'): string
    {
        $slugger = new AsciiSlugger();

        return (string) $slugger->slug($string, $delimiter)->lower();
    }

    public static function truncate(string $value, int $length, bool $preserve = false, string $separator = '...'): string
    {
        // Value length is lower than expected length, truncate not needed
        if (\strlen($value) <= $length) {
            return $value;
        }

        // If preserve true, we truncate after the next space
        if ($preserve) {
            $breakpoint = \strpos($value, ' ', $length);
            if (false !== $breakpoint) {
                $length = $breakpoint;
            }
        }

        return \rtrim(\substr($value, 0, $length)).$separator;
    }
}
