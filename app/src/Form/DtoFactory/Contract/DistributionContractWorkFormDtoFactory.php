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

    public function create(DistributionContract $distributionContract, ?DistributionContractWork $distributionContractWork): DistributionContractWorkFormDto
    {
        if (!$distributionContractWork) {
            $distributionContractWork = (new DistributionContractWork())
                ->setDistributionContract($distributionContract)
            ;

            return new DistributionContractWorkFormDto($distributionContractWork, false);
        }

        $dto = new DistributionContractWorkFormDto($distributionContractWork, true);
        $this->objectParser->mergeFromObject($distributionContractWork, $dto);

        return $dto;
    }

    public function updateEntity(DistributionContractWorkFormDto $dto, DistributionContractWork $distributionContractWork): void
    {
        $this->objectParser->mergeFromObject($dto, $distributionContractWork);
    }
}
