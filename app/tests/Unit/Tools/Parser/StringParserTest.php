<?php

declare(strict_types=1);

namespace App\Tests\Unit\Tools\Parser;

use App\Tests\Shared\AbstractTestCase;
use App\Tools\Parser\StringParser;

class StringParserTest extends AbstractTestCase
{
    private ?StringParser $stringParser;

    protected function setUp(): void
    {
        $this->stringParser = new StringParser();
    }

    public function testSlugify(): void
    {
        $this->assertSame('test', $this->stringParser->slugify('TEST'));
        $this->assertSame('test-test', $this->stringParser->slugify('TEST Test'));
        $this->assertSame('test_test', $this->stringParser->slugify('TEST Test', '_'));
    }
}
