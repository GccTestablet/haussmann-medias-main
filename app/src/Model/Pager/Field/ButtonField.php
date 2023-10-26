<?php

declare(strict_types=1);

namespace App\Model\Pager\Field;

use App\Enum\Pager\FieldTypeEnum;
use Symfony\Component\Translation\TranslatableMessage;

class ButtonField implements FieldInterface
{
    public function __construct(
        private readonly TranslatableMessage|FieldInterface|string $value,
        /**
         * @var array<string, mixed>
         */
        private readonly array $attributes
    ) {}

    public function getValue(): TranslatableMessage|FieldInterface|string
    {
        return $this->value;
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
        return FieldTypeEnum::BUTTON;
    }
}
