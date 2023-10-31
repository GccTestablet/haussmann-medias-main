<?php

declare(strict_types=1);

namespace App\Model\Pager\Field;

use App\Enum\Pager\FieldTypeEnum;

class BooleanField implements FieldInterface
{
    public function __construct(
        private readonly bool $value,
    ) {}

    public function isValue(): bool
    {
        return $this->value;
    }

    public function getType(): FieldTypeEnum
    {
        return FieldTypeEnum::BOOLEAN;
    }
}
