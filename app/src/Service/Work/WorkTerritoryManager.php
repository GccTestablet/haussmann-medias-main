<?php

declare(strict_types=1);

namespace App\Service\Work;

use App\Entity\Setting\Territory;
use App\Entity\Work\Work;
use App\Entity\Work\WorkTerritory;

class WorkTerritoryManager
{
    public function findOrCreate(Work $work, Territory $territory): WorkTerritory
    {
        $workTerritory = $work->getWorkTerritory($territory);
        if ($workTerritory) {
            return $workTerritory;
        }

        return (new WorkTerritory())
            ->setWork($work)
            ->setTerritory($territory)
        ;
    }
}
