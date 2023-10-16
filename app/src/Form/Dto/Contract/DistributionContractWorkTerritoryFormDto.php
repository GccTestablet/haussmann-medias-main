<?php

declare(strict_types=1);

namespace App\Form\Dto\Contract;

use App\Entity\Contract\DistributionContractWork;
use App\Entity\Setting\Territory;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class DistributionContractWorkTerritoryFormDto
{
    private ?Territory $territory = null;

    private ?Collection $broadcastChannels;

    public function __construct(
        //        private readonly DistributionContractWork $distributionContractWork,
        //        private readonly bool $exists,
    ) {
        $this->broadcastChannels = new ArrayCollection();
    }

    //    public function getDistributionContractWork(): DistributionContractWork
    //    {
    //        return $this->distributionContractWork;
    //    }
    //
    //    public function isExists(): bool
    //    {
    //        return $this->exists;
    //    }

    public function getTerritory(): ?Territory
    {
        return $this->territory;
    }

    public function setTerritory(?Territory $territory): static
    {
        $this->territory = $territory;

        return $this;
    }

    public function getBroadcastChannels(): Collection
    {
        return $this->broadcastChannels;
    }

    public function setBroadcastChannels(?Collection $broadcastChannels): static
    {
        $this->broadcastChannels = $broadcastChannels;

        return $this;
    }
}
