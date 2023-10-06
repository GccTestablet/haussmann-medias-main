<?php

declare(strict_types=1);

namespace App\Form\DtoFactory\Work;

use App\Entity\Contract;
use App\Entity\Work;
use App\Form\Dto\Work\WorkFormDto;

class WorkFormDtoFactory
{
    public function create(?Work $work, Contract $contract): WorkFormDto
    {
        if (!$work) {
            $work = (new Work())
                ->setContract($contract)
            ;

            return new WorkFormDto($work, false);
        }

        return (new WorkFormDto($work, true))
            ->setInternalId($work->getInternalId())
            ->setImdbId($work->getImdbId())
            ->setName($work->getName())
            ->setOriginalName($work->getOriginalName())
            ->setOrigin($work->getOrigin())
            ->setMinimumGuaranteedBeforeReversion($work->getMinimumGuaranteedBeforeReversion())
            ->setMinimumCostOfTheTopBeforeReversion($work->getMinimumCostOfTheTopBeforeReversion())
            ->setYear($work->getYear())
            ->setDuration($work->getDuration())
        ;
    }

    public function updateEntity(WorkFormDto $dto, Work $work): void
    {
        $work
            ->setInternalId($dto->getInternalId())
            ->setImdbId($dto->getImdbId())
            ->setName($dto->getName())
            ->setOriginalName($dto->getOriginalName())
            ->setOrigin($dto->getOrigin())
            ->setMinimumGuaranteedBeforeReversion($dto->getMinimumGuaranteedBeforeReversion())
            ->setMinimumCostOfTheTopBeforeReversion($dto->getMinimumCostOfTheTopBeforeReversion())
            ->setYear($dto->getYear())
            ->setDuration($dto->getDuration())
        ;
    }
}
