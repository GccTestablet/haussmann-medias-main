<?php

declare(strict_types=1);

namespace App\Entity\Work;

use App\Entity\Setting\BroadcastChannel;
use App\Entity\Setting\Territory;
use App\Entity\Shared\BlameableEntity;
use App\Entity\Shared\TimestampableEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'work_territories')]
class WorkTerritory
{
    use BlameableEntity;
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Work::class, inversedBy: 'workTerritories')]
    #[ORM\JoinColumn(name: 'work_id', referencedColumnName: 'id', nullable: false)]
    private Work $work;

    #[ORM\ManyToOne(targetEntity: Territory::class)]
    #[ORM\JoinColumn(name: 'territory_id', referencedColumnName: 'id', nullable: false)]
    private Territory $territory;

    #[ORM\ManyToMany(targetEntity: BroadcastChannel::class)]
    #[ORM\JoinTable(name: 'work_territory_broadcast_channels')]
    private Collection $broadcastChannels;

    public function __construct()
    {
        $this->broadcastChannels = new ArrayCollection();
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

    public function getWork(): Work
    {
        return $this->work;
    }

    public function setWork(Work $work): self
    {
        $this->work = $work;

        return $this;
    }

    public function getTerritory(): Territory
    {
        return $this->territory;
    }

    public function setTerritory(Territory $territory): self
    {
        $this->territory = $territory;

        return $this;
    }

    public function getBroadcastChannels(): Collection
    {
        return $this->broadcastChannels;
    }

    public function setBroadcastChannels(Collection $broadcastChannels): self
    {
        $this->broadcastChannels->clear();

        foreach ($broadcastChannels as $channel) {
            $this->broadcastChannels->add($channel);
        }

        return $this;
    }
}
