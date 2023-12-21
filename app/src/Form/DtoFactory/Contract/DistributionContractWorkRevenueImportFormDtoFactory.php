<?php

declare(strict_types=1);

namespace App\Form\DtoFactory\Contract;

use App\Entity\Contract\DistributionContract;
use App\Form\Dto\Contract\DistributionContractWorkRevenueImportFormDto;

class DistributionContractWorkRevenueImportFormDtoFactory
{
    public function create(DistributionContract $distributionContract): DistributionContractWorkRevenueImportFormDto
    {
        return new DistributionContractWorkRevenueImportFormDto($distributionContract);
    }

    public function updateEntity(DistributionContractWorkRevenueImportFormDto $dto, DistributionContract $distributionContract): void
    {

    }
}
