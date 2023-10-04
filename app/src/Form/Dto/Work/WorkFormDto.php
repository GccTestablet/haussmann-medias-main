<?php

declare(strict_types=1);

namespace App\Form\Dto\Work;

use App\Entity\Work;

class WorkFormDto
{
    private ?string $internalId = null;

    private ?string $name = null;

    private ?string $originalName = null;

    private ?string $synopsis = null;

    private ?int $year = null;

    private ?string $duration = null;

    private ?string $nationality = null;

    public function __construct(
        private readonly Work $work,
        private readonly bool $exists,
    ) {}

    public function getWork(): Work
    {
        return $this->work;
    }

    public function isExists(): bool
    {
        return $this->exists;
    }

    public function getInternalId(): ?string
    {
        return $this->internalId;
    }

    public function setInternalId(?string $internalId): static
    {
        $this->internalId = $internalId;

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

    public function getOriginalName(): ?string
    {
        return $this->originalName;
    }

    public function setOriginalName(?string $originalName): static
    {
        $this->originalName = $originalName;

        return $this;
    }

    public function getSynopsis(): ?string
    {
        return $this->synopsis;
    }

    public function setSynopsis(?string $synopsis): static
    {
        $this->synopsis = $synopsis;

        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(?int $year): static
    {
        $this->year = $year;

        return $this;
    }

    public function getDuration(): ?string
    {
        return $this->duration;
    }

    public function setDuration(?string $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function getNationality(): ?string
    {
        return $this->nationality;
    }

    public function setNationality(?string $nationality): static
    {
        $this->nationality = $nationality;

        return $this;
    }
}
