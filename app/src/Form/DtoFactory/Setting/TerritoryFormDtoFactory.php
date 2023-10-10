<?php

declare(strict_types=1);

namespace App\Form\DtoFactory\Setting;

use App\Entity\Territory;
use App\Form\Dto\Setting\TerritoryFormDto;

class TerritoryFormDtoFactory
{
    public function create(?Territory $territory): TerritoryFormDto
    {
        if (!$territory) {
            return new TerritoryFormDto(new Territory(), false);
        }

        return (new TerritoryFormDto($territory, true))
            ->setName($territory->getName())
            ->setDescription($territory->getDescription())
        ;
    }

    public function updateEntity(TerritoryFormDto $dto, Territory $territory): void
    {
        $territory
            ->setName($dto->getName())
            ->setDescription($dto->getDescription())
        ;
    }
}
