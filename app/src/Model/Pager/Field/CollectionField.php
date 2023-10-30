<?php

declare(strict_types=1);

namespace App\Model\Pager\Field;

use App\Enum\Pager\FieldTypeEnum;

class CollectionField implements FieldInterface
{
    public function __construct(
        /**
         * @var array<FieldInterface>
         */
        private readonly array $elements,
        private readonly string $separator = '',
    ) {}

    /**
     * @return array<FieldInterface>
     */
    public function getElements(): array
    {
        return $this->elements;
    }

    public function getSeparator(): string
    {
        return $this->separator;
    }

    public function getType(): FieldTypeEnum
    {
        return FieldTypeEnum::COLLECTION;
    }
}
