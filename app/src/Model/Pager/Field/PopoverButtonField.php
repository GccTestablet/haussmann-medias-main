<?php

declare(strict_types=1);

namespace App\Model\Pager\Field;

use App\Enum\Pager\FieldTypeEnum;
use Symfony\Component\Translation\TranslatableMessage;

class PopoverButtonField implements FieldInterface
{
    public function __construct(
        protected readonly FieldInterface|TranslatableMessage|string $value,
        private readonly TranslatableMessage|string $popoverTitle,
        private readonly TranslatableMessage|string $popoverContent,
        /**
         * @var array<string, mixed>
         */
        protected readonly array $attributes,
    ) {}

    public function getValue(): TranslatableMessage|FieldInterface|string
    {
        return $this->value;
    }

    public function getPopoverTitle(): TranslatableMessage|string
    {
        return $this->popoverTitle;
    }

    public function getPopoverContent(): TranslatableMessage|string
    {
        return $this->popoverContent;
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
        return FieldTypeEnum::POPOVER_BUTTON;
    }
}
