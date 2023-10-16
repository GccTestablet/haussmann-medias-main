<?php

declare(strict_types=1);

namespace App\Form\DtoFactory\Contract;

use App\Entity\Contract\DistributionContractWork;
use App\Form\Dto\Contract\DistributionContractWorkTerritoryCollectionFormDto;
use App\Tools\Parser\ObjectParser;

class DistributionContractWorkTerritoryCollectionFormDtoFactory
{
    public function __construct(
        private readonly ObjectParser $objectParser
    ) {}

    public function create(DistributionContractWork $contractWork): DistributionContractWorkTerritoryCollectionFormDto
    {
        $dto = new DistributionContractWorkTerritoryCollectionFormDto($contractWork, true);
        $this->objectParser->mergeFromObject($contractWork, $dto);

        return $dto;
    }

    public function updateEntity(DistributionContractWorkTerritoryCollectionFormDto $dto, DistributionContractWork $contractWork): void
    {
        $this->objectParser->mergeFromObject($dto, $contractWork);
    }
}
