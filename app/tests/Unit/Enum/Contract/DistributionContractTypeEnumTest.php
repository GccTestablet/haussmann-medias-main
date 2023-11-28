<?php

declare(strict_types=1);

namespace App\Tests\Unit\Enum\Contract;

use App\Enum\Contract\DistributionContractTypeEnum;
use App\Tests\Unit\Enum\AbstractEnumTestCase;

class DistributionContractTypeEnumTest extends AbstractEnumTestCase
{
    protected static string $enum = DistributionContractTypeEnum::class;

    public function testNames(): void
    {
        $this->assertAsCallback(
            [
                'ONE_OFF',
                'RECURRING',
            ],
            fn (DistributionContractTypeEnum $enum) => $enum->name
        );
    }

    public function testValues(): void
    {
        $this->assertAsCallback(
            [
                'one_off',
                'recurring',
            ],
            fn (DistributionContractTypeEnum $enum) => $enum->value
        );
    }

    public function testGetAsText(): void
    {
        $this->assertAsCallback(
            [
                'One-off',
                'Recurring',
            ],
            fn (DistributionContractTypeEnum $enum) => $enum->getAsText()
        );
    }
}
