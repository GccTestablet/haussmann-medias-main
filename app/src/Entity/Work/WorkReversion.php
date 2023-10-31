<?php

declare(strict_types=1);

namespace App\Entity\Work;

use App\Entity\Setting\BroadcastChannel;
use App\Entity\Shared\BlameableEntity;
use App\Entity\Shared\TimestampableEntity;
use App\Repository\Work\WorkReversionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WorkReversionRepository::class)]
#[ORM\Table(name: 'work_reversions')]
class WorkReversion
{
    use BlameableEntity;
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Work::class, inversedBy: 'workReversions')]
    #[ORM\JoinColumn(name: 'work_id', referencedColumnName: 'id', nullable: false)]
    private Work $work;

    #[ORM\ManyToOne(targetEntity: BroadcastChannel::class, inversedBy: 'workReversions')]
    #[ORM\JoinColumn(name: 'channel_id', referencedColumnName: 'id', nullable: false)]
    private BroadcastChannel $channel;

    #[ORM\Column(type: Types::FLOAT, precision: 10, scale: 2, options: ['default' => 0.0])]
    private float $percentageReversion = 0.0;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

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

    public function getChannel(): BroadcastChannel
    {
        return $this->channel;
    }

    public function setChannel(BroadcastChannel $channel): static
    {
        $this->channel = $channel;

        return $this;
    }

    public function getPercentageReversion(): ?float
    {
        return $this->percentageReversion;
    }

    public function setPercentageReversion(?float $percentageReversion): static
    {
        $this->percentageReversion = $percentageReversion;

        return $this;
    }
}
