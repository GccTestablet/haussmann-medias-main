<?php

declare(strict_types=1);

namespace App\Form\Dto\Work;

use App\Entity\WorkAdaptation;

class WorkAdaptationFormDto
{
    private ?float $dubbingCost = null;

    private ?float $manufacturingCost = null;

    private ?float $mediaMatrixFileCost = null;

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

    public function getDubbingCost(): ?float
    {
        return $this->dubbingCost;
    }

    public function setDubbingCost(?float $dubbingCost): self
    {
        $this->dubbingCost = $dubbingCost;

        return $this;
    }

    public function getManufacturingCost(): ?float
    {
        return $this->manufacturingCost;
    }

    public function setManufacturingCost(?float $manufacturingCost): self
    {
        $this->manufacturingCost = $manufacturingCost;

        return $this;
    }

    public function getMediaMatrixFileCost(): ?float
    {
        return $this->mediaMatrixFileCost;
    }

    public function setMediaMatrixFileCost(?float $mediaMatrixFileCost): self
    {
        $this->mediaMatrixFileCost = $mediaMatrixFileCost;

        return $this;
    }
}
