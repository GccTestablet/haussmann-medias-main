<?php

declare(strict_types=1);

namespace App\Form\DtoFactory\Contract;

use App\Entity\Company;
use App\Entity\Contract\DistributionContract;
use App\Form\Dto\Contract\DistributionContractFormDto;
use App\Tools\Generator\FileNameGenerator;
use App\Tools\Manager\UploadFileManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class DistributionContractFormDtoFactory
{
    public function __construct(
        private readonly UploadFileManager $uploadFileManager
    ) {}

    public function create(Company $company, ?DistributionContract $contract): DistributionContractFormDto
    {
        if (!$contract) {
            $contract = (new DistributionContract())
                ->setCompany($company)
            ;

            return new DistributionContractFormDto($contract, false);
        }

        $file = null;
        if ($contract->getFileName()) {
            $filePath = $this->uploadFileManager->path($contract->getUploadDir(), $contract->getFileName());
            $file = new UploadedFile(
                $filePath,
                $contract->getOriginalFileName()
            );
        }

        return (new DistributionContractFormDto($contract, true))
            ->setDistributor($contract->getDistributor())
            ->setType($contract->getType())
            ->setFile($file)
            ->setStartsAt($contract->getStartsAt())
            ->setEndsAt($contract->getEndsAt())
            ->setExclusivity($contract->getExclusivity())
            ->setAmount($contract->getAmount())
            ->setReportFrequency($contract->getReportFrequency())
        ;
    }

    public function updateEntity(DistributionContractFormDto $dto, DistributionContract $contract): void
    {
        if ($dto->getFile()) {
            $contract
                ->setOriginalFileName($dto->getFile()->getClientOriginalName())
                ->setFileName(FileNameGenerator::generate($dto->getFile()))
            ;
        }

        $contract
            ->setDistributor($dto->getDistributor())
            ->setType($dto->getType())
            ->setStartsAt($dto->getStartsAt())
            ->setEndsAt($dto->getEndsAt())
            ->setExclusivity($dto->getExclusivity())
            ->setAmount($dto->getAmount())
            ->setReportFrequency($dto->getReportFrequency())
        ;
    }
}
