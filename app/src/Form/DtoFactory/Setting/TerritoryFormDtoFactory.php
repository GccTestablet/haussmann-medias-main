<?php

declare(strict_types=1);

namespace App\Form\DtoFactory\Setting;

use App\Entity\Setting\Territory;
use App\Form\Dto\Setting\TerritoryFormDto;
use App\Tools\Parser\ObjectParser;

class TerritoryFormDtoFactory
{
    public function __construct(
        private readonly ObjectParser $objectParser
    ) {}

    public function create(?Territory $territory): TerritoryFormDto
    {
        if (!$territory) {
            return new TerritoryFormDto(new Territory(), false);
        }

        $dto = new TerritoryFormDto($territory, true);
        $this->objectParser->mergeFromObject($territory, $dto);

        return $dto;
    }

    public function updateEntity(TerritoryFormDto $dto, Territory $territory): void
    {
        $this->objectParser->mergeFromObject($dto, $territory);
    }
}
