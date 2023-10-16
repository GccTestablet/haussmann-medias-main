<?php

declare(strict_types=1);

namespace App\Form\Dto\Company;

use App\Entity\Company;
use App\Entity\User;
use App\Enum\User\UserCompanyPermissionEnum;
use Symfony\Component\Validator\Constraints as Assert;

class CompanyUserFormDto
{
    #[Assert\NotBlank]
    private ?User $user = null;

    #[Assert\NotBlank]
    private ?UserCompanyPermissionEnum $permission = null;

    public function __construct(
        private readonly Company $company,
    ) {}

    public function getCompany(): Company
    {
        return $this->company;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getPermission(): ?UserCompanyPermissionEnum
    {
        return $this->permission;
    }

    public function setPermission(?UserCompanyPermissionEnum $permission): static
    {
        $this->permission = $permission;

        return $this;
    }
}
