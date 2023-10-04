<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Shared\BlameableEntity;
use App\Entity\Shared\TimestampableEntity;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'works')]
class Work
{
    use BlameableEntity;
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private int $id;

    #[ORM\Column(unique: true)]
    private string $internalId;

    #[ORM\Column(unique: true)]
    private string $name;

    #[ORM\Column(unique: true)]
    private string $originalName;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $synopsis = null;

    #[ORM\Column(type: Types::SMALLINT, length: 4, nullable: true)]
    private ?int $year = null;

    #[ORM\Column(nullable: true)]
    private ?string $duration = null;

    #[ORM\Column(nullable: true)]
    private ?string $nationality = null;

    #[ORM\ManyToOne(targetEntity: Contract::class, inversedBy: 'works')]
    #[ORM\JoinColumn(name: 'contract_id', referencedColumnName: 'id')]
    private Contract $contract;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getInternalId(): string
    {
        return $this->internalId;
    }

    public function setInternalId(string $internalId): static
    {
        $this->internalId = $internalId;

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

    public function getOriginalName(): string
    {
        return $this->originalName;
    }

    public function setOriginalName(string $originalName): static
    {
        $this->originalName = $originalName;

        return $this;
    }

    public function getSynopsis(): ?string
    {
        return $this->synopsis;
    }

    public function setSynopsis(?string $synopsis): static
    {
        $this->synopsis = $synopsis;

        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(?int $year): static
    {
        $this->year = $year;

        return $this;
    }

    public function getDuration(): ?string
    {
        return $this->duration;
    }

    public function setDuration(?string $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function getNationality(): ?string
    {
        return $this->nationality;
    }

    public function setNationality(?string $nationality): static
    {
        $this->nationality = $nationality;

        return $this;
    }

    public function getContract(): Contract
    {
        return $this->contract;
    }

    public function setContract(Contract $contract): static
    {
        $this->contract = $contract;

        return $this;
    }
}
