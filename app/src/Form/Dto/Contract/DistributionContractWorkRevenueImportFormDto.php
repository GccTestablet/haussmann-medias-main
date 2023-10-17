<?php

declare(strict_types=1);

namespace App\Form\Dto\Contract;

use App\Entity\Contract\DistributionContract;
use DateTime;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

class DistributionContractWorkRevenueImportFormDto
{
    #[Assert\NotBlank]
    private ?DateTime $startsAt = null;

    private ?DateTime $endsAt = null;
    #[Assert\NotBlank]
    #[Assert\GreaterThanOrEqual(propertyPath: 'startsAt')]
    #[Assert\NotBlank]
    #[Assert\File(
        mimeTypes: [
            'text/csv', 'text/plain', 'application/csv', 'application/x-csv',
            'text/comma-separated-values', 'text/x-comma-separated-values', 'text/tab-separated-values',
        ]
    )]
    private ?UploadedFile $file = null;

    private ?string $currency = 'EUR';

    public function __construct(
        private readonly DistributionContract $distributionContract,
    ) {}

    public function getDistributionContract(): DistributionContract
    {
        return $this->distributionContract;
    }

    public function getStartsAt(): ?DateTime
    {
        return $this->startsAt;
    }

    public function setStartsAt(?DateTime $startsAt): static
    {
        $this->startsAt = $startsAt;

        return $this;
    }

    public function getEndsAt(): ?DateTime
    {
        return $this->endsAt;
    }

    public function setEndsAt(?DateTime $endsAt): static
    {
        $this->endsAt = $endsAt;

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

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(?string $currency): void
    {
        $this->currency = $currency;
    }
}
