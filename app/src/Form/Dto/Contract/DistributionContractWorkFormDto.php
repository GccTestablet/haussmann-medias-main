<?php

declare(strict_types=1);

namespace App\Form\Dto\Contract;

use App\Entity\Contract\DistributionContractWork;
use App\Entity\Work\Work;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class DistributionContractWorkFormDto
{
    private ?Work $work = null;

    private Collection $territories;

    private Collection $broadcastChannels;

    private Collection $broadcastServices;

    public function __construct(
        private readonly DistributionContractWork $distributionContractWork,
        private readonly bool $exists,
    ) {
        $this->territories = new ArrayCollection();
        $this->broadcastChannels = new ArrayCollection();
        $this->broadcastServices = new ArrayCollection();
    }

    public function getDistributionContractWork(): DistributionContractWork
    {
        return $this->distributionContractWork;
    }

    public function isExists(): bool
    {
        return $this->exists;
    }

    public function getWork(): ?Work
    {
        return $this->work;
    }

    public function setWork(?Work $work): self
    {
        $this->work = $work;

        return $this;
    }

    public function getTerritories(): Collection
    {
        return $this->territories;
    }

    public function setTerritories(Collection $territories): static
    {
        $this->territories = $territories;

        return $this;
    }

    public function getBroadcastChannels(): Collection
    {
        return $this->broadcastChannels;
    }

    public function setBroadcastChannels(Collection $broadcastChannels): static
    {
        $this->broadcastChannels = $broadcastChannels;

        return $this;
    }

    public function getBroadcastServices(): Collection
    {
        return $this->broadcastServices;
    }

    public function setBroadcastServices(Collection $broadcastServices): static
    {
        $this->broadcastServices = $broadcastServices;

        return $this;
    }
}
