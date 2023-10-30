<?php

declare(strict_types=1);

namespace App\Form\DtoFactory\Contract;

use App\Entity\Company;
use App\Entity\Contract\AcquisitionContract;
use App\Entity\Contract\AcquisitionContractFile;
use App\Form\Dto\Contract\AcquisitionContractFormDto;
use App\Tools\Generator\FileNameGenerator;
use App\Tools\Manager\UploadFileManager;
use App\Tools\Parser\ObjectParser;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AcquisitionContractFormDtoFactory
{
    public function __construct(
        private readonly UploadFileManager $uploadFileManager,
        private readonly ObjectParser $objectParser
    ) {}

    public function create(Company $company, ?AcquisitionContract $contract): AcquisitionContractFormDto
    {
        if (!$contract) {
            $contract = (new AcquisitionContract())
                ->setCompany($company)
            ;

            return new AcquisitionContractFormDto($contract, false);
        }

        $files = [];
        foreach ($contract->getContractFiles() as $contractFile) {
            $files[$contractFile->getId()] = new UploadedFile(
                $this->uploadFileManager->path($contractFile->getUploadDir(), $contractFile->getFileName()),
                $contractFile->getOriginalFileName()
            );
        }

        $dto = (new AcquisitionContractFormDto($contract, true))
            ->setFiles($files)
        ;

        $this->objectParser->mergeFromObject($contract, $dto, ['company', 'files', 'works']);

        return $dto;
    }

    public function updateEntity(AcquisitionContractFormDto $dto, AcquisitionContract $contract): void
    {
        if (0 !== \count($dto->getFiles())) {
            foreach ($dto->getFiles() as $file) {
                $contractFile = (new AcquisitionContractFile())
                    ->setAcquisitionContract($contract)
                    ->setOriginalFileName($file->getClientOriginalName())
                    ->setFileName(FileNameGenerator::generate($file))
                    ->setFile($file)
                ;

                //                $this->uploadFileManager->upload($file, $contractFile->getUploadDir(), $contractFile->getFileName());

                $contract->addContractFile($contractFile);
            }
        }

        $this->objectParser->mergeFromObject($dto, $contract, ['company', 'files', 'works']);
    }
}
