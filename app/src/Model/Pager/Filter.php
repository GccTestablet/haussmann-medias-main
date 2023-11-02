<?php

declare(strict_types=1);

namespace App\Model\Pager;

use App\Enum\Pager\ColumnEnum;

class Filter
{
    public function __construct(
        private readonly string $column,
        private readonly mixed $value,
    ) {
        if (!ColumnEnum::isValueValid($column)) {
            throw new \InvalidArgumentException(\sprintf('Invalid column "%s" given.', $column));
        }
    }

    public function getColumn(): string
    {
        return $this->column;
    }

    public function getValue(): mixed
    {
        return $this->value;
    }
}
