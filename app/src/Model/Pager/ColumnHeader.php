<?php

declare(strict_types=1);

namespace App\Model\Pager;

class ColumnHeader
{
    public function __construct(
        private readonly \Closure $callback,
        private readonly bool $sortable = true,
        /**
         * @var array<string, mixed>
         */
        private readonly array $attributes = [],
    ) {}

    public function getCallback(): callable
    {
        return $this->callback;
    }

    public function isSortable(): bool
    {
        return $this->sortable;
    }

    /**
     * @return array<string, mixed>
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }
}
