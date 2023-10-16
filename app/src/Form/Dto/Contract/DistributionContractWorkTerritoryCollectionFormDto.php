<?php

declare(strict_types=1);

namespace App\Form\Dto\Contract;

use App\Entity\Contract\DistributionContractWork;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class DistributionContractWorkTerritoryCollectionFormDto
{
    private Collection $territories;

    public function __construct(
        private readonly DistributionContractWork $distributionContractWork,
        private readonly bool $exists,
    ) {
        $this->territories = new ArrayCollection();
    }

    public function getDistributionContractWork(): DistributionContractWork
    {
        return $this->distributionContractWork;
    }

    public function isExists(): bool
    {
        return $this->exists;
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
}
