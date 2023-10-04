<?php

declare(strict_types=1);

namespace App\Form\Dto\Beneficiary;

use App\Entity\Beneficiary;
use Symfony\Component\Validator\Constraints as Assert;

class BeneficiaryFormDto
{
    #[Assert\NotBlank]
    private ?string $name = null;

    public function __construct(
        private readonly Beneficiary $beneficiary,
        private readonly bool $exists,
    ) {}

    public function getBeneficiary(): Beneficiary
    {
        return $this->beneficiary;
    }

    public function isExists(): bool
    {
        return $this->exists;
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
}
