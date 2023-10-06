<?php

declare(strict_types=1);

namespace App\Form\DtoFactory\Work;

use App\Entity\Work;
use App\Entity\WorkRevenue;
use App\Form\Dto\Work\WorkRevenueFormDto;

class WorkRevenueFormDtoFactory
{
    public function create(Work $work, ?WorkRevenue $workRevenue): WorkRevenueFormDto
    {
        if (!$workRevenue) {
            $workRevenue = (new WorkRevenue())
                ->setWork($work)
            ;

            return new WorkRevenueFormDto($workRevenue, false);
        }

        return (new WorkRevenueFormDto($workRevenue, true))
            ->setChannel($workRevenue->getChannel())
            ->setDistributor($workRevenue->getDistributor())
            ->setStartsAt($workRevenue->getStartsAt())
            ->setEndsAt($workRevenue->getEndsAt())
            ->setRevenue($workRevenue->getRevenue())
        ;
    }

    public function updateEntity(WorkRevenueFormDto $dto, WorkRevenue $workRevenue): void
    {
        $workRevenue
            ->setChannel($dto->getChannel())
            ->setDistributor($dto->getDistributor())
            ->setStartsAt($dto->getStartsAt())
            ->setEndsAt($dto->getEndsAt())
            ->setRevenue($dto->getRevenue())
        ;
    }
}
