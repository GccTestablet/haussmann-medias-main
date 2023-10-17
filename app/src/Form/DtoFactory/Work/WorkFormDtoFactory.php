<?php

declare(strict_types=1);

namespace App\Form\DtoFactory\Work;

use App\Entity\Contract\AcquisitionContract;
use App\Entity\Work\Work;
use App\Form\Dto\Work\WorkFormDto;
use App\Service\Work\WorkManager;
use App\Tools\Parser\ObjectParser;

class WorkFormDtoFactory
{
    public function __construct(
        private readonly WorkManager $workManager,
        private readonly ObjectParser $objectParser
    ) {}

    public function create(?Work $work, AcquisitionContract $contract): WorkFormDto
    {
        if (!$work) {
            $internalId = $this->workManager->findNextInternalId($contract);
            $work = (new Work())
                ->setAcquisitionContract($contract)
                ->setInternalId($internalId)
            ;

            return (new WorkFormDto($work, false))
                ->setInternalId($internalId)
            ;
        }

        $dto = new WorkFormDto($work, true);
        $this->objectParser->mergeFromObject($work, $dto);

        return $dto;
    }

    public function updateEntity(WorkFormDto $dto, Work $work): void
    {
        if (!$dto->isExists()) {
            $internalId = $this->workManager->findNextInternalId($work->getAcquisitionContract());
            $work->setInternalId($internalId);
        }

        $this->objectParser->mergeFromObject($dto, $work);
    }
}
