<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Setting\BroadcastChannel;
use App\Entity\Setting\BroadcastService;
use App\Entity\Setting\Territory;
use App\Entity\Shared\BlameableEntity;
use App\Entity\Shared\TimestampableEntity;
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
     * @var Collection<Territory>
     */
    #[ORM\ManyToMany(targetEntity: Territory::class)]
    #[ORM\JoinTable(name: 'distribution_contract_work_territories')]
    private Collection $territories;

    /**
     * @var Collection<BroadcastChannel>
     */
    #[ORM\ManyToMany(targetEntity: BroadcastChannel::class)]
    #[ORM\JoinTable(name: 'distribution_contract_work_broadcast_channels')]
    private Collection $broadcastChannels;

    /**
     * @var Collection<BroadcastService>
     */
    #[ORM\ManyToMany(targetEntity: BroadcastService::class)]
    #[ORM\JoinTable(name: 'distribution_contract_work_broadcast_services')]
    private Collection $broadcastServices;

    public function __construct(
    ) {
        $this->territories = new ArrayCollection();
        $this->broadcastChannels = new ArrayCollection();
        $this->broadcastServices = new ArrayCollection();
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

    public function setTerritories(Collection $territories): static
    {
        $this->territories = $territories;

        return $this;
    }

    public function getBroadcastChannels(): Collection
    {
        return $this->broadcastChannels;
    }

    public function setBroadcastChannels(Collection $broadcastChannels): static
    {
        $this->broadcastChannels = $broadcastChannels;

        return $this;
    }

    public function getBroadcastServices(): Collection
    {
        return $this->broadcastServices;
    }

    public function setBroadcastServices(Collection $broadcastServices): static
    {
        $this->broadcastServices = $broadcastServices;

        return $this;
    }
}
