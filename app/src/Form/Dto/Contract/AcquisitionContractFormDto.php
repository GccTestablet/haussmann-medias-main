<?php

declare(strict_types=1);

namespace App\Form\Dto\Contract;

use App\Entity\Company;
use App\Entity\Contract\AcquisitionContract;
use App\Enum\Common\FrequencyEnum;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

class AcquisitionContractFormDto
{
    private ?Company $beneficiary = null;

    #[Assert\NotBlank()]
    private ?string $name = null;

    #[Assert\NotBlank()]
    private ?UploadedFile $file = null;

    #[Assert\NotBlank()]
    private ?\DateTime $signedAt = null;

    #[Assert\NotBlank()]
    private ?\DateTime $startsAt = null;

    #[Assert\GreaterThanOrEqual(propertyPath: 'startsAt')]
    private ?\DateTime $endsAt = null;

    private ?FrequencyEnum $reportFrequency = null;

    public function __construct(
        private readonly AcquisitionContract $contract,
        private readonly bool $exists,
    ) {}

    public function getContract(): AcquisitionContract
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

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
