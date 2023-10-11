<?php

declare(strict_types=1);

namespace App\Entity\Contract;

use App\Entity\Shared\BlameableEntity;
use App\Entity\Shared\TimestampableEntity;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'distribution_contract_work_revenues')]
class DistributionContractWorkRevenue
{
    use BlameableEntity;
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private int $id;

    #[ORM\ManyToOne(targetEntity: DistributionContractWork::class, inversedBy: 'revenues')]
    #[ORM\JoinColumn(name: 'distribution_contract_work_id', referencedColumnName: 'id', nullable: false)]
    private DistributionContractWork $contractWork;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private \DateTime $startsAt;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private \DateTime $endsAt;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, options: ['DEFAULT' => 0.0])]
    private float $amount = 0.0;

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

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): static
    {
        $this->amount = $amount;

        return $this;
    }
}