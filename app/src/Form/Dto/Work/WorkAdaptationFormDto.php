<?php

declare(strict_types=1);

namespace App\Form\Dto\Work;

use App\Entity\Setting\AdaptationCostType;
use App\Entity\Work\WorkAdaptation;

class WorkAdaptationFormDto
{
    private ?AdaptationCostType $type = null;

    private ?float $amount = null;

    private ?string $currency = 'EUR';

    private ?string $comment = null;

    public function __construct(
        private readonly WorkAdaptation $workAdaptation,
        private readonly bool $exists,
    ) {}

    public function getWorkAdaptation(): WorkAdaptation
    {
        return $this->workAdaptation;
    }

    public function isExists(): bool
    {
        return $this->exists;
    }

    public function getType(): ?AdaptationCostType
    {
        return $this->type;
    }

    public function setType(?AdaptationCostType $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(?float $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(?string $currency): static
    {
        $this->currency = $currency;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): void
    {
        $this->comment = $comment;
    }
}
