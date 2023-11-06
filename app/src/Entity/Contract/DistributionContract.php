<?php

declare(strict_types=1);

namespace App\Entity\Contract;

use App\Entity\Company;
use App\Entity\Setting\BroadcastChannel;
use App\Entity\Setting\Territory;
use App\Entity\Shared\BlameableEntity;
use App\Entity\Shared\TimestampableEntity;
use App\Entity\Work\Work;
use App\Enum\Common\FrequencyEnum;
use App\Enum\Contract\DistributionContractTypeEnum;
use App\Repository\Contract\DistributionContractRepository;
use App\Tools\Parser\ArrayCollectionParser;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DistributionContractRepository::class)]
#[ORM\Table(name: 'distribution_contracts')]
class DistributionContract
{
    use BlameableEntity;
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private int $id;

    #[ORM\Column(type: Types::BOOLEAN, options: ['default' => false])]
    private bool $archived = false;

    #[ORM\ManyToOne(targetEntity: Company::class, inversedBy: 'distributionContracts')]
    #[ORM\JoinColumn(name: 'company_id', referencedColumnName: 'id', nullable: false)]
    private Company $company;

    #[ORM\ManyToOne(targetEntity: Company::class)]
    #[ORM\JoinColumn(name: 'distributor_id', referencedColumnName: 'id', nullable: false)]
    private Company $distributor;

    #[ORM\Column(unique: true)]
    private string $name;

    #[ORM\Column(length: 20, enumType: DistributionContractTypeEnum::class)]
    private DistributionContractTypeEnum $type;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private \DateTime $signedAt;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $exclusivity = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $commercialConditions = null;

    #[ORM\Column(length: 20, nullable: true, enumType: FrequencyEnum::class)]
    private ?FrequencyEnum $reportFrequency = null;

    /**
     * @var Collection<Territory>
     */
    #[ORM\ManyToMany(targetEntity: Territory::class)]
    #[ORM\JoinTable(name: 'distribution_contracts_territories')]
    #[ORM\OrderBy(['name' => 'ASC'])]
    private Collection $territories;

    /**
     * @var Collection<BroadcastChannel>
     */
    #[ORM\ManyToMany(targetEntity: BroadcastChannel::class)]
    #[ORM\JoinTable(name: 'distribution_contracts_broadcast_channels')]
    #[ORM\OrderBy(['name' => 'ASC'])]
    private Collection $broadcastChannels;

    /**
     * @var Collection<DistributionContractFile>
     */
    #[ORM\OneToMany(mappedBy: 'distributionContract', targetEntity: DistributionContractFile::class, cascade: ['persist'])]
    private Collection $contractFiles;

    /**
     * @var Collection<DistributionContractWork>
     */
    #[ORM\OneToMany(mappedBy: 'distributionContract', targetEntity: DistributionContractWork::class, cascade: ['persist'])]
    private Collection $contractWorks;

    public function __construct()
    {
        $this->territories = new ArrayCollection();
        $this->broadcastChannels = new ArrayCollection();
        $this->contractFiles = new ArrayCollection();
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

    public function isArchived(): bool
    {
        return $this->archived;
    }

    public function setArchived(bool $archived): static
    {
        $this->archived = $archived;

        return $this;
    }

    public function getCompany(): Company
    {
        return $this->company;
    }

    public function setCompany(Company $company): static
    {
        $this->company = $company;

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

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getType(): DistributionContractTypeEnum
    {
        return $this->type;
    }

    public function setType(DistributionContractTypeEnum $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getSignedAt(): \DateTime
    {
        return $this->signedAt;
    }

    public function setSignedAt(\DateTime $signedAt): static
    {
        $this->signedAt = $signedAt;

        return $this;
    }

    public function getExclusivity(): ?string
    {
        return $this->exclusivity;
    }

    public function setExclusivity(?string $exclusivity): static
    {
        $this->exclusivity = $exclusivity;

        return $this;
    }

    public function getCommercialConditions(): ?string
    {
        return $this->commercialConditions;
    }

    public function setCommercialConditions(?string $commercialConditions): static
    {
        $this->commercialConditions = $commercialConditions;

        return $this;
    }

    public function getReportFrequency(): ?FrequencyEnum
    {
        return $this->reportFrequency;
    }

    public function setReportFrequency(?FrequencyEnum $reportFrequency): static
    {
        $this->reportFrequency = $reportFrequency;

        return $this;
    }

    public function getTerritories(): Collection
    {
        return $this->territories;
    }

    public function addTerritory(Territory $territory): static
    {
        if (!$this->territories->contains($territory)) {
            $this->territories->add($territory);
        }

        return $this;
    }

    public function setTerritories(Collection $territories): static
    {
        $this->territories->clear();

        foreach ($territories as $territory) {
            $this->addTerritory($territory);
        }

        return $this;
    }

    /**
     * @return Collection<BroadcastChannel>
     */
    public function getBroadcastChannels(): Collection
    {
        return $this->broadcastChannels;
    }

    public function addBroadcastChannel(BroadcastChannel $broadcastChannel): static
    {
        if (!$this->broadcastChannels->contains($broadcastChannel)) {
            $this->broadcastChannels->add($broadcastChannel);
        }

        return $this;
    }

    public function setBroadcastChannels(Collection $broadcastChannels): static
    {
        $this->broadcastChannels->clear();

        foreach ($broadcastChannels as $channel) {
            $this->addBroadcastChannel($channel);
        }

        return $this;
    }

    /**
     * @return Collection<DistributionContractFile>
     */
    public function getContractFiles(): Collection
    {
        return $this->contractFiles;
    }

    public function addContractFile(DistributionContractFile $contractFile): static
    {
        if (!$this->contractFiles->contains($contractFile)) {
            $this->contractFiles->add($contractFile);
        }

        return $this;
    }

    public function setContractFiles(Collection $contractFiles): static
    {
        $this->contractFiles = $contractFiles;

        return $this;
    }

    public function getContractWorks(): Collection
    {
        return $this->contractWorks;
    }

    public function getWorks(): Collection
    {
        $works = $this->contractWorks->map(fn (DistributionContractWork $contractWork) => $contractWork->getWork());

        return ArrayCollectionParser::sort($works, static fn (Work $a, Work $b) => $a->getName() <=> $b->getName());
    }

    public function getContractWork(Work $work): ?DistributionContractWork
    {
        foreach ($this->contractWorks as $contractWork) {
            if ($contractWork->getWork() === $work) {
                return $contractWork;
            }
        }

        return null;
    }

    public function addContractWork(DistributionContractWork $contractWork): static
    {
        if (!$this->contractWorks->contains($contractWork)) {
            $this->contractWorks->add($contractWork);
        }

        return $this;
    }

    public function removeContractWork(DistributionContractWork $contractWork): static
    {
        if ($this->contractWorks->contains($contractWork)) {
            $this->contractWorks->removeElement($contractWork);
        }

        return $this;
    }

    public function setContractWorks(Collection $contractWorks): static
    {
        $this->contractWorks = $contractWorks;

        return $this;
    }
}
