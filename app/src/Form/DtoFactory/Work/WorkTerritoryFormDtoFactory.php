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

        $broadcastChannels = $this->broadcastChannelManager->findAll();

        foreach ($this->territoryManager->findAll() as $territory) {
            $workTerritory = $work->getWorkTerritory($territory);
            foreach ($broadcastChannels as $broadcastChannel) {
                $dto->addTerritory(
                    WorkTerritoryFormDto::getFormName($territory, $broadcastChannel),
                    (bool) $workTerritory?->getBroadcastChannels()->contains($broadcastChannel)
                );
            }
        }

        return $dto;
    }

    /**
     * @param array<string, bool|string> $territories
     */
    public function updateEntity(Work $work, array $territories): void
    {
        $broadcastChannels = $this->broadcastChannelManager->findAll();

        $workTerritories = new ArrayCollection();
        foreach ($this->territoryManager->findAll() as $territory) {
            $workTerritory = $this->workTerritoryManager->findOrCreate($work, $territory);

            foreach ($broadcastChannels as $broadcastChannel) {
                $value = (bool) ($territories[WorkTerritoryFormDto::getFormName($territory, $broadcastChannel)] ?? false);

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
