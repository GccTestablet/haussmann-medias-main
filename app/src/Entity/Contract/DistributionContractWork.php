<?php

declare(strict_types=1);

namespace App\Entity\Contract;

use App\Entity\Setting\Territory;
use App\Entity\Shared\BlameableEntity;
use App\Entity\Shared\TimestampableEntity;
use App\Entity\Work\Work;
use App\Repository\Contract\DistributionContractWorkRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DistributionContractWorkRepository::class)]
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

    #[ORM\ManyToOne(targetEntity: DistributionContract::class, inversedBy: 'contractWorks')]
    #[ORM\JoinColumn(name: 'distribution_contract_id', referencedColumnName: 'id', nullable: false)]
    private DistributionContract $distributionContract;

    #[ORM\ManyToOne(targetEntity: Work::class, inversedBy: 'contractWorks')]
    #[ORM\JoinColumn(name: 'work_id', referencedColumnName: 'id', nullable: false)]
    private Work $work;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private \DateTime $startsAt;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $endsAt = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $amount = null;

    #[ORM\Column(length: 3, options: ['default' => 'EUR'])]
    private string $currency = 'EUR';

    #[ORM\OneToMany(mappedBy: 'contractWork', targetEntity: DistributionContractWorkRevenue::class)]
    private Collection $revenues;

    /**
     * @var Collection<DistributionContractWorkTerritory>
     */
    #[ORM\OneToMany(mappedBy: 'contractWork', targetEntity: DistributionContractWorkTerritory::class, cascade: ['persist'])]
    private Collection $workTerritories;

    public function __construct(
    ) {
        $this->revenues = new ArrayCollection();
        $this->workTerritories = new ArrayCollection();
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

    public function getStartsAt(): \DateTime
    {
        return $this->startsAt;
    }

    public function setStartsAt(\DateTime $startsAt): static
    {
        $this->startsAt = $startsAt;

        return $this;
    }

    public function getEndsAt(): ?\DateTime
    {
        return $this->endsAt;
    }

    public function setEndsAt(?\DateTime $endsAt): static
    {
        $this->endsAt = $endsAt;

        return $this;
    }

    public function getAmount(): ?string
    {
        return $this->amount;
    }

    public function setAmount(?string $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): static
    {
        $this->currency = $currency;

        return $this;
    }

    public function getRevenues(): Collection
    {
        return $this->revenues;
    }

    public function setRevenues(Collection $revenues): static
    {
        $this->revenues = $revenues;

        return $this;
    }

    public function getWorkTerritories(): Collection
    {
        return $this->workTerritories;
    }

    public function getWorkTerritory(Territory $territory): ?DistributionContractWorkTerritory
    {
        foreach ($this->workTerritories as $workTerritory) {
            if ($workTerritory->getTerritory() === $territory) {
                return $workTerritory;
            }
        }

        return null;
    }

    public function addWorkTerritory(DistributionContractWorkTerritory $workTerritory): static
    {
        if (!$this->workTerritories->contains($workTerritory)) {
            $this->workTerritories->add($workTerritory);
        }

        return $this;
    }

    public function setWorkTerritories(Collection $workTerritories): static
    {
        $this->workTerritories = $workTerritories;

        return $this;
    }

    public function getBroadcastChannels(): Collection
    {
        $broadcastChannels = new ArrayCollection();
        foreach ($this->workTerritories as $workTerritory) {
            foreach ($workTerritory->getBroadcastChannels() as $broadcastChannel) {
                /* @phpstan-ignore-next-line */
                if ($broadcastChannels->contains($broadcastChannel)) {
                    continue;
                }

                $broadcastChannels->add($broadcastChannel);
            }
        }

        return $broadcastChannels;
    }
}
