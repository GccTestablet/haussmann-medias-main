<?php

declare(strict_types=1);

namespace App\Entity\Setting;

use App\Entity\Contract\DistributionContractWorkTerritory;
use App\Entity\Shared\BlameableEntity;
use App\Entity\Shared\TimestampableEntity;
use App\Repository\Setting\TerritoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TerritoryRepository::class)]
#[ORM\Table(name: 'setting_territories')]
class Territory
{
    use BlameableEntity;
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private int $id;

    #[ORM\Column(type: Types::BOOLEAN, options: ['default' => false])]
    private bool $archived = false;

    #[ORM\Column(unique: true)]
    private string $name;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    /**
     * @var Collection<DistributionContractWorkTerritory>
     */
    #[ORM\OneToMany(mappedBy: 'territory', targetEntity: DistributionContractWorkTerritory::class)]
    private Collection $distributionContractWorkTerritories;

    public function __construct()
    {
        $this->distributionContractWorkTerritories = new ArrayCollection();
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

    public function isArchived(): bool
    {
        return $this->archived;
    }

    public function setArchived(bool $archived): static
    {
        $this->archived = $archived;

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

    /**
     * @return Collection<DistributionContractWorkTerritory>
     */
    public function getDistributionContractWorkTerritories(): Collection
    {
        return $this->distributionContractWorkTerritories;
    }

    /**
     * @param Collection<DistributionContractWorkTerritory> $distributionContractWorkTerritories
     */
    public function setDistributionContractWorkTerritories(Collection $distributionContractWorkTerritories): static
    {
        $this->distributionContractWorkTerritories = $distributionContractWorkTerritories;

        return $this;
    }
}
