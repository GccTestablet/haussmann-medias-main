<?php

declare(strict_types=1);

namespace App\Tests\Unit\Form\Dto\Common;

use App\Form\Dto\Common\DateRangeFormDto;
use App\Tests\Shared\AbstractTestCase;

class DateRangeFormDtoTest extends AbstractTestCase
{
    public function testGetAndSetWithValues(): void
    {
        $from = new \DateTime();
        $to = new \DateTime();

        $dto = new DateRangeFormDto($from, $to);

        $this->assertSame($from, $dto->getFrom());
        $this->assertSame($to, $dto->getTo());
    }

    public function testGetAndSetWithNull(): void
    {
        $dto = new DateRangeFormDto(null, null);

        $this->assertNull($dto->getFrom());
        $this->assertNull($dto->getTo());
    }
}
