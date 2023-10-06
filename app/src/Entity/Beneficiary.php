<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Shared\BlameableEntity;
use App\Entity\Shared\TimestampableEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'beneficiaries')]
class Beneficiary
{
    use BlameableEntity;
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private int $id;

    #[ORM\Column]
    private string $name;

    /**
     * @var Collection<Contract>
     */
    #[ORM\OneToMany(mappedBy: 'beneficiary', targetEntity: Contract::class)]
    private Collection $contracts;

    public function __construct()
    {
        $this->contracts = new ArrayCollection();
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

    public function getContracts(): Collection
    {
        return $this->contracts;
    }

    public function setContracts(Collection $contracts): static
    {
        $this->contracts = $contracts;

        return $this;
    }
}
