<?php

declare(strict_types=1);

namespace App\Model\Pager\Field;

use App\Enum\Pager\FieldTypeEnum;

class PeriodField implements FieldInterface
{
    public function __construct(
        private readonly ?\DateTime $from,
        private readonly ?\DateTime $to,
    ) {}

    public function getFrom(): ?\DateTime
    {
        return $this->from;
    }

    public function getTo(): ?\DateTime
    {
        return $this->to;
    }

    public function getType(): FieldTypeEnum
    {
        return FieldTypeEnum::PERIOD;
    }
}
