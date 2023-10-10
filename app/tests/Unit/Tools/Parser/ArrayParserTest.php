<?php

declare(strict_types=1);

namespace App\Tests\Unit\Tools\Parser;

use App\Tests\AbstractTestCase;
use App\Tools\Parser\ArrayParser;

class ArrayParserTest extends AbstractTestCase
{
    private ?ArrayParser $arrayParser;

    protected function setUp(): void
    {
        $this->arrayParser = new ArrayParser();
    }

    public function testMerge(): void
    {
        $source = ['a' => 1, 'b' => 2];
        $target = ['a' => 3, 'c' => 4];

        $this->assertSame([
            'a' => 3,
            'b' => 2,
            'c' => 4,
        ], $this->arrayParser->merge($source, $target));
    }
}
