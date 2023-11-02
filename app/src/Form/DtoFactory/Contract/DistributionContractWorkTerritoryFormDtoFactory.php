<?php

declare(strict_types=1);

namespace App\Form\DtoFactory\Contract;

use App\Entity\Contract\DistributionContractWork;
use App\Form\Dto\Contract\DistributionContractWorkTerritoryFormDto;
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

        $territories = $contractWork->getDistributionContract()->getTerritories();
        $broadcastChannels = $contractWork->getDistributionContract()->getBroadcastChannels();

        foreach ($territories as $territory) {
            if (!$contractWork->getWork()->getTerritories()->contains($territory)) {
                continue;
            }

            $workTerritory = $contractWork->getWorkTerritory($territory);
            $dto->addExclusive(
                DistributionContractWorkTerritoryFormDto::getFormName($territory),
                $workTerritory?->isExclusive() ?? true
            );
            foreach ($broadcastChannels as $broadcastChannel) {
                if (!$contractWork->getWork()->getBroadcastChannels()->contains($broadcastChannel)) {
                    continue;
                }

                $dto->addBroadcastChannel(
                    DistributionContractWorkTerritoryFormDto::getFormName($territory, $broadcastChannel),
                    (bool) $workTerritory?->getBroadcastChannels()->contains($broadcastChannel)
                );
            }
        }

        return $dto;
    }

    public function updateEntity(DistributionContractWork $contractWork, DistributionContractWorkTerritoryFormDto $dto): void
    {
        $territories = $contractWork->getDistributionContract()->getTerritories();
        $broadcastChannels = $contractWork->getDistributionContract()->getBroadcastChannels();

        $workTerritories = new ArrayCollection();
        foreach ($territories as $territory) {
            $workTerritory = $this->distributionContractWorkTerritoryManager->findOrCreate($contractWork, $territory);
            $workTerritory->setExclusive($dto->getExclusive(DistributionContractWorkTerritoryFormDto::getFormName($territory)));

            foreach ($broadcastChannels as $broadcastChannel) {
                $value = $dto->getBroadcastChannel(DistributionContractWorkTerritoryFormDto::getFormName($territory, $broadcastChannel));
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
