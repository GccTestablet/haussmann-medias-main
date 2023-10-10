<?php

declare(strict_types=1);

namespace App\Form\DtoFactory\Setting;

use App\Entity\Setting\AdaptationCostType;
use App\Form\Dto\Setting\AdaptationCostTypeFormDto;

class AdaptationCostTypeFormDtoFactory
{
    public function create(?AdaptationCostType $type): AdaptationCostTypeFormDto
    {
        if (!$type) {
            return new AdaptationCostTypeFormDto(new AdaptationCostType(), false);
        }

        return (new AdaptationCostTypeFormDto($type, true))
            ->setName($type->getName())
        ;
    }

    public function updateEntity(AdaptationCostTypeFormDto $dto, AdaptationCostType $type): void
    {
        $type
            ->setName($dto->getName())
        ;
    }
}
