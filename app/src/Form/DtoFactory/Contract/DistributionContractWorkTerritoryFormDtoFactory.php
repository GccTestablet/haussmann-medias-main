<?php

declare(strict_types=1);

namespace App\Form\DtoFactory\Contract;

use App\Entity\Contract\DistributionContractWork;
use App\Form\Dto\Contract\DistributionContractWorkTerritoryFormDto;
use App\Tools\Parser\ObjectParser;

class DistributionContractWorkTerritoryFormDtoFactory
{
    public function __construct(
        private readonly ObjectParser $objectParser
    ) {}

    public function create(DistributionContractWork $contractWork): DistributionContractWorkTerritoryFormDto
    {
        $dto = new DistributionContractWorkTerritoryFormDto($contractWork, true);
        $this->objectParser->mergeFromObject($contractWork, $dto);

        return $dto;
    }

    public function updateEntity(DistributionContractWorkTerritoryFormDto $dto, DistributionContractWork $contractWork): void
    {
        $this->objectParser->mergeFromObject($dto, $contractWork);
    }
}
