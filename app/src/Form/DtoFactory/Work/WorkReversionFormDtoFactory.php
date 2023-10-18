<?php

declare(strict_types=1);

namespace App\Form\DtoFactory\Work;

use App\Entity\Work\Work;
use App\Entity\Work\WorkReversion;
use App\Form\Dto\Work\WorkReversionFormDto;
use App\Tools\Parser\ObjectParser;

class WorkReversionFormDtoFactory
{
    public function __construct(
        private readonly ObjectParser $objectParser
    ) {}

    public function create(Work $work, ?WorkReversion $workReversion): WorkReversionFormDto
    {
        if (!$workReversion) {
            $workReversion = (new WorkReversion())
                ->setWork($work)
            ;

            return new WorkReversionFormDto($workReversion, false);
        }

        $dto = new WorkReversionFormDto($workReversion, true);
        $this->objectParser->mergeFromObject($workReversion, $dto);

        return $dto;
    }

    public function updateEntity(WorkReversionFormDto $dto, WorkReversion $workReversion): void
    {
        $this->objectParser->mergeFromObject($dto, $workReversion);
    }
}
