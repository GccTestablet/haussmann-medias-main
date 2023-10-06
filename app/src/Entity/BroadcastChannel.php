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
#[ORM\Table(name: 'broadcast_channels')]
class BroadcastChannel
{
    use BlameableEntity;
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private int $id;

    #[ORM\Column(unique: true)]
    private string $name;

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

    public function __construct()
    {
        $this->broadcastServices = new ArrayCollection();
        $this->workReversions = new ArrayCollection();
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
}
