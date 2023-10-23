<?php

declare(strict_types=1);

namespace App\Entity\Contract;

use App\Entity\Company;
use App\Entity\Shared\BlameableEntity;
use App\Entity\Shared\TimestampableEntity;
use App\Entity\Work\Work;
use App\Enum\Common\FrequencyEnum;
use App\Repository\Contract\AcquisitionContractRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AcquisitionContractRepository::class)]
#[ORM\Table(name: 'acquisition_contracts')]
class AcquisitionContract
{
    use BlameableEntity;
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Company::class, inversedBy: 'acquisitionContracts')]
    #[ORM\JoinColumn(name: 'company_id', referencedColumnName: 'id', nullable: false)]
    private Company $company;

    #[ORM\ManyToOne(targetEntity: Company::class)]
    #[ORM\JoinColumn(name: 'beneficiary_id', referencedColumnName: 'id', nullable: false)]
    private Company $beneficiary;

    #[ORM\Column(unique: true)]
    private string $name;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private \DateTime $signedAt;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $startsAt = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $endsAt = null;

    #[ORM\Column(length: 20, nullable: true, enumType: FrequencyEnum::class)]
    private ?FrequencyEnum $reportFrequency = null;

    /**
     * @var Collection<AcquisitionContractFile>
     */
    #[ORM\OneToMany(mappedBy: 'acquisitionContract', targetEntity: AcquisitionContractFile::class, cascade: ['persist'])]
    private Collection $contractFiles;

    /**
     * @var Collection<Work>
     */
    #[ORM\OneToMany(mappedBy: 'acquisitionContract', targetEntity: Work::class)]
    private Collection $works;

    public function __construct()
    {
        $this->contractFiles = new ArrayCollection();
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

    public function getBeneficiary(): Company
    {
        return $this->beneficiary;
    }

    public function setBeneficiary(Company $beneficiary): static
    {
        $this->beneficiary = $beneficiary;

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

    public function getSignedAt(): \DateTime
    {
        return $this->signedAt;
    }

    public function setSignedAt(\DateTime $signedAt): static
    {
        $this->signedAt = $signedAt;

        return $this;
    }

    public function getStartsAt(): ?\DateTime
    {
        return $this->startsAt;
    }

    public function setStartsAt(?\DateTime $startsAt): static
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

    public function getReportFrequency(): ?FrequencyEnum
    {
        return $this->reportFrequency;
    }

    public function setReportFrequency(?FrequencyEnum $reportFrequency): static
    {
        $this->reportFrequency = $reportFrequency;

        return $this;
    }

    public function getContractFiles(): Collection
    {
        return $this->contractFiles;
    }

    public function addContractFile(AcquisitionContractFile $contractFile): static
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

    public function getWorks(): Collection
    {
        return $this->works;
    }

    public function setWorks(Collection $works): static
    {
        $this->works = $works;

        return $this;
    }
}
