<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Setting\BroadcastChannel;
use App\Entity\Shared\BlameableEntity;
use App\Entity\Shared\TimestampableEntity;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'work_revenues')]
class WorkRevenue
{
    use BlameableEntity;
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Work::class, inversedBy: 'workRevenues')]
    #[ORM\JoinColumn(name: 'work_id', referencedColumnName: 'id', nullable: false)]
    private Work $work;

    #[ORM\ManyToOne(targetEntity: Company::class)]
    #[ORM\JoinColumn(name: 'distributor_id', referencedColumnName: 'id', nullable: false)]
    private Company $distributor;

    #[ORM\ManyToOne(targetEntity: BroadcastChannel::class)]
    #[ORM\JoinColumn(name: 'channel_id', referencedColumnName: 'id', nullable: false)]
    private BroadcastChannel $channel;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private \DateTime $startsAt;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private \DateTime $endsAt;

    #[ORM\Column(type: Types::FLOAT, precision: 10, scale: 2, options: ['default' => 0.0])]
    private float $revenue = 0.0;

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

    public function getDistributor(): Company
    {
        return $this->distributor;
    }

    public function setDistributor(Company $distributor): static
    {
        $this->distributor = $distributor;

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

    public function getStartsAt(): \DateTime
    {
        return $this->startsAt;
    }

    public function setStartsAt(\DateTime $startsAt): static
    {
        $this->startsAt = $startsAt;

        return $this;
    }

    public function getEndsAt(): \DateTime
    {
        return $this->endsAt;
    }

    public function setEndsAt(\DateTime $endsAt): static
    {
        $this->endsAt = $endsAt;

        return $this;
    }

    public function getRevenue(): float
    {
        return $this->revenue;
    }

    public function setRevenue(float $revenue): static
    {
        $this->revenue = $revenue;

        return $this;
    }
}
