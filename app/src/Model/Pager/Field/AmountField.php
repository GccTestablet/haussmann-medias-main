<?php

declare(strict_types=1);

namespace App\Model\Pager\Field;

use App\Enum\Pager\FieldTypeEnum;

class AmountField implements FieldInterface
{
    public function __construct(
        private readonly ?float $amount,
        private readonly string $currency,
    ) {}

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getType(): FieldTypeEnum
    {
        return FieldTypeEnum::AMOUNT;
    }
}
