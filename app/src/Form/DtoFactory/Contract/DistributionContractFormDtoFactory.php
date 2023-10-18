<?php

declare(strict_types=1);

namespace App\Form\DtoFactory\Contract;

use App\Entity\Company;
use App\Entity\Contract\DistributionContract;
use App\Entity\Contract\DistributionContractFile;
use App\Form\Dto\Contract\DistributionContractFormDto;
use App\Service\Contract\DistributionContractWorkManager;
use App\Service\Work\WorkManager;
use App\Tools\Generator\FileNameGenerator;
use App\Tools\Manager\UploadFileManager;
use App\Tools\Parser\ObjectParser;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class DistributionContractFormDtoFactory
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly WorkManager $workManager,
        private readonly DistributionContractWorkManager $distributionContractWorkManager,
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

        $works = $this->workManager->findByDistributionContract($contract);

        $dto = (new DistributionContractFormDto($contract, true))
            ->setFiles($files)
            ->setWorks(new ArrayCollection($works))
        ;
        $this->objectParser->mergeFromObject($contract, $dto, ['files', 'works']);

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
                ;

                $this->uploadFileManager->upload($file, $contractFile->getUploadDir(), $contractFile->getFileName());

                $contract->addContractFile($contractFile);
            }
        }

        foreach ($contract->getContractWorks() as $contractWork) {
            if ($dto->getWorks()->contains($contractWork->getWork())) {
                continue;
            }

            $this->entityManager->remove($contractWork);
            $contract->removeContractWork($contractWork);
        }

        foreach ($dto->getWorks() as $work) {
            $contractWork = $this->distributionContractWorkManager->findOrCreateByDistributionContractAndWork($contract, $work);

            $contract->addContractWork($contractWork);
        }
        $this->objectParser->mergeFromObject($dto, $contract, ['files', 'works']);
    }
}
