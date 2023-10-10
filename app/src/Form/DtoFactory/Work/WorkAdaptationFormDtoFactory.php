<?php

declare(strict_types=1);

namespace App\Form\DtoFactory\Work;

use App\Entity\Work;
use App\Entity\WorkAdaptation;
use App\Form\Dto\Work\WorkAdaptationFormDto;

class WorkAdaptationFormDtoFactory
{
    public function create(Work $work, ?WorkAdaptation $workAdaptation): WorkAdaptationFormDto
    {
        if (!$workAdaptation) {
            $workAdaptation = (new WorkAdaptation())
                ->setWork($work)
            ;

            return new WorkAdaptationFormDto($workAdaptation, false);
        }

        return (new WorkAdaptationFormDto($workAdaptation, true))
            ->setType($workAdaptation->getType())
            ->setCost($workAdaptation->getCost())
        ;
    }

    public function updateEntity(WorkAdaptationFormDto $dto, WorkAdaptation $workAdaptation): void
    {
        $workAdaptation
            ->setType($dto->getType())
            ->setCost($dto->getCost())
        ;
    }
}
