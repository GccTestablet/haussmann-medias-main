<?php

declare(strict_types=1);

namespace App\Model\Pager;

class Column
{
    public function __construct(
        private readonly string $id,
        private readonly ColumnHeader $header,
        private readonly \Closure $callback,
        private readonly bool $visible = true,
        /**
         * @var array<string, mixed>
         */
        private readonly array $attributes = [],
    ) {}

    public function getId(): string
    {
        return $this->id;
    }

    public function getHeader(): ColumnHeader
    {
        return $this->header;
    }

    public function getCallback(): \Closure
    {
        return $this->callback;
    }

    public function isVisible(): bool
    {
        return $this->visible;
    }

    /**
     * @return array<string, mixed>
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }
}
