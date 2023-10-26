<?php

declare(strict_types=1);

namespace App\Model\Pager;

use App\Enum\Pager\ColumnEnum;

class Column
{
    public function __construct(
        private readonly ColumnEnum $id,
        private readonly ColumnHeader $header,
        private readonly \Closure $callback,
        private readonly bool $visible = true,
        /**
         * @var array<string, mixed>
         */
        private readonly array $properties = [],
        private readonly ?string $extraClass = null,
        private readonly ?string $width = null,
    ) {}

    public function getId(): ColumnEnum
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
    public function getProperties(): array
    {
        return $this->properties;
    }

    public function getExtraClass(): ?string
    {
        return $this->extraClass;
    }

    public function getWidth(): ?string
    {
        return $this->width;
    }
}
