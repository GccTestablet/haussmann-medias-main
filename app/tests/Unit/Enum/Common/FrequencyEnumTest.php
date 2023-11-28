<?php

declare(strict_types=1);

namespace App\Tests\Unit\Enum\Common;

use App\Enum\Common\FrequencyEnum;
use App\Tests\Unit\Enum\AbstractEnumTestCase;

class FrequencyEnumTest extends AbstractEnumTestCase
{
    protected static string $enum = FrequencyEnum::class;

    public function testNames(): void
    {
        $this->assertAsCallback(
            [
                'FLAT_FEE',
                'DAILY',
                'WEEKLY',
                'MONTHLY',
                'QUARTERLY',
                'HALF_YEARLY',
                'YEARLY',
            ],
            fn (FrequencyEnum $frequencyEnum) => $frequencyEnum->name
        );
    }

    public function testValues(): void
    {
        $this->assertAsCallback(
            [
                'flat-fee',
                'daily',
                'weekly',
                'monthly',
                'quarterly',
                'half-yearly',
                'yearly',
            ],
            fn (FrequencyEnum $frequencyEnum) => $frequencyEnum->value
        );
    }

    public function testGetAsText(): void
    {
        $this->assertAsCallback(
            [
                'Flat fee',
                'Daily',
                'Weekly',
                'Monthly',
                'Quarterly',
                'Half-yearly',
                'Yearly',
            ],
            fn (FrequencyEnum $frequencyEnum) => $frequencyEnum->getAsText()
        );
    }
}
