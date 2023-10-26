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
}
