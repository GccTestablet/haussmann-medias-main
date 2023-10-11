<?php

declare(strict_types=1);

namespace App\Form\DtoFactory\Contract;

use App\Entity\Contract\DistributionContract;
use App\Entity\Contract\DistributionContractWork;
use App\Form\Dto\Contract\DistributionContractWorkFormDto;

class DistributionContractWorkFormDtoFactory
{
    public function create(DistributionContract $distributionContract, ?DistributionContractWork $distributionContractWork): DistributionContractWorkFormDto
    {
        if (!$distributionContractWork) {
            $distributionContractWork = (new DistributionContractWork())
                ->setDistributionContract($distributionContract)
            ;

            return new DistributionContractWorkFormDto($distributionContractWork, false);
        }

        return (new DistributionContractWorkFormDto($distributionContractWork, true))
            ->setWork($distributionContractWork->getWork())
            ->setTerritories($distributionContractWork->getTerritories())
            ->setBroadcastChannels($distributionContractWork->getBroadcastChannels())
            ->setBroadcastServices($distributionContractWork->getBroadcastServices())
        ;
    }

    public function updateEntity(DistributionContractWorkFormDto $dto, DistributionContractWork $distributionContractWork): void
    {
        $distributionContractWork
            ->setWork($dto->getWork())
            ->setTerritories($dto->getTerritories())
            ->setBroadcastChannels($dto->getBroadcastChannels())
            ->setBroadcastServices($dto->getBroadcastServices())
        ;
    }
}
