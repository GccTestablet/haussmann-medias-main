<?php

declare(strict_types=1);

namespace App\Form\Dto\Contract;

use App\Entity\Company;
use App\Entity\Contract\DistributionContract;
use App\Enum\Common\FrequencyEnum;
use App\Enum\Contract\DistributionContractTypeEnum;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

class DistributionContractFormDto
{
    #[Assert\NotBlank]
    private ?Company $distributor = null;

    #[Assert\NotBlank]
    private ?string $name = null;

    #[Assert\NotBlank]
    private ?DistributionContractTypeEnum $type = null;

    private Collection $broadcastChannels;

    /**
     * @var UploadedFile[]
     */
    private array $files = [];

    #[Assert\NotBlank]
    private ?\DateTime $signedAt = null;

    private ?string $exclusivity = null;

    private ?string $commercialConditions = null;

    private ?FrequencyEnum $reportFrequency = null;

    public function __construct(
        private readonly DistributionContract $contract,
        private readonly bool $exists,
    ) {
        $this->broadcastChannels = new ArrayCollection();
    }

    public function getContract(): DistributionContract
    {
        return $this->contract;
    }

    public function getCompany(): Company
    {
        return $this->contract->getCompany();
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

    public function getBroadcastChannels(): Collection
    {
        return $this->broadcastChannels;
    }

    public function setBroadcastChannels(Collection $broadcastChannels): static
    {
        $this->broadcastChannels = $broadcastChannels;

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

    public function getExclusivity(): ?string
    {
        return $this->exclusivity;
    }

    public function setExclusivity(?string $exclusivity): static
    {
        $this->exclusivity = $exclusivity;

        return $this;
    }

    public function getCommercialConditions(): ?string
    {
        return $this->commercialConditions;
    }

    public function setCommercialConditions(?string $commercialConditions): self
    {
        $this->commercialConditions = $commercialConditions;

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
