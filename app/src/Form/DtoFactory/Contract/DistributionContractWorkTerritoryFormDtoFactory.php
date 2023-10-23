<?php

declare(strict_types=1);

namespace App\Form\DtoFactory\Contract;

use App\Entity\Contract\DistributionContractWork;
use App\Form\Dto\Contract\DistributionContractWorkTerritoryFormDto;
use App\Form\Dto\Work\WorkTerritoryFormDto;
use App\Service\Contract\DistributionContractWorkTerritoryManager;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;

class DistributionContractWorkTerritoryFormDtoFactory
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly DistributionContractWorkTerritoryManager $distributionContractWorkTerritoryManager
    ) {}

    public function create(DistributionContractWork $contractWork): DistributionContractWorkTerritoryFormDto
    {
        $dto = new DistributionContractWorkTerritoryFormDto($contractWork);

        $work = $contractWork->getWork();
        $territories = $work->getTerritories();
        $broadcastChannels = $contractWork->getDistributionContract()->getBroadcastChannels();

        foreach ($territories as $territory) {
            $workTerritory = $work->getWorkTerritory($territory);
            foreach ($broadcastChannels as $broadcastChannel) {
                if (!$workTerritory?->hasBroadcastChannel($broadcastChannel)) {
                    continue;
                }

                $dto->addTerritory(
                    WorkTerritoryFormDto::getFormName($territory, $broadcastChannel),
                    (bool) $contractWork->getWorkTerritory($territory)?->getBroadcastChannels()->contains($broadcastChannel)
                );
            }
        }

        return $dto;
    }

    /**
     * @param array<string, bool|string> $territories
     */
    public function updateEntity(DistributionContractWork $contractWork, array $territories): void
    {
        $work = $contractWork->getWork();
        $broadcastChannels = $contractWork->getDistributionContract()->getBroadcastChannels();

        $workTerritories = new ArrayCollection();
        foreach ($work->getTerritories() as $territory) {
            $workTerritory = $this->distributionContractWorkTerritoryManager->findOrCreate($contractWork, $territory);

            foreach ($broadcastChannels as $broadcastChannel) {
                $value = (bool) ($territories[DistributionContractWorkTerritoryFormDto::getFormName($territory, $broadcastChannel)] ?? false);
                if (!$value) {
                    $workTerritory->removeBroadcastChannel($broadcastChannel);

                    continue;
                }

                $workTerritory->addBroadcastChannel($broadcastChannel);
            }

            if ($workTerritory->getBroadcastChannels()->isEmpty()) {
                $this->entityManager->remove($workTerritory);

                continue;
            }

            $workTerritories->add($workTerritory);
        }

        $contractWork->setWorkTerritories($workTerritories);
    }
}
