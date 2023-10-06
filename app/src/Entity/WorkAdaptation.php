<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Shared\BlameableEntity;
use App\Entity\Shared\TimestampableEntity;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'work_adaptations')]
class WorkAdaptation
{
    use BlameableEntity;
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Work::class, inversedBy: 'workAdaptations')]
    #[ORM\JoinColumn(name: 'work_id', referencedColumnName: 'id', nullable: false)]
    private Work $work;

    #[ORM\Column(type: Types::FLOAT, precision: 10, scale: 2, nullable: true)]
    private ?float $dubbingCost = null;

    #[ORM\Column(type: Types::FLOAT, precision: 10, scale: 2, nullable: true)]
    private ?float $manufacturingCost = null;

    #[ORM\Column(type: Types::FLOAT, precision: 10, scale: 2, nullable: true)]
    private ?float $mediaMatrixFileCost = null;

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

    public function getDubbingCost(): ?float
    {
        return $this->dubbingCost;
    }

    public function setDubbingCost(?float $dubbingCost): self
    {
        $this->dubbingCost = $dubbingCost;

        return $this;
    }

    public function getManufacturingCost(): ?float
    {
        return $this->manufacturingCost;
    }

    public function setManufacturingCost(?float $manufacturingCost): self
    {
        $this->manufacturingCost = $manufacturingCost;

        return $this;
    }

    public function getMediaMatrixFileCost(): ?float
    {
        return $this->mediaMatrixFileCost;
    }

    public function setMediaMatrixFileCost(?float $mediaMatrixFileCost): self
    {
        $this->mediaMatrixFileCost = $mediaMatrixFileCost;

        return $this;
    }
}
