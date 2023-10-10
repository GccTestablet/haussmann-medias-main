<?php

declare(strict_types=1);

namespace App\Form\Dto\Setting;

use App\Entity\Territory;

class TerritoryFormDto
{
    private ?string $name = null;

    private ?string $description = null;

    public function __construct(
        private readonly Territory $territory,
        private readonly bool $exists,
    ) {}

    public function getTerritory(): Territory
    {
        return $this->territory;
    }

    public function isExists(): bool
    {
        return $this->exists;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }
}
