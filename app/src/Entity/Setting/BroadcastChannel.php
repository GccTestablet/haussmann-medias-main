<?php

declare(strict_types=1);

namespace App\Entity\Setting;

use App\Entity\Contract\DistributionContractWorkRevenue;
use App\Entity\Shared\BlameableEntity;
use App\Entity\Shared\TimestampableEntity;
use App\Entity\Work\WorkReversion;
use App\Repository\Broadcast\BroadcastChannelRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BroadcastChannelRepository::class)]
#[ORM\Table(name: 'setting_broadcast_channels')]
class BroadcastChannel
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

    #[ORM\Column(unique: true)]
    private string $slug;

    /**
     * @var Collection<BroadcastService>
     */
    #[ORM\OneToMany(mappedBy: 'broadcastChannel', targetEntity: BroadcastService::class)]
    private Collection $broadcastServices;

    /**
     * @var Collection<WorkReversion>
     */
    #[ORM\OneToMany(mappedBy: 'channel', targetEntity: WorkReversion::class, cascade: ['persist'])]
    private Collection $workReversions;

    #[ORM\OneToMany(mappedBy: 'broadcastChannel', targetEntity: DistributionContractWorkRevenue::class)]
    private Collection $distributionContractWorkRevenues;

    public function __construct()
    {
        $this->broadcastServices = new ArrayCollection();
        $this->workReversions = new ArrayCollection();
        $this->distributionContractWorkRevenues = new ArrayCollection();
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

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

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

    public function getWorkReversions(): Collection
    {
        return $this->workReversions;
    }

    public function setWorkReversions(Collection $workReversions): static
    {
        $this->workReversions = $workReversions;

        return $this;
    }

    public function getDistributionContractWorkRevenues(): Collection
    {
        return $this->distributionContractWorkRevenues;
    }

    public function setDistributionContractWorkRevenues(Collection $distributionContractWorkRevenues): static
    {
        $this->distributionContractWorkRevenues = $distributionContractWorkRevenues;

        return $this;
    }
}
