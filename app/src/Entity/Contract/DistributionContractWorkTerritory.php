<?php

declare(strict_types=1);

namespace App\Entity\Contract;

use App\Entity\Setting\BroadcastChannel;
use App\Entity\Setting\Territory;
use App\Entity\Shared\BlameableEntity;
use App\Entity\Shared\TimestampableEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'distribution_contract_work_territories')]
class DistributionContractWorkTerritory
{
    use BlameableEntity;
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private int $id;

    #[ORM\ManyToOne(targetEntity: DistributionContractWork::class, inversedBy: 'workTerritories')]
    #[ORM\JoinColumn(name: 'distribution_contract_work_id', referencedColumnName: 'id', nullable: false)]
    private DistributionContractWork $contractWork;

    #[ORM\ManyToOne(targetEntity: Territory::class, inversedBy: 'distributionContractWorkTerritories')]
    #[ORM\JoinColumn(name: 'territory_id', referencedColumnName: 'id', nullable: false)]
    private Territory $territory;

    #[ORM\Column(type: Types::BOOLEAN, options: ['default' => true])]
    private bool $exclusive = true;

    #[ORM\ManyToMany(targetEntity: BroadcastChannel::class)]
    #[ORM\JoinTable(name: 'distribution_contract_work_territory_broadcast_channels')]
    private Collection $broadcastChannels;

    public function __construct()
    {
        $this->broadcastChannels = new ArrayCollection();
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

    public function getContractWork(): DistributionContractWork
    {
        return $this->contractWork;
    }

    public function setContractWork(DistributionContractWork $contractWork): static
    {
        $this->contractWork = $contractWork;

        return $this;
    }

    public function getTerritory(): Territory
    {
        return $this->territory;
    }

    public function setTerritory(Territory $territory): static
    {
        $this->territory = $territory;

        return $this;
    }

    public function isExclusive(): bool
    {
        return $this->exclusive;
    }

    public function setExclusive(bool $exclusive): static
    {
        $this->exclusive = $exclusive;

        return $this;
    }

    public function getBroadcastChannels(): Collection
    {
        return $this->broadcastChannels;
    }

    public function addBroadcastChannel(BroadcastChannel $channel): static
    {
        if (!$this->broadcastChannels->contains($channel)) {
            $this->broadcastChannels->add($channel);
        }

        return $this;
    }

    public function hasBroadcastChannel(BroadcastChannel $channel): bool
    {
        return $this->broadcastChannels->contains($channel);
    }

    public function removeBroadcastChannel(BroadcastChannel $channel): static
    {
        if ($this->broadcastChannels->contains($channel)) {
            $this->broadcastChannels->removeElement($channel);
        }

        return $this;
    }

    public function setBroadcastChannels(Collection $broadcastChannels): static
    {
        $this->broadcastChannels = $broadcastChannels;

        return $this;
    }
}
