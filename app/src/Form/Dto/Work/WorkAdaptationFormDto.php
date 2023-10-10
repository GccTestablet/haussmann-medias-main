<?php

declare(strict_types=1);

namespace App\Form\Dto\Work;

use App\Entity\Setting\AdaptationCostType;
use App\Entity\WorkAdaptation;

class WorkAdaptationFormDto
{
    private ?AdaptationCostType $type = null;

    private ?float $cost = null;

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

    public function getCost(): ?float
    {
        return $this->cost;
    }

    public function setCost(?float $cost): static
    {
        $this->cost = $cost;

        return $this;
    }
}
