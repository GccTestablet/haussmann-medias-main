<?php

declare(strict_types=1);

namespace App\Tests\Unit\Enum\Pager;

use App\Enum\Pager\FieldTypeEnum;
use App\Tests\Unit\Enum\AbstractEnumTestCase;

class FieldTypeEnumTest extends AbstractEnumTestCase
{
    protected static string $enum = FieldTypeEnum::class;

    public function testNames(): void
    {
        $this->assertAsCallback(
            [
                'AMOUNT',
                'ICON',
                'BUTTON',
                'POPOVER_BUTTON',
                'LINK',
                'COLLECTION',
                'SIMPLE_ARRAY',
                'PERIOD',
                'BOOLEAN',
            ],
            fn (FieldTypeEnum $fieldTypeEnum) => $fieldTypeEnum->name
        );
    }

    public function testValues(): void
    {
        $this->assertAsCallback(
            [
                'pager_amount',
                'pager_icon',
                'pager_button',
                'pager_popover_button',
                'pager_link',
                'pager_collection',
                'pager_simple_array',
                'pager_period',
                'pager_boolean',
            ],
            fn (FieldTypeEnum $fieldTypeEnum) => $fieldTypeEnum->value
        );
    }
}
