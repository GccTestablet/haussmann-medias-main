<?php

declare(strict_types=1);

namespace App\Form\DtoFactory\Work;

use App\Entity\Work;
use App\Entity\WorkReversion;
use App\Form\Dto\Work\WorkReversionFormDto;

class WorkReversionFormDtoFactory
{
    public function create(Work $work, ?WorkReversion $workReversion): WorkReversionFormDto
    {
        if (!$workReversion) {
            $workReversion = (new WorkReversion())
                ->setWork($work)
            ;

            return new WorkReversionFormDto($workReversion, false);
        }

        return (new WorkReversionFormDto($workReversion, true))
            ->setChannel($workReversion->getChannel())
            ->setPercentageReversion($workReversion->getPercentageReversion())
        ;
    }

    public function updateEntity(WorkReversionFormDto $dto, WorkReversion $workReversion): void
    {
        $workReversion
            ->setChannel($dto->getChannel())
            ->setPercentageReversion($dto->getPercentageReversion())
        ;
    }
}
