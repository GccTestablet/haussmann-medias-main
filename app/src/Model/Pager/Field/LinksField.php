<?php

declare(strict_types=1);

namespace App\Model\Pager\Field;

use App\Enum\Pager\FieldTypeEnum;

class LinksField implements FieldInterface
{
    public function __construct(
        /**
         * @var array<LinkField>
         */
        private readonly array $links,
        private readonly string $separator = '',
    ) {}

    /**
     * @return array<LinkField>
     */
    public function getLinks(): array
    {
        return $this->links;
    }

    public function getSeparator(): string
    {
        return $this->separator;
    }

    public function getType(): FieldTypeEnum
    {
        return FieldTypeEnum::LINKS;
    }
}
