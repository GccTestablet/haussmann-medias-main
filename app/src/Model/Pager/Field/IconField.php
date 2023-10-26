<?php

declare(strict_types=1);

namespace App\Model\Pager\Field;

use App\Enum\Pager\FieldTypeEnum;

class IconField implements FieldInterface
{
    public function __construct(
        private readonly string $icon,
        /**
         * @var array<string, mixed>
         */
        private readonly array $attributes = [],
    ) {}

    public function getIcon(): string
    {
        return $this->icon;
    }

    /**
     * @return array<string, mixed>
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function getType(): FieldTypeEnum
    {
        return FieldTypeEnum::ICON;
    }
}
