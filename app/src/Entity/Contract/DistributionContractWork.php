<?php

declare(strict_types=1);

namespace App\Entity\Contract;

use App\Entity\Shared\BlameableEntity;
use App\Entity\Shared\TimestampableEntity;
use App\Entity\Work;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'distribution_contracts_works')]
#[ORM\UniqueConstraint(columns: ['distribution_contract_id', 'work_id'])]
class DistributionContractWork
{
    use BlameableEntity;
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private int $id;

    #[ORM\ManyToOne(targetEntity: DistributionContract::class, inversedBy: 'works')]
    #[ORM\JoinColumn(name: 'distribution_contract_id', referencedColumnName: 'id', nullable: false)]
    private DistributionContract $distributionContract;

    #[ORM\ManyToOne(targetEntity: Work::class, inversedBy: 'distributionContracts')]
    #[ORM\JoinColumn(name: 'work_id', referencedColumnName: 'id', nullable: false)]
    private Work $work;

    /**
     * @var Collection<DistributionContractWorkTerritory>
     */
    #[ORM\OneToMany(mappedBy: 'contractWork', targetEntity: DistributionContractWorkTerritory::class, cascade: ['persist', 'remove'])]
    private Collection $territories;

    #[ORM\OneToMany(mappedBy: 'contractWork', targetEntity: DistributionContractWorkRevenue::class)]
    private Collection $revenues;

    public function __construct(
    ) {
        $this->territories = new ArrayCollection();
        $this->revenues = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getDistributionContract(): DistributionContract
    {
        return $this->distributionContract;
    }

    public function setDistributionContract(DistributionContract $distributionContract): static
    {
        $this->distributionContract = $distributionContract;

        return $this;
    }

    public function getWork(): Work
    {
        return $this->work;
    }

    public function setWork(Work $work): static
    {
        $this->work = $work;

        return $this;
    }

    public function getTerritories(): Collection
    {
        return $this->territories;
    }

    //    public function addTerritory(DistributionContractWorkTerritory $territory): static
    //    {
    //        if (!$this->territories->contains($territory)) {
    //            $this->territories->add($territory);
    //            $territory->setContractWork($this);
    //        }
    //
    //        return $this;
    //    }
    //
    //    public function removeTerritory(DistributionContractWorkTerritory $territory): static
    //    {
    //        if ($this->territories->contains($territory)) {
    //            $this->territories->removeElement($territory);
    //            $territory->setContractWork(null);
    //        }
    //
    //        return $this;
    //    }

    /**
     * @param Collection<DistributionContractWorkTerritory> $territories
     */
    public function setTerritories(Collection $territories): static
    {
        $this->territories->clear();

        foreach ($territories as $territory) {
            $this->territories->add($territory);
            $territory->setContractWork($this);
        }

        return $this;
    }

    public function getRevenues(): Collection
    {
        return $this->revenues;
    }

    public function setRevenues(Collection $revenues): self
    {
        $this->revenues = $revenues;

        return $this;
    }
}
