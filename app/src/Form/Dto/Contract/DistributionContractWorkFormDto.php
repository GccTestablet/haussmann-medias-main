<?php

declare(strict_types=1);

namespace App\Form\Dto\Contract;

use App\Entity\Contract\DistributionContractWork;
use App\Entity\Work\Work;
use Symfony\Component\Validator\Constraints as Assert;

class DistributionContractWorkFormDto
{
    #[Assert\NotBlank]
    private ?Work $work = null;

    #[Assert\NotBlank]
    private ?\DateTime $startsAt = null;

    #[Assert\GreaterThanOrEqual(propertyPath: 'startsAt')]
    private ?\DateTime $endsAt = null;

    #[Assert\Type(type: 'float')]
    private ?float $amount = null;

    #[Assert\Currency()]
    private string $currency = 'EUR';

    public function __construct(
        private readonly DistributionContractWork $contractWork,
        private readonly bool $exists,
    ) {}

    public function getContractWork(): DistributionContractWork
    {
        return $this->contractWork;
    }

    public function isExists(): bool
    {
        return $this->exists;
    }

    public function getWork(): ?Work
    {
        return $this->work;
    }

    public function setWork(?Work $work): static
    {
        $this->work = $work;

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

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(?float $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): static
    {
        $this->currency = $currency;

        return $this;
    }
}
