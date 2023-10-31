<?php

declare(strict_types=1);

namespace App\Model\Pager\Field;

use App\Enum\Pager\FieldTypeEnum;

class SimpleArrayField implements FieldInterface
{
    public function __construct(
        /**
         * @var array<string>
         */
        private readonly array $values,
        private readonly string $separator = ', ',
    ) {}

    /**
     * @return array<string>
     */
    public function getValues(): array
    {
        return $this->values;
    }

    public function getSeparator(): string
    {
        return $this->separator;
    }

    public function getType(): FieldTypeEnum
    {
        return FieldTypeEnum::SIMPLE_ARRAY;
    }
}
