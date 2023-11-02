<?php

declare(strict_types=1);

namespace App\Service\Work;

use App\Entity\Setting\BroadcastChannel;
use App\Entity\Work\Work;
use App\Entity\Work\WorkReversion;

class WorkReversionManager
{
    public function find(Work $work, BroadcastChannel $channel): ?WorkReversion
    {
        return $work->getWorkReversion($channel);
    }

    public function findOrCreate(Work $work, BroadcastChannel $channel): WorkReversion
    {
        $workReversion = $this->find($work, $channel);
        if ($workReversion) {
            return $workReversion;
        }

        return (new WorkReversion())
            ->setWork($work)
            ->setChannel($channel)
        ;
    }
}
