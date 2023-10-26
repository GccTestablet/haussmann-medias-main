<?php

declare(strict_types=1);

namespace App\Model\Pager;

use App\Enum\Pager\ColumnEnum;

class Filter
{
    public function __construct(
        private readonly ColumnEnum $column,
        private readonly mixed $value,
    ) {}

    public function getColumn(): ColumnEnum
    {
        return $this->column;
    }

    public function getValue(): mixed
    {
        return $this->value;
    }
}
