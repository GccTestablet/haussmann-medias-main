<?php

declare(strict_types=1);

namespace App\Form\DtoFactory\Contract;

use App\Entity\Contract\DistributionContract;
use App\Entity\Contract\DistributionContractWork;
use App\Form\Dto\Contract\DistributionContractWorkFormDto;
use App\Tools\Parser\ObjectParser;

class DistributionContractWorkFormDtoFactory
{
    public function __construct(
        private readonly ObjectParser $objectParser
    ) {}

    public function create(DistributionContract $contract, ?DistributionContractWork $contractWork): DistributionContractWorkFormDto
    {
        if (!$contractWork) {
            $contractWork = (new DistributionContractWork())
                ->setDistributionContract($contract)
            ;

            return new DistributionContractWorkFormDto($contractWork, false);
        }

        $dto = new DistributionContractWorkFormDto($contractWork, true);

        $this->objectParser->mergeFromObject($contractWork, $dto);

        return $dto;
    }

    public function updateEntity(DistributionContractWork $contractWork, DistributionContractWorkFormDto $dto): void
    {
        $this->objectParser->mergeFromObject($dto, $contractWork);
    }
}
