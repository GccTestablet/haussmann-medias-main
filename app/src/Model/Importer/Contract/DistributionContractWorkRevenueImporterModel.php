<?php

declare(strict_types=1);

namespace App\Model\Importer\Contract;

class DistributionContractWorkRevenueImporterModel
{
    /**
     * @var array<string, int>
     */
    private array $channels = [];

    public function __construct(
        private readonly string $internalId,
        private readonly string $name,
    ) {}

    public function getInternalId(): string
    {
        return $this->internalId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array<string, int>
     */
    public function getChannels(): array
    {
        return $this->channels;
    }

    public function addChannel(string $name, int $revenue): static
    {
        $this->channels[$name] = $revenue;

        return $this;
    }
}
