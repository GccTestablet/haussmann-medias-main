<?php

declare(strict_types=1);

namespace App\Form\Dto\Contract;

use App\Entity\Company;
use App\Entity\Contract\DistributionContract;
use App\Enum\Common\FrequencyEnum;
use App\Enum\Contract\DistributionContractTypeEnum;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class DistributionContractFormDto
{
    private ?Company $distributor = null;

    private ?string $name = null;

    private ?DistributionContractTypeEnum $type = null;

    /**
     * @var UploadedFile[]
     */
    private array $files = [];

    private ?\DateTime $startsAt = null;

    private ?\DateTime $endsAt = null;

    private ?string $exclusivity = null;

    private ?float $amount = null;

    private string $currency = 'EUR';

    private ?FrequencyEnum $reportFrequency = null;

    public function __construct(
        private readonly DistributionContract $contract,
        private readonly bool $exists,
    ) {}

    public function getContract(): DistributionContract
    {
        return $this->contract;
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getType(): ?DistributionContractTypeEnum
    {
        return $this->type;
    }

    public function setType(?DistributionContractTypeEnum $type): static
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return UploadedFile[]
     */
    public function getFiles(): array
    {
        return $this->files;
    }

    /**
     * @param UploadedFile[] $files
     */
    public function setFiles(array $files): static
    {
        $this->files = $files;

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

    public function getExclusivity(): ?string
    {
        return $this->exclusivity;
    }

    public function setExclusivity(?string $exclusivity): static
    {
        $this->exclusivity = $exclusivity;

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

    public function getReportFrequency(): ?FrequencyEnum
    {
        return $this->reportFrequency;
    }

    public function setReportFrequency(?FrequencyEnum $reportFrequency): static
    {
        $this->reportFrequency = $reportFrequency;

        return $this;
    }
}
