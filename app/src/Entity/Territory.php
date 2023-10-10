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
#[ORM\Table(name: 'territories')]
class Territory
{
    use BlameableEntity;
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private int $id;

    #[ORM\Column(unique: true)]
    private string $name;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    /**
     * @var Collection<Contract>
     */
    #[ORM\ManyToMany(targetEntity: Contract::class, mappedBy: 'territories')]
    private Collection $acquisitionContracts;

    public function __construct()
    {
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

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
