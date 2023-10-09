<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Shared\BlameableEntity;
use App\Entity\Shared\TimestampableEntity;
use App\Enum\User\UserCompanyPermissionEnum;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'users_companies')]
class UserCompany
{
    use BlameableEntity;
    use TimestampableEntity;

    #[ORM\Column(length: 20, enumType: UserCompanyPermissionEnum::class)]
    private UserCompanyPermissionEnum $permission;

    public function __construct(
        #[ORM\Id]
        #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'companies')]
        #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: false)]
        private readonly User $user,
        #[ORM\Id]
        #[ORM\ManyToOne(targetEntity: Company::class, inversedBy: 'users')]
        #[ORM\JoinColumn(name: 'company_id', referencedColumnName: 'id', nullable: false)]
        private readonly Company $company
    ) {}

    public function getUser(): User
    {
        return $this->user;
    }

    public function getCompany(): Company
    {
        return $this->company;
    }

    public function getPermission(): UserCompanyPermissionEnum
    {
        return $this->permission;
    }

    public function setPermission(UserCompanyPermissionEnum $permission): static
    {
        $this->permission = $permission;

        return $this;
    }
}
