<?php

declare(strict_types=1);

namespace App\Service\Contract;

use App\Entity\Contract\DistributionContractWork;
use App\Entity\Contract\DistributionContractWorkTerritory;
use App\Entity\Setting\Territory;

class DistributionContractWorkTerritoryManager
{
    public function findOrCreate(DistributionContractWork $contractWork, Territory $territory): DistributionContractWorkTerritory
    {
        $workTerritory = $contractWork->getWorkTerritory($territory);
        if ($workTerritory) {
            return $workTerritory;
        }

        return (new DistributionContractWorkTerritory())
            ->setContractWork($contractWork)
            ->setTerritory($territory)
        ;
    }
}
