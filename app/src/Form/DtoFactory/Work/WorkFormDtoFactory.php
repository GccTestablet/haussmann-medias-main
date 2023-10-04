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
            ->setName($work->getName())
            ->setOriginalName($work->getOriginalName())
            ->setSynopsis($work->getSynopsis())
            ->setYear($work->getYear())
            ->setDuration($work->getDuration())
            ->setNationality($work->getNationality())
        ;
    }

    public function updateEntity(WorkFormDto $dto, Work $work): void
    {
        $work
            ->setInternalId($dto->getInternalId())
            ->setName($dto->getName())
            ->setOriginalName($dto->getOriginalName())
            ->setSynopsis($dto->getSynopsis())
            ->setYear($dto->getYear())
            ->setDuration($dto->getDuration())
            ->setNationality($dto->getNationality())
        ;
    }
}
