<?php

declare(strict_types=1);

namespace App\Form\Dto\Work;

use App\Entity\Work\Work;
use Symfony\Component\Validator\Constraints as Assert;

class WorkFormDto
{
    private ?string $internalId = null;

    #[Assert\Regex('/^tt[0-9]*$/')]
    private ?string $imdbId = null;

    private ?string $frenchTitle = null;

    private ?string $originalTitle = null;

    private ?string $country = null;

    private ?float $minimumGuaranteed = null;

    private ?float $ceilingOfRecoverableCosts = null;
    #[Assert\Currency()]
    private string $currency = 'EUR';
    #[Assert\Range(min: 1900, max: 2100)]
    private ?int $year = null;

    private ?string $duration = null;

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

    public function getImdbId(): ?string
    {
        return $this->imdbId;
    }

    public function setImdbId(?string $imdbId): static
    {
        $this->imdbId = $imdbId;

        return $this;
    }

    public function getFrenchTitle(): ?string
    {
        return $this->frenchTitle;
    }

    public function setFrenchTitle(?string $frenchTitle): static
    {
        $this->frenchTitle = $frenchTitle;

        return $this;
    }

    public function getOriginalTitle(): ?string
    {
        return $this->originalTitle;
    }

    public function setOriginalTitle(?string $originalTitle): static
    {
        $this->originalTitle = $originalTitle;

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

    public function getMinimumGuaranteed(): ?float
    {
        return $this->minimumGuaranteed;
    }

    public function setMinimumGuaranteed(?float $minimumGuaranteed): static
    {
        $this->minimumGuaranteed = $minimumGuaranteed;

        return $this;
    }

    public function getCeilingOfRecoverableCosts(): ?float
    {
        return $this->ceilingOfRecoverableCosts;
    }

    public function setCeilingOfRecoverableCosts(?float $ceilingOfRecoverableCosts): static
    {
        $this->ceilingOfRecoverableCosts = $ceilingOfRecoverableCosts;

        return $this;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): void
    {
        $this->currency = $currency;
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
}
