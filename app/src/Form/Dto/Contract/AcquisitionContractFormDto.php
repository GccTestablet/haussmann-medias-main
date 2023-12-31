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
    #[Assert\NotBlank()]
    private ?Company $beneficiary = null;

    private bool $archived = false;

    #[Assert\NotBlank()]
    private ?string $name = null;

    /**
     * @var UploadedFile[]
     */
    private array $files = [];

    #[Assert\NotBlank()]
    private ?\DateTime $signedAt = null;

    private ?\DateTime $startsAt = null;

    #[Assert\GreaterThanOrEqual(propertyPath: 'startsAt')]
    private ?\DateTime $endsAt = null;

    private ?FrequencyEnum $reportFrequency = null;

    private ?string $reportFrequencyComment = null;

    public function __construct(
        private readonly AcquisitionContract $contract,
        private readonly bool $exists,
    ) {}

    public function getCompany(): Company
    {
        return $this->contract->getCompany();
    }

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

    public function isArchived(): bool
    {
        return $this->archived;
    }

    public function setArchived(bool $archived): static
    {
        $this->archived = $archived;

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

    public function getReportFrequencyComment(): ?string
    {
        return $this->reportFrequencyComment;
    }

    public function setReportFrequencyComment(?string $reportFrequencyComment): static
    {
        $this->reportFrequencyComment = $reportFrequencyComment;

        return $this;
    }
}
