<?php

declare(strict_types=1);

namespace App\Service\Contract;

use App\Entity\Contract\DistributionContract;
use App\Entity\Contract\DistributionContractWork;
use App\Entity\Contract\DistributionContractWorkTerritory;
use App\Entity\Setting\Territory;
use Doctrine\ORM\EntityManagerInterface;

class DistributionContractWorkTerritoryManager
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {}

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

    public function createFromContract(DistributionContract $distributionContract, DistributionContractWork $contractWork): void
    {
        $territories = $distributionContract->getTerritories();
        $broadcastChannels = $distributionContract->getBroadcastChannels();
        $work = $contractWork->getWork();

        foreach ($territories as $territory) {
            if (!$work->getTerritories()->contains($territory)) {
                continue;
            }

            $contractWorkTerritory = $this->findOrCreate($contractWork, $territory);

            foreach ($broadcastChannels as $channel) {
                if (!$work->getBroadcastChannels()->contains($channel)) {
                    continue;
                }

                $contractWorkTerritory->addBroadcastChannel($channel);
            }

            $this->entityManager->persist($contractWorkTerritory);

            $contractWork->addWorkTerritory($contractWorkTerritory);
        }
    }
}
