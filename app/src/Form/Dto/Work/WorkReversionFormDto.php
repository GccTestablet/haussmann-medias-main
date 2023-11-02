<?php

declare(strict_types=1);

namespace App\Form\Dto\Work;

use App\Entity\Setting\BroadcastChannel;
use App\Entity\Work\Work;

class WorkReversionFormDto
{
    public static function getFormName(BroadcastChannel $broadcastChannel): string
    {
        return \sprintf('broadcast_channel_%d', $broadcastChannel->getId());
    }

    /**
     * @var array<string, float|null>
     */
    private array $reversions = [];

    public function __construct(
        private readonly Work $work,
    ) {}

    public function getWork(): Work
    {
        return $this->work;
    }

    /**
     * @return array<string, float|null>
     */
    public function getReversions(): array
    {
        return $this->reversions;
    }

    public function getReversion(string $key): ?float
    {
        return $this->reversions[$key] ?? null;
    }

    public function addReversion(string $key, ?float $value): static
    {
        $this->reversions[$key] = $value;

        return $this;
    }

    /**
     * @param array<string, float|null> $reversions
     */
    public function setReversions(array $reversions): static
    {
        $this->reversions = $reversions;

        return $this;
    }
}
