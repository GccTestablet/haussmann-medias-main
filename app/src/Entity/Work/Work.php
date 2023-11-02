<?php

declare(strict_types=1);

namespace App\Entity\Work;

use App\Entity\Contract\AcquisitionContract;
use App\Entity\Contract\DistributionContractWork;
use App\Entity\Setting\BroadcastChannel;
use App\Entity\Setting\Territory;
use App\Entity\Shared\BlameableEntity;
use App\Entity\Shared\TimestampableEntity;
use App\Enum\Work\WorkQuotaEnum;
use App\Repository\WorkRepository;
use App\Tools\Parser\ArrayCollectionParser;
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

    #[ORM\Column(type: Types::BOOLEAN, options: ['default' => false])]
    private bool $archived = false;

    #[ORM\Column(nullable: true)]
    private ?string $imdbId = null;

    #[ORM\Column]
    private string $name;

    #[ORM\Column]
    private string $originalName;

    /**
     * @var array<string>
     */
    #[ORM\Column(type: Types::SIMPLE_ARRAY)]
    private array $countries = [];

    #[ORM\Column(length: 20, enumType: WorkQuotaEnum::class)]
    private WorkQuotaEnum $quota;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?float $minimumGuaranteedBeforeReversion = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?float $minimumCostOfTheTopBeforeReversion = null;

    #[ORM\Column(length: 3, options: ['default' => 'EUR'])]
    private string $currency = 'EUR';

    #[ORM\Column(type: Types::SMALLINT, length: 4, nullable: true)]
    private ?int $year = null;

    #[ORM\Column(nullable: true)]
    private ?string $duration = null;

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
     * @var Collection<WorkTerritory>
     */
    #[ORM\OneToMany(mappedBy: 'work', targetEntity: WorkTerritory::class, cascade: ['persist'])]
    private Collection $workTerritories;

    /**
     * @var Collection<DistributionContractWork>
     */
    #[ORM\OneToMany(mappedBy: 'work', targetEntity: DistributionContractWork::class, cascade: ['persist'])]
    private Collection $contractWorks;

    public function __construct()
    {
        $this->workAdaptations = new ArrayCollection();
        $this->workReversions = new ArrayCollection();
        $this->workTerritories = new ArrayCollection();
        $this->contractWorks = new ArrayCollection();
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

    public function isArchived(): bool
    {
        return $this->archived;
    }

    public function setArchived(bool $archived): static
    {
        $this->archived = $archived;

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

    /**
     * @return string[]
     */
    public function getCountries(): array
    {
        return $this->countries;
    }

    /**
     * @param array<string> $countries
     */
    public function setCountries(array $countries): self
    {
        $this->countries = $countries;

        return $this;
    }

    public function getQuota(): WorkQuotaEnum
    {
        return $this->quota;
    }

    public function setQuota(WorkQuotaEnum $quota): static
    {
        $this->quota = $quota;

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

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): static
    {
        $this->currency = $currency;

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

    public function getWorkReversion(BroadcastChannel $channel): ?WorkReversion
    {
        foreach ($this->workReversions as $workReversion) {
            if ($workReversion->getChannel() === $channel) {
                return $workReversion;
            }
        }

        return null;
    }

    public function addWorkReversion(WorkReversion $workReversion): static
    {
        if (!$this->workReversions->contains($workReversion)) {
            $this->workReversions->add($workReversion);
            $workReversion->setWork($this);
        }

        return $this;
    }

    public function setWorkReversions(Collection $workReversions): static
    {
        $this->workReversions = $workReversions;

        return $this;
    }

    /**
     * @return Collection<WorkTerritory>
     */
    public function getWorkTerritories(): Collection
    {
        return $this->workTerritories;
    }

    /**
     * @return Collection<Territory>
     */
    public function getTerritories(): Collection
    {
        return $this->workTerritories
            ->map(fn (WorkTerritory $workTerritory) => $workTerritory->getTerritory())
        ;
    }

    /**
     * @return Collection<BroadcastChannel>
     */
    public function getBroadcastChannels(): Collection
    {
        $channels = new ArrayCollection();
        foreach ($this->workTerritories as $workTerritory) {
            foreach ($workTerritory->getBroadcastChannels() as $channel) {
                /* @phpstan-ignore-next-line */
                if ($channels->contains($channel)) {
                    continue;
                }

                $channels->add($channel);
            }
        }

        return ArrayCollectionParser::sort(
            $channels,
            static fn (BroadcastChannel $a, BroadcastChannel $b) => $a->isArchived() <=> $b->isArchived()
        );
    }

    public function getWorkTerritory(Territory $territory): ?WorkTerritory
    {
        foreach ($this->workTerritories as $workTerritory) {
            if ($workTerritory->getTerritory() === $territory) {
                return $workTerritory;
            }
        }

        return null;
    }

    public function addWorkTerritory(WorkTerritory $workTerritory): static
    {
        if (!$this->workTerritories->contains($workTerritory)) {
            $this->workTerritories->add($workTerritory);
            $workTerritory->setWork($this);
        }

        return $this;
    }

    /**
     * @param Collection<WorkTerritory> $workTerritories
     */
    public function setWorkTerritories(Collection $workTerritories): static
    {
        $this->workTerritories->clear();

        foreach ($workTerritories as $workTerritory) {
            $this->addWorkTerritory($workTerritory);
        }

        return $this;
    }

    public function getContractWorks(): Collection
    {
        return $this->contractWorks;
    }

    public function setContractWorks(Collection $contractWorks): static
    {
        $this->contractWorks = $contractWorks;

        return $this;
    }

    public function getImdbLink(): string
    {
        return \sprintf('https://www.imdb.com/title/%s/', $this->imdbId);
    }
}
