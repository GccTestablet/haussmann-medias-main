<?php

declare(strict_types=1);

namespace App\Form\DtoFactory\Contract;

use App\Entity\Company;
use App\Entity\Contract\DistributionContract;
use App\Entity\Contract\DistributionContractFile;
use App\Form\Dto\Contract\DistributionContractFormDto;
use App\Tools\Generator\FileNameGenerator;
use App\Tools\Manager\UploadFileManager;
use App\Tools\Parser\ObjectParser;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class DistributionContractFormDtoFactory
{
    public function __construct(
        private readonly UploadFileManager $uploadFileManager,
        private readonly ObjectParser $objectParser
    ) {}

    public function create(Company $company, ?DistributionContract $contract): DistributionContractFormDto
    {
        if (!$contract) {
            $contract = (new DistributionContract())
                ->setCompany($company)
            ;

            return new DistributionContractFormDto($contract, false);
        }

        $files = [];
        foreach ($contract->getContractFiles() as $contractFile) {
            $files[$contractFile->getId()] = new UploadedFile(
                $this->uploadFileManager->path($contractFile->getUploadDir(), $contractFile->getFileName()),
                $contractFile->getOriginalFileName()
            );
        }

        $dto = (new DistributionContractFormDto($contract, true))
            ->setFiles($files)
        ;
        $this->objectParser->mergeFromObject($contract, $dto, ['company', 'files', 'works']);

        return $dto;
    }

    public function updateEntity(DistributionContractFormDto $dto, DistributionContract $contract): void
    {
        if (0 !== \count($dto->getFiles())) {
            foreach ($dto->getFiles() as $file) {
                $contractFile = (new DistributionContractFile())
                    ->setDistributionContract($contract)
                    ->setOriginalFileName($file->getClientOriginalName())
                    ->setFileName(FileNameGenerator::generate($file))
                    ->setFile($file)
                ;

                $contract->addContractFile($contractFile);
            }
        }

        $this->objectParser->mergeFromObject($dto, $contract, ['company', 'files']);
    }
}
