<?php

declare(strict_types=1);

namespace App\Form\Dto\Company;

use App\Entity\Company;
use App\Enum\Company\CompanyTypeEnum;
use Symfony\Component\Validator\Constraints as Assert;

class CompanyFormDto
{
    #[Assert\NotBlank]
    private ?string $name = null;

    private ?CompanyTypeEnum $type = null;

    public function __construct(
        private readonly Company $company,
        private readonly bool $exists,
    ) {}

    public function getCompany(): Company
    {
        return $this->company;
    }

    public function isExists(): bool
    {
        return $this->exists;
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

    public function getType(): ?CompanyTypeEnum
    {
        return $this->type;
    }

    public function setType(?CompanyTypeEnum $type): static
    {
        $this->type = $type;

        return $this;
    }
}
