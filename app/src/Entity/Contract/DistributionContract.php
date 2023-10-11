<?php

declare(strict_types=1);

namespace App\Entity\Contract;

use App\Entity\Company;
use App\Entity\Shared\BlameableEntity;
use App\Entity\Shared\FileInterface;
use App\Entity\Shared\TimestampableEntity;
use App\Enum\Common\FrequencyEnum;
use App\Enum\Contract\DistributionContractTypeEnum;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity()]
#[ORM\Table(name: 'distribution_contracts')]
class DistributionContract implements FileInterface
{
    use BlameableEntity;
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Company::class, inversedBy: 'distributionContracts')]
    #[ORM\JoinColumn(name: 'company_id', referencedColumnName: 'id', nullable: false)]
    private Company $company;

    #[ORM\ManyToOne(targetEntity: Company::class)]
    #[ORM\JoinColumn(name: 'distributor_id', referencedColumnName: 'id', nullable: false)]
    private Company $distributor;

    #[ORM\Column(length: 20, enumType: DistributionContractTypeEnum::class)]
    private DistributionContractTypeEnum $type;

    #[ORM\Column(unique: true, nullable: true)]
    private ?string $fileName = null;

    #[ORM\Column(nullable: true)]
    private ?string $originalFileName = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private \DateTime $startsAt;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $endsAt = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $exclusivity = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?float $amount = null;

    #[ORM\Column(length: 20, nullable: true, enumType: FrequencyEnum::class)]
    private ?FrequencyEnum $reportFrequency = null;

    /**
     * @var Collection<DistributionContractWork>
     */
    #[ORM\OneToMany(mappedBy: 'distributionContract', targetEntity: DistributionContractWork::class, cascade: ['persist'])]
    private Collection $works;

    public function __construct()
    {
        $this->works = new ArrayCollection();
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

    public function getType(): DistributionContractTypeEnum
    {
        return $this->type;
    }

    public function setType(DistributionContractTypeEnum $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    public function setFileName(string $fileName): static
    {
        $this->fileName = $fileName;

        return $this;
    }

    public function getOriginalFileName(): ?string
    {
        return $this->originalFileName;
    }

    public function setOriginalFileName(string $originalFileName): static
    {
        $this->originalFileName = $originalFileName;

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

    public function getEndsAt(): ?\DateTime
    {
        return $this->endsAt;
    }

    public function setEndsAt(?\DateTime $endsAt): static
    {
        $this->endsAt = $endsAt;

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

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(?float $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    public function getReportFrequency(): ?FrequencyEnum
    {
        return $this->reportFrequency;
    }

    public function setReportFrequency(?FrequencyEnum $reportFrequency): self
    {
        $this->reportFrequency = $reportFrequency;

        return $this;
    }

    public function getWorks(): Collection
    {
        return $this->works;
    }

    public function setWorks(Collection $works): static
    {
        $this->works = $works;

        return $this;
    }

    public function getUploadDir(): string
    {
        return 'media/distribution-contracts';
    }
}
