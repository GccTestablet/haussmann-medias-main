<?php

declare(strict_types=1);

namespace App\Tools\Parser;

use Symfony\Component\String\Slugger\AsciiSlugger;

class StringParser
{
    public function slugify(string $string): string
    {
        $slugger = new AsciiSlugger();

        return (string) $slugger->slug($string, '_')->lower();
    }
}
