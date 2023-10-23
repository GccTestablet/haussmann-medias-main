<?php

declare(strict_types=1);

namespace App\Entity\Contract;

use App\Entity\Shared\BlameableEntity;
use App\Entity\Shared\FileInterface;
use App\Entity\Shared\TimestampableEntity;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'acquisition_contract_files')]
class AcquisitionContractFile implements FileInterface
{
    use BlameableEntity;
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private int $id;

    #[ORM\ManyToOne(targetEntity: AcquisitionContract::class, inversedBy: 'contractFiles')]
    #[ORM\JoinColumn(name: 'acquisition_contract_id', referencedColumnName: 'id', nullable: false)]
    private AcquisitionContract $acquisitionContract;

    #[ORM\Column(unique: true)]
    private string $fileName;

    #[ORM\Column()]
    private string $originalFileName;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

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

    public function getUploadDir(): string
    {
        return \sprintf('media/acquisition-contracts/%d', $this->acquisitionContract->getId());
    }
}
