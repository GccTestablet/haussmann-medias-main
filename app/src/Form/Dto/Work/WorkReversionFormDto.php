<?php

declare(strict_types=1);

namespace App\Form\Dto\Work;

use App\Entity\Setting\BroadcastChannel;
use App\Entity\WorkReversion;

class WorkReversionFormDto
{
    private ?BroadcastChannel $channel = null;

    private ?float $percentageReversion = 0.0;

    public function __construct(
        private readonly WorkReversion $workReversion,
        private readonly bool $exists,
    ) {}

    public function getWorkReversion(): WorkReversion
    {
        return $this->workReversion;
    }

    public function isExists(): bool
    {
        return $this->exists;
    }

    public function getChannel(): ?BroadcastChannel
    {
        return $this->channel;
    }

    public function setChannel(?BroadcastChannel $channel): static
    {
        $this->channel = $channel;

        return $this;
    }

    public function getPercentageReversion(): ?float
    {
        return $this->percentageReversion;
    }

    public function setPercentageReversion(?float $percentageReversion): static
    {
        $this->percentageReversion = $percentageReversion;

        return $this;
    }
}
