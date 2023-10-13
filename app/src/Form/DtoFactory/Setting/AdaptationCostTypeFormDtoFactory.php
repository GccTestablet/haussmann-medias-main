<?php

declare(strict_types=1);

namespace App\Form\DtoFactory\Setting;

use App\Entity\Setting\AdaptationCostType;
use App\Form\Dto\Setting\AdaptationCostTypeFormDto;
use App\Tools\Parser\ObjectParser;

class AdaptationCostTypeFormDtoFactory
{
    public function __construct(
        private readonly ObjectParser $objectParser
    ) {}

    public function create(?AdaptationCostType $type): AdaptationCostTypeFormDto
    {
        if (!$type) {
            return new AdaptationCostTypeFormDto(new AdaptationCostType(), false);
        }

        $dto = new AdaptationCostTypeFormDto($type, true);
        $this->objectParser->mergeFromObject($type, $dto);

        return $dto;
    }

    public function updateEntity(AdaptationCostTypeFormDto $dto, AdaptationCostType $type): void
    {
        $this->objectParser->mergeFromObject($dto, $type);
    }
}
