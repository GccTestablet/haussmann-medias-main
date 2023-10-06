<?php

declare(strict_types=1);

namespace App\Form\Dto\Company;

use App\Entity\Company;
use App\Entity\Contract;
use App\Enum\Common\FrequencyEnum;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class CompanyContractFormDto
{
    private ?Company $beneficiary = null;

    private ?UploadedFile $file = null;

    private ?\DateTime $signedAt = null;

    private ?\DateTime $startsAt = null;

    private ?\DateTime $endsAt = null;

    private ?string $territories = null;

    private ?FrequencyEnum $reportFrequency = null;

    public function __construct(
        private readonly Contract $contract,
        private readonly bool $exists,
    ) {}

    public function getContract(): Contract
    {
        return $this->contract;
    }

    public function isExists(): bool
    {
        return $this->exists;
    }

    public function getBeneficiary(): ?Company
    {
        return $this->beneficiary;
    }

    public function setBeneficiary(?Company $beneficiary): static
    {
        $this->beneficiary = $beneficiary;

        return $this;
    }

    public function getFile(): ?UploadedFile
    {
        return $this->file;
    }

    public function setFile(?UploadedFile $file): static
    {
        $this->file = $file;

        return $this;
    }

    public function getSignedAt(): ?\DateTime
    {
        return $this->signedAt;
    }

    public function setSignedAt(?\DateTime $signedAt): static
    {
        $this->signedAt = $signedAt;

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

    public function getTerritories(): ?string
    {
        return $this->territories;
    }

    public function setTerritories(?string $territories): static
    {
        $this->territories = $territories;

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
