<?php

declare(strict_types=1);

namespace App\Tests\Unit\Tools\Parser;

use App\Tests\Shared\AbstractTestCase;
use App\Tools\Parser\StringParser;

class StringParserTest extends AbstractTestCase
{
    public function testSlugify(): void
    {
        $this->assertSame('test', StringParser::slugify('TEST'));
        $this->assertSame('test-test', StringParser::slugify('TEST Test'));
        $this->assertSame('test_test', StringParser::slugify('TEST Test', '_'));
    }
}
