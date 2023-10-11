<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Setting\Territory;
use App\Entity\Shared\BlameableEntity;
use App\Entity\Shared\FileInterface;
use App\Entity\Shared\TimestampableEntity;
use App\Enum\Common\FrequencyEnum;
use App\Repository\Contract\AcquisitionContractRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AcquisitionContractRepository::class)]
#[ORM\Table(name: 'acquisition_contracts')]
class AcquisitionContract implements FileInterface
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
    private string $fileName;

    #[ORM\Column]
    private string $originalFileName;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private \DateTime $signedAt;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private \DateTime $startsAt;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $endsAt = null;

    /**
     * @var Collection<Territory>
     */
    #[ORM\ManyToMany(targetEntity: Territory::class, inversedBy: 'acquisitionContracts')]
    #[ORM\JoinTable(name: 'acquisition_contracts_territories')]
    private Collection $territories;

    #[ORM\Column(length: 20, nullable: true, enumType: FrequencyEnum::class)]
    private ?FrequencyEnum $reportFrequency = null;

    /**
     * @var Collection<Work>
     */
    #[ORM\OneToMany(mappedBy: 'acquisitionContract', targetEntity: Work::class)]
    private Collection $works;

    public function __construct()
    {
        $this->territories = new ArrayCollection();
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

    public function getFileName(): string
    {
        return $this->fileName;
    }

    public function setFileName(string $fileName): static
    {
        $this->fileName = $fileName;

        return $this;
    }

    public function getOriginalFileName(): string
    {
        return $this->originalFileName;
    }

    public function setOriginalFileName(string $originalFileName): static
    {
        $this->originalFileName = $originalFileName;

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

    public function getTerritories(): Collection
    {
        return $this->territories;
    }

    /**
     * TODO: Change this method to use EntityParser
     * We use add/remove to avoid a bug with ManyToMany in form type and DTO
     */
    public function setTerritories(Collection $territories): static
    {
        foreach ($this->territories as $territory) {
            $this->territories->removeElement($territory);
        }

        foreach ($territories as $territory) {
            $this->territories->add($territory);
        }

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
        return 'media/acquisition-contracts';
    }
}
