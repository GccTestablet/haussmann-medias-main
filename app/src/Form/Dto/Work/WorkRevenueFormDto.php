<?php

declare(strict_types=1);

namespace App\Form\Dto\Work;

use App\Entity\BroadcastChannel;
use App\Entity\Company;
use App\Entity\WorkRevenue;

class WorkRevenueFormDto
{
    private ?Company $distributor = null;

    private ?BroadcastChannel $channel = null;

    private ?\DateTime $startsAt = null;

    private ?\DateTime $endsAt = null;

    private float $revenue = 0.0;

    public function __construct(
        private readonly WorkRevenue $workRevenue,
        private readonly bool $exists,
    ) {}

    public function getWorkRevenue(): WorkRevenue
    {
        return $this->workRevenue;
    }

    public function isExists(): bool
    {
        return $this->exists;
    }

    public function getDistributor(): ?Company
    {
        return $this->distributor;
    }

    public function setDistributor(?Company $distributor): static
    {
        $this->distributor = $distributor;

        return $this;
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

    public function getStartsAt(): ?\DateTime
    {
        return $this->startsAt;
    }

    public function setStartsAt(?\DateTime $startsAt): static
    {
        $this->startsAt = $startsAt;

        return $this;
    }

    public function getEndsAt(): ?\DateTime
    {
        return $this->endsAt;
    }

    public function setEndsAt(?\DateTime $endsAt): static
    {
        $this->endsAt = $endsAt;

        return $this;
    }

    public function getRevenue(): float
    {
        return $this->revenue;
    }

    public function setRevenue(float $revenue): static
    {
        $this->revenue = $revenue;

        return $this;
    }
}
