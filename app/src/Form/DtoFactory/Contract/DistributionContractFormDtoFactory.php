<?php

declare(strict_types=1);

namespace App\Form\DtoFactory\Contract;

use App\Entity\Company;
use App\Entity\Contract\DistributionContract;
use App\Entity\Contract\DistributionContractFile;
use App\Form\Dto\Contract\DistributionContractFormDto;
use App\Tools\Generator\FileNameGenerator;
use App\Tools\Parser\ObjectParser;

class DistributionContractFormDtoFactory
{
    public function __construct(
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

        $dto = new DistributionContractFormDto($contract, true);
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
