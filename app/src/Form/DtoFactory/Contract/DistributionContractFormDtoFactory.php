<?php

declare(strict_types=1);

namespace App\Form\DtoFactory\Contract;

use App\Entity\Company;
use App\Entity\Contract\DistributionContract;
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

        $file = null;
        if ($contract->getFileName()) {
            $filePath = $this->uploadFileManager->path($contract->getUploadDir(), $contract->getFileName());
            $file = new UploadedFile(
                $filePath,
                $contract->getOriginalFileName()
            );
        }

        $works = $this->workManager->findByDistributionContract($contract);

        $dto = (new DistributionContractFormDto($contract, true))
            ->setFile($file)
            ->setWorks(new ArrayCollection($works))
        ;
        $this->objectParser->mergeFromObject($contract, $dto);

        return $dto;
    }

    public function updateEntity(DistributionContractFormDto $dto, DistributionContract $contract): void
    {
        if ($dto->getFile()) {
            $contract
                ->setOriginalFileName($dto->getFile()->getClientOriginalName())
                ->setFileName(FileNameGenerator::generate($dto->getFile()))
            ;
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
        $this->objectParser->mergeFromObject($dto, $contract);
    }
}
