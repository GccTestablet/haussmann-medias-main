<?php

declare(strict_types=1);

namespace App\Form\DtoFactory\Contract;

use App\Entity\Company;
use App\Entity\Contract\AcquisitionContract;
use App\Form\Dto\Contract\AcquisitionContractFormDto;
use App\Tools\Generator\FileNameGenerator;
use App\Tools\Manager\UploadFileManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AcquisitionContractFormDtoFactory
{
    public function __construct(
        private readonly UploadFileManager $uploadFileManager
    ) {}

    public function create(Company $company, ?AcquisitionContract $contract): AcquisitionContractFormDto
    {
        if (!$contract) {
            $contract = (new AcquisitionContract())
                ->setCompany($company)
            ;

            return new AcquisitionContractFormDto($contract, false);
        }

        $filePath = $this->uploadFileManager->path($contract->getUploadDir(), $contract->getFileName());
        $file = new UploadedFile(
            $filePath,
            $contract->getOriginalFileName()
        );

        return (new AcquisitionContractFormDto($contract, true))
            ->setBeneficiary($contract->getBeneficiary())
            ->setName($contract->getName())
            ->setFile($file)
            ->setSignedAt($contract->getSignedAt())
            ->setStartsAt($contract->getStartsAt())
            ->setEndsAt($contract->getEndsAt())
            ->setReportFrequency($contract->getReportFrequency())
        ;
    }

    public function updateEntity(AcquisitionContractFormDto $dto, AcquisitionContract $contract): void
    {
        if ($dto->getFile()) {
            $contract
                ->setOriginalFileName($dto->getFile()->getClientOriginalName())
                ->setFileName(FileNameGenerator::generate($dto->getFile()))
            ;
        }

        $contract
            ->setBeneficiary($dto->getBeneficiary())
            ->setName($dto->getName())
            ->setSignedAt($dto->getSignedAt())
            ->setStartsAt($dto->getStartsAt())
            ->setEndsAt($dto->getEndsAt())
            ->setReportFrequency($dto->getReportFrequency())
        ;
    }
}
