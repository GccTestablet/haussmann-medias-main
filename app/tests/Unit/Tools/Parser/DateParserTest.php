<?php

declare(strict_types=1);

namespace App\Tests\Unit\Tools\Parser;

use App\Tests\AbstractTestCase;
use App\Tools\Parser\DateParser;

class DateParserTest extends AbstractTestCase
{
    private DateParser $dateParser;

    protected function setUp(): void
    {
        $this->dateParser = new DateParser();
    }

    public function testGetDateTime(): void
    {
        $dateTime = new \DateTime('2023-01-13 14:30:00');
        $this->assertSame(
            $dateTime->format(DateParser::DATETIME_FORMAT),
            $this->dateParser->getDateTime('2023-01-13 14:30:00')->format(DateParser::DATETIME_FORMAT)
        );

        $this->assertSame(
            $dateTime->modify('-1 hour')->format(DateParser::DATETIME_FORMAT),
            $this->dateParser->getDateTime('2023-01-13 14:30:00', 'Europe/London')->format(DateParser::DATETIME_FORMAT)
        );

        $this->assertNull($this->dateParser->getDateTime('ERROR'));
    }

    public function testCreateFromMixed(): void
    {
        $dateTime = new \DateTime('2023-01-13 14:30:00');
        $this->assertSame(
            $dateTime,
            $this->dateParser->createFromMixed($dateTime)
        );

        $this->assertSame(
            $dateTime->format(DateParser::DATETIME_FORMAT),
            $this->dateParser->createFromMixed(1_673_620_200)->format(DateParser::DATETIME_FORMAT)
        );

        $this->assertSame(
            $dateTime->format(DateParser::DATETIME_FORMAT),
            $this->dateParser->createFromMixed('2023-01-13 14:30:00')->format(DateParser::DATETIME_FORMAT)
        );

        $this->assertNull($this->dateParser->createFromMixed(null));
    }
}
