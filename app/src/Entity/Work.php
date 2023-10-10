<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Setting\BroadcastChannel;
use App\Entity\Shared\BlameableEntity;
use App\Entity\Shared\TimestampableEntity;
use App\Repository\WorkRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WorkRepository::class)]
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

    #[ORM\Column(nullable: true)]
    private ?string $imdbId = null;

    #[ORM\Column(unique: true)]
    private string $name;

    #[ORM\Column(unique: true)]
    private string $originalName;

    #[ORM\Column(length: 2)]
    private string $country;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?float $minimumGuaranteedBeforeReversion = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?float $minimumCostOfTheTopBeforeReversion = null;

    #[ORM\Column(type: Types::SMALLINT, length: 4, nullable: true)]
    private ?int $year = null;

    #[ORM\Column(nullable: true)]
    private ?string $duration = null;

    /**
     * @var Collection<BroadcastChannel>
     */
    #[ORM\ManyToMany(targetEntity: BroadcastChannel::class, inversedBy: 'works')]
    #[ORM\JoinTable(name: 'works_broadcast_channels')]
    private Collection $broadcastChannels;

    #[ORM\ManyToOne(targetEntity: AcquisitionContract::class, inversedBy: 'works')]
    #[ORM\JoinColumn(name: 'acquisition_contract_id', referencedColumnName: 'id')]
    private AcquisitionContract $acquisitionContract;

    /**
     * @var Collection<WorkAdaptation>
     */
    #[ORM\OneToMany(mappedBy: 'work', targetEntity: WorkAdaptation::class)]
    private Collection $workAdaptations;

    /**
     * @var Collection<WorkReversion>
     */
    #[ORM\OneToMany(mappedBy: 'work', targetEntity: WorkReversion::class, cascade: ['persist'])]
    private Collection $workReversions;

    /**
     * @var Collection<WorkRevenue>
     */
    #[ORM\OneToMany(mappedBy: 'work', targetEntity: WorkRevenue::class, cascade: ['persist'])]
    private Collection $workRevenues;

    public function __construct()
    {
        $this->broadcastChannels = new ArrayCollection();
        $this->workAdaptations = new ArrayCollection();
        $this->workReversions = new ArrayCollection();
        $this->workRevenues = new ArrayCollection();
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

    public function getInternalId(): string
    {
        return $this->internalId;
    }

    public function setInternalId(string $internalId): static
    {
        $this->internalId = $internalId;

        return $this;
    }

    public function getImdbId(): ?string
    {
        return $this->imdbId;
    }

    public function setImdbId(?string $imdbId): static
    {
        $this->imdbId = $imdbId;

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

    public function getCountry(): string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getMinimumGuaranteedBeforeReversion(): ?float
    {
        return $this->minimumGuaranteedBeforeReversion;
    }

    public function setMinimumGuaranteedBeforeReversion(?float $minimumGuaranteedBeforeReversion): static
    {
        $this->minimumGuaranteedBeforeReversion = $minimumGuaranteedBeforeReversion;

        return $this;
    }

    public function getMinimumCostOfTheTopBeforeReversion(): ?float
    {
        return $this->minimumCostOfTheTopBeforeReversion;
    }

    public function setMinimumCostOfTheTopBeforeReversion(?float $minimumCostOfTheTopBeforeReversion): static
    {
        $this->minimumCostOfTheTopBeforeReversion = $minimumCostOfTheTopBeforeReversion;

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

    public function getBroadcastChannels(): Collection
    {
        return $this->broadcastChannels;
    }

    public function setBroadcastChannels(Collection $broadcastChannels): static
    {
        $this->broadcastChannels = $broadcastChannels;

        return $this;
    }

    public function getAcquisitionContract(): AcquisitionContract
    {
        return $this->acquisitionContract;
    }

    public function setAcquisitionContract(AcquisitionContract $acquisitionContract): static
    {
        $this->acquisitionContract = $acquisitionContract;

        return $this;
    }

    public function getWorkAdaptations(): Collection
    {
        return $this->workAdaptations;
    }

    public function setWorkAdaptations(Collection $workAdaptations): static
    {
        $this->workAdaptations = $workAdaptations;

        return $this;
    }

    /**
     * @return Collection<WorkReversion>
     */
    public function getWorkReversions(): Collection
    {
        return $this->workReversions;
    }

    public function setWorkReversions(Collection $workReversions): static
    {
        $this->workReversions = $workReversions;

        return $this;
    }

    public function getWorkRevenues(): Collection
    {
        return $this->workRevenues;
    }

    public function setWorkRevenues(Collection $workRevenues): static
    {
        $this->workRevenues = $workRevenues;

        return $this;
    }

    public function getImdbLink(): string
    {
        return \sprintf('https://www.imdb.com/title/%s/', $this->imdbId);
    }
}
