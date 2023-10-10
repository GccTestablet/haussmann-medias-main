<?php

declare(strict_types=1);

namespace App\Form\DtoFactory\Work;

use App\Entity\AcquisitionContract;
use App\Entity\Work;
use App\Form\Dto\Work\WorkFormDto;
use App\Service\Work\WorkManager;

class WorkFormDtoFactory
{
    public function __construct(
        private readonly WorkManager $workManager
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

        return (new WorkFormDto($work, true))
            ->setInternalId($work->getInternalId())
            ->setImdbId($work->getImdbId())
            ->setName($work->getName())
            ->setOriginalName($work->getOriginalName())
            ->setCountry($work->getCountry())
            ->setMinimumGuaranteedBeforeReversion($work->getMinimumGuaranteedBeforeReversion())
            ->setMinimumCostOfTheTopBeforeReversion($work->getMinimumCostOfTheTopBeforeReversion())
            ->setYear($work->getYear())
            ->setDuration($work->getDuration())
            ->setBroadcastChannels($work->getBroadcastChannels())
        ;
    }

    public function updateEntity(WorkFormDto $dto, Work $work): void
    {
        if (!$dto->isExists()) {
            $internalId = $this->workManager->findNextInternalId($work->getAcquisitionContract());
            $work->setInternalId($internalId);
        }
        $work
            ->setImdbId($dto->getImdbId())
            ->setName($dto->getName())
            ->setOriginalName($dto->getOriginalName())
            ->setCountry($dto->getCountry())
            ->setMinimumGuaranteedBeforeReversion($dto->getMinimumGuaranteedBeforeReversion())
            ->setMinimumCostOfTheTopBeforeReversion($dto->getMinimumCostOfTheTopBeforeReversion())
            ->setYear($dto->getYear())
            ->setDuration($dto->getDuration())
            ->setBroadcastChannels($dto->getBroadcastChannels())
        ;
    }
}
