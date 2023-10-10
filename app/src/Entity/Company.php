<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Shared\BlameableEntity;
use App\Entity\Shared\TimestampableEntity;
use App\Enum\Company\CompanyTypeEnum;
use App\Repository\CompanyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CompanyRepository::class)]
#[ORM\Table(name: 'companies')]
class Company
{
    use BlameableEntity;
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private int $id;

    #[ORM\Column(unique: true)]
    private string $name;

    #[ORM\Column(length: 20, enumType: CompanyTypeEnum::class)]
    private CompanyTypeEnum $type;

    /**
     * @var Collection<UserCompany>
     */
    #[ORM\OneToMany(mappedBy: 'company', targetEntity: UserCompany::class, cascade: ['persist'])]
    private Collection $users;

    /**
     * @var Collection<AcquisitionContract>
     */
    #[ORM\OneToMany(mappedBy: 'company', targetEntity: AcquisitionContract::class)]
    private Collection $acquisitionContracts;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->acquisitionContracts = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getType(): CompanyTypeEnum
    {
        return $this->type;
    }

    public function setType(CompanyTypeEnum $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(UserCompany $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
        }

        return $this;
    }

    public function setUsers(Collection $users): static
    {
        $this->users = $users;

        return $this;
    }

    public function getAcquisitionContracts(): Collection
    {
        return $this->acquisitionContracts;
    }

    public function setAcquisitionContracts(Collection $acquisitionContracts): self
    {
        $this->acquisitionContracts = $acquisitionContracts;

        return $this;
    }
}
