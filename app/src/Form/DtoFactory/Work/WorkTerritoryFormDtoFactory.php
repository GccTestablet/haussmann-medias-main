<?php

declare(strict_types=1);

namespace App\Form\DtoFactory\Work;

use App\Entity\Work\Work;
use App\Form\Dto\Work\WorkTerritoryFormDto;
use App\Service\Setting\BroadcastChannelManager;
use App\Service\Setting\TerritoryManager;
use App\Service\Work\WorkTerritoryManager;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;

class WorkTerritoryFormDtoFactory
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly TerritoryManager $territoryManager,
        private readonly BroadcastChannelManager $broadcastChannelManager,
        private readonly WorkTerritoryManager $workTerritoryManager
    ) {}

    public function create(Work $work): WorkTerritoryFormDto
    {
        $dto = new WorkTerritoryFormDto($work);

        $broadcastChannels = $this->broadcastChannelManager->findAll($work->getBroadcastChannels());

        foreach ($this->territoryManager->findAll($work->getTerritories()) as $territory) {
            $workTerritory = $work->getWorkTerritory($territory);
            $dto->addExclusive(
                WorkTerritoryFormDto::getFormName($territory),
                $workTerritory?->isExclusive() ?? true
            );

            foreach ($broadcastChannels as $broadcastChannel) {
                $dto->addBroadcastChannel(
                    WorkTerritoryFormDto::getFormName($territory, $broadcastChannel),
                    (bool) $workTerritory?->getBroadcastChannels()->contains($broadcastChannel)
                );
            }
        }

        return $dto;
    }

    public function updateEntity(Work $work, WorkTerritoryFormDto $dto): void
    {
        $broadcastChannels = $this->broadcastChannelManager->findAll($work->getBroadcastChannels());

        $workTerritories = new ArrayCollection();
        foreach ($this->territoryManager->findAll($work->getTerritories()) as $territory) {
            $workTerritory = $this->workTerritoryManager->findOrCreate($work, $territory);
            $workTerritory->setExclusive($dto->getExclusive(WorkTerritoryFormDto::getFormName($territory)));

            foreach ($broadcastChannels as $broadcastChannel) {
                $value = $dto->getBroadcastChannel(WorkTerritoryFormDto::getFormName($territory, $broadcastChannel));

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

        $work->setWorkTerritories($workTerritories);
    }
}
