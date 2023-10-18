<?php

declare(strict_types=1);

namespace App\Entity\Contract;

use App\Entity\Shared\BlameableEntity;
use App\Entity\Shared\FileInterface;
use App\Entity\Shared\TimestampableEntity;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'distribution_contracts_files')]
class DistributionContractFile implements FileInterface
{
    use BlameableEntity;
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private int $id;

    #[ORM\ManyToOne(targetEntity: DistributionContract::class, inversedBy: 'contractFiles')]
    #[ORM\JoinColumn(name: 'distribution_contract_id', referencedColumnName: 'id', nullable: false)]
    private DistributionContract $distributionContract;

    #[ORM\Column(unique: true, nullable: true)]
    private ?string $fileName = null;

    #[ORM\Column(nullable: true)]
    private ?string $originalFileName = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getDistributionContract(): DistributionContract
    {
        return $this->distributionContract;
    }

    public function setDistributionContract(DistributionContract $distributionContract): static
    {
        $this->distributionContract = $distributionContract;

        return $this;
    }

    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    public function setFileName(?string $fileName): static
    {
        $this->fileName = $fileName;

        return $this;
    }

    public function getOriginalFileName(): ?string
    {
        return $this->originalFileName;
    }

    public function setOriginalFileName(?string $originalFileName): static
    {
        $this->originalFileName = $originalFileName;

        return $this;
    }

    public function getUploadDir(): string
    {
        return \sprintf('media/distribution-contracts/%d', $this->distributionContract->getId());
    }
}
