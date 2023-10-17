<?php

declare(strict_types=1);

namespace App\Form\Dto\Company;

use App\Entity\Company;
use Symfony\Component\Validator\Constraints as Assert;

class CompanyFormDto
{
    #[Assert\NotBlank]
    private ?string $name = null;

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
}
