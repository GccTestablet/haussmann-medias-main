<?php

declare(strict_types=1);

namespace App\Tests\Unit\Enum\Work;

use App\Enum\Work\WorkQuotaEnum;
use App\Tests\Unit\Enum\AbstractEnumTestCase;

class WorkQuotaEnumTest extends AbstractEnumTestCase
{
    protected static string $enum = WorkQuotaEnum::class;

    public function testNames(): void
    {
        $this->assertAsCallback(
            [
                'FRANCE',
                'EUROPEAN',
                'INTERNATIONAL',
            ],
            fn (WorkQuotaEnum $workQuotaEnum) => $workQuotaEnum->name
        );
    }

    public function testValues(): void
    {
        $this->assertAsCallback(
            [
                'france',
                'european',
                'international',
            ],
            fn (WorkQuotaEnum $workQuotaEnum) => $workQuotaEnum->value
        );
    }

    public function testGetAsText(): void
    {
        $this->assertAsCallback(
            [
                'France',
                'European',
                'International',
            ],
            fn (WorkQuotaEnum $workQuotaEnum) => $workQuotaEnum->getAsText()
        );
    }
}
