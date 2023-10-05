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

    #[ORM\OneToMany(mappedBy: 'broadcastChannel', targetEntity: BroadcastService::class)]
    private Collection $broadcastServices;

    public function __construct()
    {
        $this->broadcastServices = new ArrayCollection();
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
}
