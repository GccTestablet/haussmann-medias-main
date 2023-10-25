<?php

declare(strict_types=1);

namespace App\Tests\Unit\Tools\Parser;

use App\Tests\Shared\AbstractTestCase;
use App\Tools\Parser\ArrayParser;

class ArrayParserTest extends AbstractTestCase
{
    private ?ArrayParser $arrayParser;

    protected function setUp(): void
    {
        $this->arrayParser = new ArrayParser();
    }

    public function testGetFirstValue(): void
    {
        $this->assertSame(1, ArrayParser::getFirstValue([1, 2, 3]));
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
