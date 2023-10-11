<?php

declare(strict_types=1);

namespace App\Entity\Setting;

use App\Entity\Contract\DistributionContractWork;
use App\Entity\Shared\BlameableEntity;
use App\Entity\Shared\TimestampableEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'setting_broadcast_services')]
class BroadcastService
{
    use BlameableEntity;
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private int $id;

    #[ORM\Column(unique: true)]
    private string $name;

    #[ORM\ManyToOne(targetEntity: BroadcastChannel::class, inversedBy: 'broadcastServices')]
    #[ORM\JoinColumn(name: 'broadcast_channel_id', referencedColumnName: 'id', nullable: false)]
    private BroadcastChannel $broadcastChannel;

    #[ORM\ManyToMany(targetEntity: DistributionContractWork::class, mappedBy: 'broadcastServices')]
    private Collection $distributionContractWorks;

    public function __construct()
    {
        $this->distributionContractWorks = new ArrayCollection();
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

    public function getBroadcastChannel(): BroadcastChannel
    {
        return $this->broadcastChannel;
    }

    public function setBroadcastChannel(BroadcastChannel $broadcastChannel): static
    {
        $this->broadcastChannel = $broadcastChannel;

        return $this;
    }

    public function getDistributionContractWorks(): Collection
    {
        return $this->distributionContractWorks;
    }

    public function setDistributionContractWorks(Collection $distributionContractWorks): self
    {
        $this->distributionContractWorks = $distributionContractWorks;

        return $this;
    }
}
