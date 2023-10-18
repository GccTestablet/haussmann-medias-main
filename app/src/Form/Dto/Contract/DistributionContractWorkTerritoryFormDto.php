<?php

declare(strict_types=1);

namespace App\Form\Dto\Contract;

use App\Entity\Contract\DistributionContractWork;
use App\Entity\Setting\BroadcastChannel;
use App\Entity\Setting\Territory;

class DistributionContractWorkTerritoryFormDto
{
    public static function getFormName(Territory $territory, BroadcastChannel $broadcastChannel): string
    {
        return \sprintf('territory_%d_broadcast_channel_%d', $territory->getId(), $broadcastChannel->getId());
    }

    /**
     * @var array<string, bool>
     */
    private array $territories = [];

    public function __construct(
        private readonly DistributionContractWork $contractWork
    ) {}

    public function getContractWork(): DistributionContractWork
    {
        return $this->contractWork;
    }

    /**
     * @return array<string, bool>
     */
    public function getTerritories(): array
    {
        return $this->territories;
    }

    public function getTerritory(string $key): bool
    {
        return $this->territories[$key] ?? false;
    }

    public function addTerritory(string $key, bool $value): static
    {
        $this->territories[$key] = $value;

        return $this;
    }

    /**
     * @param array<string, bool> $territories
     */
    public function setTerritories(array $territories): self
    {
        $this->territories = $territories;

        return $this;
    }
}
