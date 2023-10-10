<?php

declare(strict_types=1);

namespace App\Form\Dto\Work;

use App\Entity\Work;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;

class WorkFormDto
{
    private ?string $internalId = null;

    #[Assert\Regex('/^tt[0-9]*$/')]
    private ?string $imdbId = null;

    private ?string $name = null;

    private ?string $originalName = null;

    private ?string $country = null;

    private ?float $minimumGuaranteedBeforeReversion = null;

    private ?float $minimumCostOfTheTopBeforeReversion = null;

    #[Assert\Range(min: 1900, max: 2100)]
    private ?int $year = null;

    private ?string $duration = null;

    private Collection $broadcastChannels;

    public function __construct(
        private readonly Work $work,
        private readonly bool $exists,
    ) {
        $this->broadcastChannels = new ArrayCollection();
    }

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

    public function getImdbId(): ?string
    {
        return $this->imdbId;
    }

    public function setImdbId(?string $imdbId): static
    {
        $this->imdbId = $imdbId;

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

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): static
    {
        $this->country = $country;

        return $this;
    }

    public function getMinimumGuaranteedBeforeReversion(): ?float
    {
        return $this->minimumGuaranteedBeforeReversion;
    }

    public function setMinimumGuaranteedBeforeReversion(?float $minimumGuaranteedBeforeReversion): static
    {
        $this->minimumGuaranteedBeforeReversion = $minimumGuaranteedBeforeReversion;

        return $this;
    }

    public function getMinimumCostOfTheTopBeforeReversion(): ?float
    {
        return $this->minimumCostOfTheTopBeforeReversion;
    }

    public function setMinimumCostOfTheTopBeforeReversion(?float $minimumCostOfTheTopBeforeReversion): static
    {
        $this->minimumCostOfTheTopBeforeReversion = $minimumCostOfTheTopBeforeReversion;

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

    public function getBroadcastChannels(): Collection
    {
        return $this->broadcastChannels;
    }

    public function setBroadcastChannels(Collection $broadcastChannels): static
    {
        $this->broadcastChannels = $broadcastChannels;

        return $this;
    }
}
