<?php

declare(strict_types=1);

namespace App\Form\DtoFactory\Work;

use App\Entity\Work\Work;
use App\Entity\Work\WorkAdaptation;
use App\Form\Dto\Work\WorkAdaptationFormDto;
use App\Tools\Parser\ObjectParser;

class WorkAdaptationFormDtoFactory
{
    public function __construct(
        private readonly ObjectParser $objectParser
    ) {}

    public function create(Work $work, ?WorkAdaptation $workAdaptation): WorkAdaptationFormDto
    {
        if (!$workAdaptation) {
            $workAdaptation = (new WorkAdaptation())
                ->setWork($work)
            ;

            return new WorkAdaptationFormDto($workAdaptation, false);
        }

        $dto = new WorkAdaptationFormDto($workAdaptation, true);
        $this->objectParser->mergeFromObject($workAdaptation, $dto);

        return $dto;
    }

    public function updateEntity(WorkAdaptationFormDto $dto, WorkAdaptation $workAdaptation): void
    {
        $this->objectParser->mergeFromObject($dto, $workAdaptation);
    }
}
