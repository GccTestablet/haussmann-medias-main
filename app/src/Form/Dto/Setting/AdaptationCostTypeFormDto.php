<?php

declare(strict_types=1);

namespace App\Form\Dto\Setting;

use App\Entity\Setting\AdaptationCostType;

class AdaptationCostTypeFormDto
{
    private bool $archived = false;

    private ?string $name = null;

    public function __construct(
        private readonly AdaptationCostType $type,
        private readonly bool $exists,
    ) {}

    public function getType(): AdaptationCostType
    {
        return $this->type;
    }

    public function isExists(): bool
    {
        return $this->exists;
    }

    public function isArchived(): ?bool
    {
        return $this->archived;
    }

    public function setArchived(?bool $archived): static
    {
        $this->archived = $archived;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }
}
