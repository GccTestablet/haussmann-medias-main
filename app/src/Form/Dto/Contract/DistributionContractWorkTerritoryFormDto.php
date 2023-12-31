<?php

declare(strict_types=1);

namespace App\Form\Dto\Contract;

use App\Entity\Contract\DistributionContractWork;
use App\Entity\Setting\BroadcastChannel;
use App\Entity\Setting\Territory;

class DistributionContractWorkTerritoryFormDto
{
    public static function getFormName(Territory $territory, BroadcastChannel $broadcastChannel = null): string
    {
        if (!$broadcastChannel) {
            return \sprintf('territory_%d', $territory->getId());
        }

        return \sprintf('territory_%d_broadcast_channel_%d', $territory->getId(), $broadcastChannel->getId());
    }

    /**
     * @var array<string, bool>
     */
    private array $exclusives = [];

    /**
     * @var array<string, bool>
     */
    private array $broadcastChannels = [];

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
    public function getExclusives(): array
    {
        return $this->exclusives;
    }

    public function getExclusive(string $key): bool
    {
        return $this->exclusives[$key] ?? true;
    }

    public function addExclusive(string $key, bool $value): static
    {
        $this->exclusives[$key] = $value;

        return $this;
    }

    /**
     * @param array<string, bool> $exclusives
     */
    public function setExclusives(array $exclusives): static
    {
        $this->exclusives = $exclusives;

        return $this;
    }

    /**
     * @return array<string, bool>
     */
    public function getBroadcastChannels(): array
    {
        return $this->broadcastChannels;
    }

    public function getBroadcastChannel(string $key): bool
    {
        return $this->broadcastChannels[$key] ?? false;
    }

    public function addBroadcastChannel(string $key, bool $value): static
    {
        $this->broadcastChannels[$key] = $value;

        return $this;
    }

    /**
     * @param array<string, bool> $broadcastChannels
     */
    public function setBroadcastChannels(array $broadcastChannels): self
    {
        $this->broadcastChannels = $broadcastChannels;

        return $this;
    }
}
